<?php

use yii\base\InvalidConfigException;
use yii\db\Schema;
use yii\rbac\DbManager;

class m140608_201406_rbac_init extends \yii\db\Migration
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }
        return $authManager;
    }

    public function up()
    {
        $authManager = $this->getAuthManager();

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($authManager->ruleTable, [
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'data' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (name)',
        ], $tableOptions);

        $this->createTable($authManager->itemTable, [
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'type' => Schema::TYPE_INTEGER . ' NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'rule_name' => Schema::TYPE_STRING . '(64)',
            'data' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (name)',
            'FOREIGN KEY (rule_name) REFERENCES ' . $authManager->ruleTable . ' (name) ON DELETE SET NULL ON UPDATE CASCADE',
        ], $tableOptions);
        $this->createIndex('idx-auth_item-type', $authManager->itemTable, 'type');

        $this->createTable($authManager->itemChildTable, [
            'parent' => Schema::TYPE_STRING . '(64) NOT NULL',
            'child' => Schema::TYPE_STRING . '(64) NOT NULL',
            'PRIMARY KEY (parent, child)',
            'FOREIGN KEY (parent) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (child) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        $this->createTable($authManager->assignmentTable, [
            'item_name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'user_id' => Schema::TYPE_STRING . '(64) NOT NULL',
            'created_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (item_name, user_id)',
            'FOREIGN KEY (item_name) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        $this->crateDefAuth();
    }

    public function down()
    {
        $authManager = $this->getAuthManager();

        $this->dropTable($authManager->assignmentTable);
        $this->dropTable($authManager->itemChildTable);
        $this->dropTable($authManager->itemTable);
        $this->dropTable($authManager->ruleTable);
    }

    private function crateDefAuth() {
        $auth = Yii::$app->getAuthManager();

        // add "createUser" permission
        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'create an user';
        $auth->add($createUser);

        // add "updateUser" permission
        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'update an user';
        $auth->add($updateUser);

        // add "deleteUser" permission
        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'delete an user';
        $auth->add($deleteUser);

        // add "createProduct" permission
        $createProduct = $auth->createPermission('createProduct');
        $createProduct->description = 'create a product';
        $auth->add($createProduct);

        // add "updateProduct" permission
        $updateProduct = $auth->createPermission('updateProduct');
        $updateProduct->description = 'update product';
        $auth->add($updateProduct);

        // add "deleteProduct" permission
        $deleteProduct = $auth->createPermission('deleteProduct');
        $deleteProduct->description = 'delete a product';
        $auth->add($deleteProduct);

        // create role editor and give permission to work on product
        $editor = $auth->createRole('editor');
        $auth->add($editor);
        $auth->addChild($editor, $createProduct);
        $auth->addChild($editor, $updateProduct);
        $auth->addChild($editor, $deleteProduct);

        // create role admin and give all permission
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $createUser);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $deleteUser);
        // grand all permission of editor to admin include: create, update, delete product
        $auth->addChild($admin, $editor);

        // implement in user model
        $auth->assign($admin,1);
        $auth->assign($editor,1);
    }
}
