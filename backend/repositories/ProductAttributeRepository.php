<?php
namespace backend\repositories;

use backend\models\Product;

class ProductAttributeRepository
{
    public function setProductAttributes($product, array $attributes)
    {
        if (is_numeric($product)) {
            $product = Product::findOne($product);
        }

        foreach ($attributes as $attrId => $value)
        {
            $product->setAttrValue($attrId, $value);
        }

        // Return TRUE after everything is done
        return true;
    }
}
