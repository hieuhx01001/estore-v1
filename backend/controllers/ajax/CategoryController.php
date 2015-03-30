<?php
namespace backend\controllers\ajax;

use backend\models\Attribute;
use backend\models\Category;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;

class CategoryController extends Controller
{
    /** @var Request */
    protected $request = null;

    /** @var Response */
    protected $response = null;

    /**
     * Controller's initialization
     */
    public function init()
    {
        parent::init();

        $this->request = \Yii::$app->request;
        $this->response = \Yii::$app->response;

        // Set default response of this controller to be "application/json"
        $this->response->format = Response::FORMAT_JSON;
    }

    /**
     * Retrieve the allowed attributes for the
     * descendant of the requested parent category.
     *
     * @return array
     */
    public function actionGetAllowedAttributes()
    {
        $req = $this->request;
        $notAllowedAttrs = [];
        $allowedAttrs = null;

        // Get Parent-Category-ID from request params
        $parentId = $req->get('parent_id');

        if (empty($parentId) != true) {
            // Retrieve the requested parent category from DB
            $parent = Category::findOne($parentId);

            // Return a failure message if the requested
            // category was not found
            if (is_null($parent)) {
                return [
                    'success' => false,
                    'message' => "Category not found",
                ];
            }

            foreach ($parent->attrs as $attr) {
                array_push($notAllowedAttrs, $attr->id);
            }

            foreach ($parent->allAncestors as $ancestor) {
                foreach ($ancestor->attrs as $attr) {
                    array_push($notAllowedAttrs, $attr->id);
                }
            }
        }

        $allowedAttrs = Attribute::find()
            ->where(['NOT IN', 'id', $notAllowedAttrs])
            ->all();

        return [
            'success' => true,
            'attributes' => $allowedAttrs,
        ];
    }
}
