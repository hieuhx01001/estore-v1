<?php

namespace backend\controllers;

use backend\models\Category;
use backend\repositories\CategoryRepository;
use backend\repositories\ProductAttributeRepository;
use backend\repositories\ProductCategoryRepository;
use Yii;
use backend\models\Product;
use backend\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $product = new Product();
        $requestMethod = Yii::$app->request->method;

        if ('POST' == $requestMethod) {
            $pcRepository = new ProductCategoryRepository();

            $product->load(Yii::$app->request->post());
            $categoryId = Yii::$app->request->post('category_id');

            $result = $pcRepository->createProduct($product, $categoryId);

            if ($result) {
                return $this->redirect(['view', 'id' => $product->id]);
            }
            else {
                die('Failed to create product by ProductCategoryRepository');
            }
        }
        else {
            return $this->render('create', [
                'model' => $product,
                'categories' => $this->getCategories(),
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $product = $this->findModel($id);
        $requestMethod = Yii::$app->request->method;

        if ('POST' == $requestMethod) {
            $pcRepository = new ProductCategoryRepository();

            $product->load(Yii::$app->request->post());
            $categoryId = Yii::$app->request->post('category_id');

            $result = $pcRepository->updateProduct($product, $categoryId);

            if ($result) {
                return $this->redirect(['view', 'id' => $product->id]);
            }
            else {
                die('Failed to update product by ProductCategoryRepository');
            }
        }
        else {
            return $this->render('update', [
                'model' => $product,
                'categories' => $this->getCategories()
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Set attributes for a product
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAttributes($id)
    {
        $req = Yii::$app->request;

        $product = $this->findModel($id);

        if ('POST' == $req->method) {
            $attributes = $req->post('attributes');

            $result = (new ProductAttributeRepository())->setProductAttributes($product, $attributes);

            if ($result) {
                return $this->redirect(['view', 'id' => $product->id]);
            }
            else {
                die('Failed to set attributes\' value for this Product');
            }
        }
        else {
            // These attributes are those linked with this product's category.
            // A product will have attributes linked to its category.
            $category = $product->mainCategory;
            $attributes = $category->fullAttributes;

            return $this->render('attributes', [
                'product' => $product,
                'attributes' => $attributes,
            ]);
        }
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getCategories()
    {
        $categories = (new CategoryRepository())->getAll();
        return $categories;
    }
}
