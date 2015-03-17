<?php
namespace backend\repositories;

use backend\models\Category;

class CategoryRepository
{
    /**
     * Retrieve all categories from database
     *
     * @return array|Category[]
     */
    public function getAll()
    {
        return Category::find()->all();
    }

    /**
     * Retrieve all top-level categories
     *
     * @return array|Category[]
     */
    public function getTopCategories()
    {
        return Category::find()->where(['=', 'level', 1])->all();
    }
}
