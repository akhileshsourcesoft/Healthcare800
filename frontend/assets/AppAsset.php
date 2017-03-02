<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/prettyPhoto.css',
    ];
    public $js = [
		'js/jquery.maskedinput.js',
		'js/jquery.prettyPhoto.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = array(
		'position' => \yii\web\View::POS_HEAD
	);
}
