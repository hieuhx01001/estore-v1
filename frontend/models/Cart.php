<?php
namespace frontend\models;

use backend\models\Customer;
use backend\models\Invoice;
use backend\models\Order;
use backend\models\OrderItem;
use frontend\models\exceptions\ProductNotFoundException;
use backend\models\Product;
use yii\web\Session;

class Cart
{
    //-----------------------
    //--     CONSTANTS     --
    //-----------------------

    const SESSION_CART_KEY  = '__cart_items';
    const KEY_DETAILS       = 'details';
    const KEY_QUANTITY      = 'quantity';

    //-----------------------
    //--      STATICS      --
    //-----------------------

    /**
     * The only instance of this class
     *
     * @var static
     */
    protected static $cart = null;

    //-----------------------
    //--     VARIABLES     --
    //-----------------------

    /**
     * Session manager
     *
     * @var Session
     */
    protected $session = null;

    /**
     * Items of the cart.
     *
     * Each item contains 2 property:
     * - details : an instance of \backend\models\Product class.
     * - quantity: Quantity of that item currently in the cart.
     *
     * @var array
     */
    protected $items = null;

    //-----------------------
    //--      METHODS      --
    //-----------------------

    /**
     * Get the only instance of this class
     *
     * @return Cart
     */
    public static function getInstance()
    {
        // Create a singleton cart instance if it has not been created yet
        if (is_null(static::$cart)) {
            static::$cart = new Cart();
        }

        return static::$cart;
    }

    /**
     * Cart instance' construction method.
     */
    protected function __construct()
    {
        // Retrieve the session manager from system container
        $this->session = \Yii::$app->session;

        // Get cart's items from session. If it does not exist
        // in session, assign an empty array to the $items variable
        $this->retrieveItemsFromSession();
    }

    /**
     * Retrieve a specific item from the cart.
     *
     * @param integer $id
     * @return Product|null
     */
    public function get($id)
    {
        if (! isset($this->items[$id])) {
            return null;
        }

        return $this->items[$id];
    }

    /**
     * Retrieve all items from the cart
     *
     * @return Product|null
     */
    public function getAll()
    {
        return $this->items;
    }

    /**
     * Set quantity of a specific product in the cart.
     *
     * If the product is currently not in the cart,
     * retrieve it from database, and put it into the cart.
     *
     * @param integer $id
     * @param integer $qty
     */
    public function set($id, $qty)
    {
        // If the cart does not contain the product,
        // Retrieve it from database and put into the cart
        if (! isset($this->items[$id])) {
            $this->items[$id][static::KEY_DETAILS] = $this->findProduct($id);
        }

        $this->items[$id][static::KEY_QUANTITY] = $qty;

        // Save the items back to session
        $this->saveItemsInSession();
    }

    /**
     * Raise the current quantity of a specific item in the cart
     * by the given quantity.
     *
     * If the cart does not contain the product, use the process
     * of method set() instead.
     *
     * @param integer $id
     * @param integer $qty
     */
    public function add($id, $qty)
    {
        if (! isset($this->items[$id])) {
            return $this->set($id, $qty);
        }

        // Raise the current quantity by the given one
        $this->items[$id][static::KEY_QUANTITY] += $qty;

        // Save the items back to session
        $this->saveItemsInSession();
    }

    /**
     * Decrease the quantity of the specific item in the cart.
     *
     * If the quantity is not given, absolutely remove the item
     * from the cart.
     *
     * @param integer $id
     * @param integer|null $qty
     */
    public function remove($id, $qty = null)
    {
        if (is_null($qty)) {
            unset($this->items[$id]);
        }
        else {
            $curQty = $this->items[$id][static::KEY_QUANTITY];
            $newQty = $curQty - $qty;

            if ($newQty < 0) { $newQty = 0; }

            $this->items[$id][static::KEY_QUANTITY] = $newQty;
        }

        // Save the items back to session
        $this->saveItemsInSession();
    }

