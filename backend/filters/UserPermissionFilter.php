<?php
namespace backend\filters;

use backend\models\User;
use yii\base\ActionFilter;

class UserPermissionFilter extends ActionFilter
{
    /**
     * The config file name
     */
    const FILTER_CONFIG_FILE = 'filter-config.json';

    /**
     * @var User the currently logged-in user
     */
    protected $user;

    /**
     * Filter's initiation
     */
    public function init()
    {
        $this->user = \Yii::$app->user;
    }

    /**
     * This before-filter check if the current user
     * can access the requested action by reading
     * the filtering config file and comparing
     * with the user's permissions in database.
     *
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        // Requested controller
        $controller = $action->controller->className();
        // Requested action method
        $method = $action->actionMethod;

        /*
         * Load the filtering config JSON file, and decode it.
         * The config file will be in the same directory
         * with this class file.
         */
        $filterConfig = json_decode(
            file_get_contents(__DIR__ . '/' . static::FILTER_CONFIG_FILE));

        /*
         * Check if the current controller and action method
         * are in the config file. If NOT, the user PASSED.
         */
        if (isset($filterConfig->{$controller}) and
            isset($filterConfig->{$controller}->{$method})) {

            $requiredPermissions = $filterConfig->{$controller}->{$method};

            /*
             * Iterate through the require permissions, and check
             * if the user has all those permissions.
             */
            foreach ($requiredPermissions as $permission) {
                // Stop if there's at least 1 missing permission
                if (! $this->canUserDo($permission)) {
                    die('You do not have the required permission');
                }
            }
        }

        // PASSED!
        return parent::beforeAction($action);
    }

    /**
     * Check if the current user has the given permission
     *
     * @param $permissions
     * @return boolean
     */
    protected function canUserDo($permissions)
    {
        return $this->user->can($permissions);
    }
}
