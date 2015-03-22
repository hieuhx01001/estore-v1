<?php

namespace backend\controllers;

use backend\models\Category;
use backend\models\forms\ImageUploaderForm;
use backend\models\Image;
use backend\repositories\CategoryRepository;
use backend\repositories\ProductAttributeRepository;
use backend\repositories\ProductCategoryRepository;
use Yii;
use backend\models\Product;
use backend\models\ProductSearch;
use yii\db\Connection;
use yii\helpers\BaseFileHelper;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

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
        // Check if there's any category currently
        if (Category::find()->count() == 0) {
            // Show a message page if there's currently no category
            return $this->render(['message', 'message' => 'There\'s currently no category. Create one before creating a product.']);
        }

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
     * Show product image management page
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionImages($id)
    {
        $product = $this->findModel($id);
        $images = $product->images;

        return $this->render('images', [
            'product' => $product,
            'images'  => $images,
        ]);
    }

    public function actionAddImage()
    {
        $req = Yii::$app->request;
        $productId = $req->post('product_id');

        // If not a POST request, show 404
        if (! $req->isPost) {
            throw new NotFoundHttpException();
        }

        // Get uploaded image
        $imageFile = UploadedFile::getInstanceByName('image');

        if (empty($imageFile)) {
            return $this->render('message', ['message' => 'No image was uploaded']);
        }

        // Build image name
        $uploadedTime = time();
        $fileName = "{$imageFile->getBaseName()}-{$uploadedTime}.{$imageFile->getExtension()}";

        // Save image
        $savePath = Yii::$app->basePath . "/web/img/product/{$productId}/";
        if (! BaseFileHelper::createDirectory($savePath)
            or ! $imageFile->saveAs($savePath . $fileName)) {
            return $this->render('message', ['message' => 'Failed to save image']);
        }

        // Check if there's any main image for this product.
        // If there's currently no main image for this product,
        // Set $isMain to be TRUE (1), this image will be the main
        // one. Else, set to FALSE (0).
        $isMain = Image::findOne([
            'product_id' => $productId,
            'is_main'    => true,
        ]) == null ? 1 : 0;

        // Put a new Image record into database
        $image = new Image();
        $image->setAttributes([
            'product_id' => $productId,
            'name'       => $fileName,
            'is_main'    => $isMain,
        ]);
        if (! $image->save()) {
            return $this->render('message', ['message' => 'Image file saved, but failed to create Image record in database']);
        }

        // Redirect to product image management page after image was saved
        return $this->redirect(['images', 'id' => $productId]);
    }

    public function actionSetMainImage()
    {
        $req = Yii::$app->request;
        $productId = $req->get('product_id');
        $imageId = $req->get('image_id');

        $product = Product::findOne($productId);
        $image = Image::findOne([
            'id'         => $imageId,
            'product_id' => $productId,
        ]);

        if ($product === null or $image === null) {
            throw new NotFoundHttpException();
        }

        Yii::$app->db->transaction(function(Connection $conn) use ($image, $product) {

            // Get the currently active transaction
            $transaction = $conn->transaction;

            // Find the current main image and set is_main to false
            $mainImage = $product->mainImage;
            $mainImage->is_main = 0;
            if (! $mainImage->save()) {
                $transaction->rollBack();
                return $this->render('message', ['message' => 'Failed to unset current main image of this product']);
            }

            // Set the selected image as the main one
            $image->is_main = 1;
            if (! $image->save()) {
                $transaction->rollBack();
                return $this->render('message', ['message' => 'Failed to set the requested image to be main']);
            }
        });

        return $this->redirect(['images', 'id' => $productId]);
    }

    public function actionRemoveImage()
    {
        $req = Yii::$app->request;
        $imageId = $req->get('id');
        $image = Image::findOne($imageId);

        if (! $image) {
            throw new NotFoundHttpException();
        }

        $transaction = Yii::$app->db->transaction(function (Connection $conn) use ($image) {

            $transaction = $conn->transaction;

            // Remove Image record from database
            if ($image->delete() === false) {
                $transaction->rollBack();
                return $this->render('message', ['message' => 'Failed to remove Image record from database']);
            }

            // Remove image file from directory
            $fileDirectory = Yii::$app->basePath . "/web/img/product/{$image->product_id}/{$image->name}";
            if (unlink($fileDirectory) === false) {
                $transaction->rollBack();
                return $this->render('message', ['message' => 'Failed to remove image file from directory']);
            }
        });

        return $this->redirect(['images', 'id' => $image->product_id]);
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