    /**
     * Create an Order and an Invoice for the current cart.
     * Then clear the cart.
     *
     * @param array $customerInfo
     * @return bool
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function checkout(array $customerInfo)
    {
        $items = $this->items;

        // Start a new transaction
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            /*
             * Create a new Customer
             */
            $customer = new Customer();
            $customer->setAttributes($customerInfo);

            if (! $customer->save()) {
                $transaction->rollBack();
                return false;
            }

            /*
             * Create a new Order
             */
            $orderCode      = $this->generateRandomOrderCode();
            $orderDetail    = '';
            $tax            = 0;
            $shipmentPrice  = 0;

            $order = new Order();
            $order->setAttributes([
                'customer_id'   => $customer->id,
                'order_code'    => $orderCode,
                'order_status'  => Order::STATUS_NEW,
                'order_date'    => date("Y-m-d H:i:s"),
                'order_detail'  => $orderDetail,
                'shipment_status' => Order::STATUS_SHIPMENT_NO,
                'tax'           => $tax,
                'shipment_price' => $shipmentPrice,
            ]);

            if (! $order->save()) {
                $transaction->rollBack();
                return false;
            }

            /*
             * Create new Order Items and link them to the Order
             */
            foreach ($items as $item) {
                $product = $item[static::KEY_DETAILS];
                $qty = $item[static::KEY_QUANTITY];

                $orderItem = new OrderItem();
                $orderItem->setAttributes([
                    'product_id' => $product->id,
                    'order_id'   => $order->id,
                    'order_item_quantity' => $qty,
                    'order_item_price'    => $product->finalPrice,
                ]);

                if (! $orderItem->save()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            /*
             * Create a new Invoice after finishing the Order
             */
            $fnCalculateOrderTotal = function (array $items) {
                $total = 0;

                foreach ($items as $item) {
                    $product = $item['details'];
                    $qty     = $item['quantity'];

                    $total += $product->finalPrice * $qty;
                }

                return $total;
            };

            $invoice = new Invoice();
            $invoice->setAttributes([
                'id'             => $order->id,
                'invoice_status' => Invoice::PAYMENT_STATUS_NO,
                'invoice_date'   => $order->order_date,
                'payment_date'   => null,
                'total_amount'   => $fnCalculateOrderTotal($items),
                'other_detail'   => null,
                'invoice_code'   => $order->order_code,
            ]);

            if (! $invoice->save()) {
                $transaction->rollBack();
                return false;
            }

            // Finish the transaction
            $transaction->commit();

            // Clear the cart after successfully checking out
            $this->clear();

            return true;
        }
        catch (\Exception $ex) {
            // Rollback the transaction before throwing out the exception
            $transaction->rollBack();
            throw $ex;
        }
    }

    /**
     * Clear the cart
     */
    public function clear()
    {
        $this->items = [];
        $this->saveItemsInSession();
    }

    /**
     * Check if the cart is currently empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * Retrieve the number of item types in the list
     *
     * @return int
     */
    public function countProductTypes()
    {
        return count($this->items);
    }

    /**
     * The the product having the given ID.
     *
     * @param integer $id
     * @return static
     * @throws ProductNotFoundException
     */
    protected function findProduct($id)
    {
        $product = Product::findOne($id);

        // Throw ProductNotFoundException if not found the product
        if (is_null($product)) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    /**
     * Retrieve cart items from session
     */
    protected function retrieveItemsFromSession()
    {
        $this->items = $this->session->get(static::SESSION_CART_KEY, []);
    }

    /**
     * Push cart items back to session to update it
     */
    protected function saveItemsInSession()
    {
        $this->session->set(static::SESSION_CART_KEY, $this->items);
    }

    /**
     * Generate a random order code
     *
     * @return string
     */
    protected function generateRandomOrderCode()
    {
        $numbers    = '0123456789';
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        // Generate 2 random characters
        for ($i = 0; $i < 2; ++$i) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Generate 5 random numbers
        for ($i = 0; $i < 5; ++$i) {
            $code .= $numbers[rand(0, strlen($numbers) - 1)];
        }

        return $code;
    }
}
