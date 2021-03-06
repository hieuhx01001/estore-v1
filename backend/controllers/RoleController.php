<?php
/**
 * Created by JetBrains PhpStorm.
 * User:
 * Date:
 * Time:
 * To change this template use File | Settings | File Templates.
 */
namespace backend\controllers;

use backend\models\AuthAssignment;
use backend\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\HttpException;

use backend\models\Auth;
use backend\models\AuthSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\filters\UserPermissionFilter;

class RoleController extends Controller
{
    public function behaviors()
    {
        return [
            UserPermissionFilter::className(),
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new AuthSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get(), Auth::TYPE_ROLE);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new Auth();
        if ($model->load(Yii::$app->request->post())) {
            $permissions = $this->preparePermissions(Yii::$app->request->post());
            if($model->createRole($permissions)) {
                Yii::$app->session->setFlash('success', " '$model->name' " . Yii::t('app', 'successfully saved'));
                return $this->redirect(['view', 'name' => $model->name]);
            }
            else
            {
                $permissions = $this->getPermissions();
                $model->_permissions = Yii::$app->request->post()['Auth']['_permissions'];
                return $this->render('create', [
                        'model' => $model,
                        'permissions' => $permissions
                    ]
                );
            }
        } else {
            $permissions = $this->getPermissions();
            return $this->render('create', [
                    'model' => $model,
                    'permissions' => $permissions
                ]
            );
        }
    }

    /**
     * @param $name
     * @return string|\yii\web\Response
     */
    public function actionUpdate($name)
    {
        if($name == 'admin') {
            Yii::$app->session->setFlash('success', Yii::t('app', 'The Administrator has all permissions'));
            return $this->redirect(['view', 'name' => $name]);
        }
        $model = $this->findModel($name);
        if ($model->load(Yii::$app->request->post())) {
            $permissions = $this->preparePermissions(Yii::$app->request->post());
            if($model->updateRole($name, $permissions)) {
                Yii::$app->session->setFlash('success', " '$model->name' " . Yii::t('app', 'successfully updated'));
                return $this->redirect(['view', 'name' => $name]);
            }
        } else {
            $permissions = $this->getPermissions();
            $model->loadRolePermissions($name);
            return $this->render('update', [
                    'model' => $model,
                    'permissions' => $permissions,
                ]
            );
        }
    }

    /**
     * @param $name
     * @return \yii\web\Response
     * @throws \yii\web\HttpException
     */
    public function actionDelete($name)
    {
        if(Yii::$app->user->can('deleteRole')){
            if ($name) {
                if(!(intval(Auth::hasUsersByRole($name)) > 0) ) {
                    $auth = Yii::$app->getAuthManager();
                    $role = $auth->getRole($name);

                    // clear asset permissions
                    $permissions = $auth->getPermissionsByRole($name);

                    foreach($permissions as $permission) {
                        $auth->removeChild($role, $permission);
                    }
                    if($auth->remove($role)) {

                        Yii::$app->session->setFlash('success', " '$name' " . Yii::t('app', 'successfully removed'));
                    }
                } else {
                    $authAssignment = AuthAssignment::findOne(['item_name' => $name ]);
                    Yii::$app->session->setFlash('warning', 'Role '." '$name' " . Yii::t('app', 'still used by user '). User::findOne(['id' => $authAssignment->user_id ])->username );
                }
            }
            return $this->redirect(['index']);
        }else{
            throw new HttpException(500, 'No Auth');
        }


    }

    public function actionView($name)
    {
        $model = $this->findModel($name);
        $model->loadRolePermissions($name);
        $permissions = $this->getPermissions();
        return $this->render('view', [
            'model' => $model,
            'permissions' => $permissions,
        ]);
    }

    protected function findModel($name)
    {
        if ($name) {
            $auth = Yii::$app->getAuthManager();
            $model = new Auth();
            $role = $auth->getRole($name);
            if ($role) {
                $model->name = $role->name;
                $model->description = $role->description;
                $model->setIsNewRecord(false);
                return $model;
            }
        }
        throw new HttpException(404);
    }

    protected function getPermissions() {
        $models = Auth::find()->where(['type' => Auth::TYPE_PERMISSION])->all();
        $permissions = [];
        foreach($models as $model) {
            $permissions[$model->name] = $model->name . ' (' . $model->description . ')';
        }
        return $permissions;
    }

    protected function preparePermissions($post) {
        return (isset($post['Auth']['_permissions']) &&
            is_array($post['Auth']['_permissions'])) ? $post['Auth']['_permissions'] : [];
    }
}
