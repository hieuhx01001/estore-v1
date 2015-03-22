<?php

namespace backend\controllers;

use backend\models\Attribute;
use backend\models\CategoryAttribute;
use backend\repositories\CategoryRepository;
use Yii;
use backend\models\Category;
use backend\models\CategorySearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $categoryAttributes = $model->categoryAttributes;

        return $this->render('view', [
            'model' => $model,
            'categoryAttributes' => $categoryAttributes,
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $req = Yii::$app->request;
        $model = new Category();

        if ('POST' == $req->method) {

            $categoryRepo = new CategoryRepository();

            // Collect attribute data and assign to the entity
            $model->load(Yii::$app->request->post());

            // Collect all selected attribute ids
            $attributeIds = $req->post('attribute_ids');

            $result = $categoryRepo->saveCategory($model, $attributeIds);

            if ($result) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else {
                die ('Failed to save Category and Category-Attribute links');
            }
        }
        else {
            $attributes = Attribute::find()->all();

            return $this->render('create', [
                'model'                 => $model,
                'attributes'            => $attributes,
                'selectedAttributeIds'  => null,
            ]);
        }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $req = Yii::$app->request;
        $model = $this->findModel($id);

        if ('POST' == $req->method) {

            $categoryRepo = new CategoryRepository();

            // Collect attribute data and assign to the entity
            $model->load(Yii::$app->request->post());

            // Collect all selected attribute ids
            $attributeIds = $req->post('attribute_ids');

            $result = $categoryRepo->saveCategory($model, $attributeIds);

            if ($result) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else {
                die ('Failed to save Category and Category-Attribute links');
            }
        }
        else {
            $attributes = Attribute::find()->all();

            $selectedAttributes = ArrayHelper::getColumn(
                $model->categoryAttributes,
                CategoryRepository::FIELD_ATTRIBUTE_ID
            );

            return $this->render('update', [
                'model'                 => $model,
                'attributes'            => $attributes,
                'selectedAttributeIds'  => $selectedAttributes,
            ]);
        }
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $category = $this->findModel($id);

        // If this category containing product, it cannot be removed
        if (! empty($category->productCategories)) {
            return $this->render('message', [
                'message' => 'This category cannot be deleted because it is currently containing products']
            );
        }

        // Process deletion
        $deletionResult = $category->delete();

        if ($deletionResult === false) {
            return $this->render('message', [
                'message' => 'Failed to delete the requested category']
            );
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
