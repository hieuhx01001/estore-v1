<?php
namespace frontend\assets;

class DetailsAsset extends ProductAsset
{
    public function init()
    {
        parent::init();

        $js = & $this->js;

        $js[] = 'estore/js/details/details.js';
    }
}