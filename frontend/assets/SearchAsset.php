<?php
namespace frontend\assets;

class SearchAsset extends ProductAsset
{
    public function init()
    {
        parent::init();

        $js = & $this->js;

        $js[] = 'estore/js/search/search.js';
    }
}
