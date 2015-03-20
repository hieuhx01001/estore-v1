<?php
namespace backend\controllers;

use backend\models\Customer;
use backend\models\Order;
use backend\models\Product;
use backend\models\ProductSearch;
use frontend\assets\AppAsset;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'dashboard';
        $orderData = Order::find()->indexBy('id')->with('customer')->orderBy(['order_date' => SORT_DESC])->limit(5)->all();
        $productData = Product::find()->indexBy('id')->orderBy(['id' => SORT_DESC])->limit(5)->all();
        $totalProduct = Product::find()->count();
        $totalOrder = Order::find()->count();
        $totalCustomer = Customer::find()->count();
        //var_dump($totalProduct);die();
        return $this->render('index',
        [
            'orderData' => $orderData,
            'productData' => $productData,
            'totalProduct' => $totalProduct,
            'totalOrder' => $totalOrder,
            'totalCustomer' => $totalCustomer
        ]);
    }

    public function actionLogin()
    {
        $this->layout = 'guest';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
