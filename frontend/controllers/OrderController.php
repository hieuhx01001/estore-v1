<?php
namespace frontend\controllers;

use backend\models\Customer;
use backend\models\Product;
use frontend\models\Cart;
use yii\web\Application;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

class OrderController extends Controller
{
    const PARAM_ID       = 'id';
    const PARAM_QUANTITY = 'qty';

    /** @var Application */
    protected $app = null;

    /** @var Cart */
    protected $cart = null;

    /**
     * Controller's initialization
     */
    public function init()
    {
        parent::init();

        // Retrieve system container (application)
        $this->app = \Yii::$app;

        // Retrieve Cart singleton instance
        $this->cart = Cart::getInstance();
    }

    /**
     * ANY /cart
     *
     * @return string
     */
    public function actionCart()
    {
        $cart = $this->cart;

        return $this->render('cart', [
            'items' => $cart->getAll(),
        ]);
    }

    /**
     * ANY /ajax-add-to-cart (AJAX)
     *
     * @return array
     */
    public function actionAjaxAddToCart()
    {
        $app = $this->app;
        $cart = $this->cart;
        $req = $app->request;

        $id = $req->get(static::PARAM_ID);
        $qty = $req->get(static::PARAM_QUANTITY);

        $cart->add($id, $qty);

        // Set this response' format as JSON before returning result
        $this->setResponseFormatAjax();

        return [
            // This request status
            'success'   => true,
            // The item has been added to the cart
            'item'      => $cart->get($id),
            // All cart's items
            'items'     => $cart->getAll(),
        ];
    }

    /**
     * ANY /ajax-set-in-cart (AJAX)
     *
     * @return array
     */
    public function actionAjaxSetInCart()
    {
        // Set this response' format as JSON before returning result
        $this->setResponseFormatAjax();

        $app = $this->app;
        $cart = $this->cart;
        $req = $app->request;

        $id = $req->get(static::PARAM_ID);
        $qty = $req->get(static::PARAM_QUANTITY);

        // Validate quantity
        if ($qty <= 0) {
            return [
                'success' => false,
                'message' => 'Số lượng ít nhất là 1',
            ];
        }

        $cart->set($id, $qty);

        return [
            'success'   => true,
            'item'      => $cart->get($id),
            'items'     => $cart->getAll(),
        ];
    }

    /**
     * ANY /ajax-remove-from-cart (AJAX)
     *
     * @return array
     */
    public function actionAjaxRemoveFromCart()
    {
        // Set this response' format as JSON before returning result
        $this->setResponseFormatAjax();

        $app = $this->app;
        $cart = $this->cart;
        $req = $app->request;

        $id = $req->get('id');

        $cart->remove($id);

        return [
            'success'   => true,
            'items'     => $cart->getAll(),
        ];
    }

    /**
     * POST /checkout
     *
     * @throws MethodNotAllowedHttpException
     */
    public function actionAjaxCheckout()
    {
        $app = $this->app;
        $cart = $this->cart;
        $req = $app->request;

        // Set this response' format as JSON before returning result
        $this->setResponseFormatAjax();

        // If this is not an AJAX request or not a POST one,
        // return an unauthorized request exception
        if (! $req->isAjax or ! $req->isPost) {
            throw new UnauthorizedHttpException();
        }

        // If this is not a POST request, throw an exception for method not allowed
        if (! $req->isPost) {
            throw new MethodNotAllowedHttpException();
        }

        /*
         * TODO: Do some checkout process here...
         */
        $customer = Customer::findOne(1);
        $result = $cart->checkout($customer);

        /*
         * TODO: Return a successful message page
         */
        return [
            'success' => $result,
        ];
    }

    public function actionProducts()
    {
        $products = Product::find()->all();

        return $this->render('products', [
            'products' => $products,
        ]);
    }

    /**
     * Set current response' format as "application/json"
     */
    protected function setResponseFormatAjax()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }
}
