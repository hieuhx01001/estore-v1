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
use yii\web\Response;

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
    public function actionProduct($categoryId)
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
        if ($request->get('categoryId') != null ){
            $categoryId = $request->get('categoryId');
        }
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
                  'showBy' => $pageSize,
                  'categoryId' => $categoryId
            )
        );
    }

    public function actionAll()
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
        if ($request->get('categoryId') != null ){
            $categoryId = $request->get('categoryId');
        }
        //$pageSize = Yii::$app->params['listPerPage'];
        // query with some option
        $productCate = Product::find();
        if (isset($sortPriceStatus) && $sortPriceStatus != ''){
            $productCate->addOrderBy(['price' => @$sortPriceStatus]);
        }else if (isset($sortNameStatus) && $sortNameStatus != ''){
            $productCate->addOrderBy(['name' => @$sortNameStatus]);
        }
        // find with page size
        $countQuery = clone $productCate;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize'=>$pageSize]);
        $products = $productCate->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        return $this->render('all',
            array('products' => $products,
                'pages' => $pages,
                'searchBy' => $searchBy,
                'showBy' => $pageSize,
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

    public function actionAjaxSearch()
    {
        $app = Yii::$app;
        $req = $app->request;
        $resp = $app->response;

        // Set the response' content to be JSON
        $resp->format = Response::FORMAT_JSON;

        /*
         * Get input
         */
        $name = $req->get('name');
        $categoryId = (int) $req->get('category_id');
        $minPrice = (double) $req->get('min_price');
        $maxPrice = (double) $req->get('max_price');
        $isInStock = (bool) $req->get('is_in_stock');

        /*
         * Build query
         */
        $query = Product::find();
        $where = ['AND'];

        if (! empty($categoryId)) {
            $query->innerJoin('product_category', [
                'AND',
                'product.id = product_category.product_id',
                "product_category.category_id = :categoryId",
            ], [
                ':categoryId' => $categoryId,
            ]);
        }

        if (! empty($name)) {
            $where[] = ['LIKE', 'name', $name];
        }

        if (! empty($minPrice)) {
            $where[] = [
                'OR',
                ['>=', 'price', $minPrice],
                [
                    'AND',
                    ['>', 'sales_price', 0],
                    ['>=', 'sales_price', $minPrice]
                ],
            ];
        }

        if (! empty($maxPrice)) {
            $where[] = [
                'OR',
                ['<=', 'price', $maxPrice],
                [
                    'AND',
                    ['>', 'sales_price', 0],
                    ['<=', 'sales_price', $maxPrice]
                ],
            ];
        }

        if (! empty($isInStock)) {
            $where[] = ['>', 'quantity', 0];
        }

        $query->where($where);

        /*
         * Retrieve query result
         */
//        return [$query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql];
        $products = $query->all();

        return [
            'success' => true,
            'data'    => $products,
        ];
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
