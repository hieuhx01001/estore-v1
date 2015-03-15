<?php

use yii\base\InvalidConfigException;
use yii\db\Schema;
use yii\rbac\DbManager;

class m140708_201431_rbac_init extends \yii\db\Migration
{

    public function up()
    {
        $this->crateAuth();
    }

    public function down()
    {
        $authManager = Yii::$app->getAuthManager();

        $createPost = $authManager->getPermission('createPost');
        $readPost = $authManager->getPermission('readPost');
        $updatePost = $authManager->getPermission('updatePost');

        $reader = $authManager->getRole('reader');
        $author = $authManager->getRole('author');

        $authManager->revoke($reader, 1);
        $authManager->revoke($author, 1);

        $authManager->remove($reader);
        $authManager->remove($author);

        $authManager->remove($createPost);
        $authManager->remove($readPost);
        $authManager->remove($updatePost);
    }

    private function crateAuth() {
        $auth = Yii::$app->getAuthManager();

//        // add "createPost" permission
//        $createPost = $auth->createPermission('createPost');
//        $createPost->description = 'create a post';
//        $auth->add($createPost);
//
//        // add "readPost" permission
//        $readPost = $auth->createPermission('readPost');
//        $readPost->description = 'read a post';
//        $auth->add($readPost);
//
//        // add "updatePost" permission
//        $updatePost = $auth->createPermission('updatePost');
//        $updatePost->description = 'update post';
//        $auth->add($updatePost);
//
//        // add "reader" role and give this role the "readPost" permission
//        $reader = $auth->createRole('reader');
//        $auth->add($reader);
//        $auth->addChild($reader, $readPost);
//
//        // add "author" role and give this role the "createPost" permission
//        // as well as the permissions of the "reader" role
//        $author = $auth->createRole('author');
//        $auth->add($author);
//        $auth->addChild($author, $createPost);
//        $auth->addChild($author, $reader);
//
//        // usually implemented in your User model.
//        $auth->assign($reader, 1);
//        $auth->assign($author, 1);

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
        $createUser->description = 'create a product';
        $auth->add($createProduct);

        // add "updateProduct" permission
        $updateProduct = $auth->createPermission('updateProduct');
        $updateUser->description = 'update product';
        $auth->add($updateProduct);

        // add "deleteProduct" permission
        $deleteProduct = $auth->createPermission('deleteProduct');
        $deleteUser->description = 'delete a product';
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
