<?php
namespace frontend\controllers;

use backend\models\OrderItem;
use backend\models\Product;
use backend\models\ProductCategory;
use Yii;

use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\console\Request;
use yii\data\Pagination;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

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
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'dash_board';
        $newProducts = Product::find()->indexBy('id')
            ->orderBy(['updated_at' => SORT_ASC])
            ->limit(10)->all();

        $saleProducts = Product::find()->indexBy('id')
            ->where('sales_price > 0')
            ->orderBy(['updated_at' => SORT_ASC])
            ->limit(10)->all();

        $hotProduct = OrderItem::find()->indexBy('product_id')
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('count(product_id) DESC')
            ->all();

        return $this->render('index',
            array(
                'newProducts' => $newProducts,
                'saleProducts' => $saleProducts,
                'hotProducts' => $hotProduct
            ));
    }

    public function actionProductAll()
    {

    }
    public function actionProduct()
    {
        $this->layout = 'main_store';
        $request = Yii::$app->request;
        $sortPriceStatus = '';
        $sortNameStatus = '';
        $searchBy = '';
        $pageSize = Yii::$app->params['listPerPage'];
        if ($request->get('search_dropdown') != null ){
            $searchBy = $request->get('search_dropdown');
            if ($searchBy == 'default'){
                $sortPriceStatus = SORT_ASC;
                $sortNameStatus = SORT_ASC;
            }elseif($searchBy == 'price:asc'){
                $sortPriceStatus = SORT_ASC;
            }elseif($searchBy == 'price:desc'){
                $sortPriceStatus = SORT_DESC;
            }elseif($searchBy == 'name:asc'){
                $sortNameStatus =  SORT_ASC;
            }elseif($searchBy == 'name:desc'){
                $sortNameStatus = SORT_DESC;
            }
        }

        if ($request->get('show_dropdown') != null ){
            $pageSize = $request->get('show_dropdown');
        }

        // init option to show only for test
        $categoryId = 3;

        //$pageSize = Yii::$app->params['listPerPage'];
        // query with some option
        $productCate = ProductCategory::find()
            ->leftJoin('product', '`product`.`id` = `product_category`.`product_id`')
            ->where(['category_id'=> $categoryId] );
        if (isset($sortPriceStatus) && $sortPriceStatus != ''){
            $productCate->addOrderBy(['product.price' => @$sortPriceStatus]);
        }else if (isset($sortNameStatus) && $sortNameStatus != ''){
            $productCate->addOrderBy(['product.name' => @$sortNameStatus]);
        }
        // find with page size
        $countQuery = clone $productCate;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize'=>$pageSize]);
        $products = $productCate->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        return $this->render('product',
            array('products' => $products,
                  'pages' => $pages,
                  'searchBy' => $searchBy,
                  'showBy' => $pageSize
            )
        );
    }

    public function actionDetail($id)
    {
        $this->layout = 'main_store';
        $product = $this->findModelProduct($id);

        return $this->render('detail',
            array('product' => $product)
        );
    }


    public function actionLogin()
    {
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

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    protected function findModelProduct($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
