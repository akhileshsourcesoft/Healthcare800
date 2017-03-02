<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\Expression;
use yii\widgets\ActiveForm;
use common\models\Menu;

class Headermenu extends Widget
{
	
	 public function init(){
		 
        parent::init();
        
	 }

	public function run(){

		$menuList = Menu::find()->where(['status' => 1])->andWhere(['menu_location' => 'header'])->orderBy(['sort_order' => SORT_ASC])->all();
		$menudata = '<div id="cssmenu">
					<ul>';
					$counter = 1;
					foreach($menuList as $key=>$value){
						$active = '';
						if($counter==1){
							$active = 'class="active"';
						}
						if($value['menu_id']==4 && !isset(Yii::$app->session['usersid'])){
							$menudata .= '<li '.$active.'><a href="'.$value['menu_url'].'">'.$value['menu_name'].'</a></li>';
						}else if($value['menu_id']!=4){
							$menudata .= '<li '.$active.'><a href="'.$value['menu_url'].'">'.$value['menu_name'].'</a></li>';
						}
						
					$counter++; }
					$menudata .= '</ul>
			  </div>';
		return $menudata;
	}
	    
}
?>
