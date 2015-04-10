<?php
namespace frontend\assets;

class SearchAsset extends ProductAsset
{
    public function init()
    {
        parent::init();

        $css = & $this->css;
        $js = & $this->js;

        $css[] = 'estore/styles/search.css';

        $js[] = 'estore/js/search/search.js';
    }
}
