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
use common\models\User;
use common\models\ClinicBanner;
use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;


class Prettyphoto extends Widget{

	public $ptokenid;
		 
	public function init(){
	
        parent::init();
		$ptokenid = $this->ptokenid;
        
	}
	 
	 public function run(){
		$ptokenid = $this->ptokenid;
		$providerId = User::find()->where(['password_reset_token'=>$ptokenid])->andWhere(['status'=>1])->one();
		$bannerList = ClinicBanner::find()->where(['provider_id'=>$providerId->id])->andWhere(['status'=>1])->asArray()->all();

		 if(count($bannerList)>0){
			$bannergallery = '<div class="tabGallery" id="bannerGallery">
				<ul>';
				foreach($bannerList as $key=>$val){
					$bannerImg = explode('/',$val['images']); 
					if(file_exists('uploads/users/providers_banner/'.$bannerImg[2])){
					Image::thumbnail('@webroot/uploads/'.$val['images'].'', 100, 100)->save(Yii::getAlias('@runtime/thumbs/100X100-'.$bannerImg[2]), ['quality' => 100]);

					$bannergallery .= '<li><a href="'.Yii::$app->getUrlManager()->createUrl("uploads/".$val['images']."").'" rel="prettyPhoto[gallery2]"><img src="'.Yii::$app->getUrlManager()->getBaseUrl().'/frontend/runtime/thumbs/100X100-'.$bannerImg[2].'" class="img-responsive"></a></li>';
					}
				}
			$bannergallery .= '</ul>
		 </div>';
		  return $bannergallery;
		}
	}
}
?>
<script>
	$(document).ready(function(){
		$("#bannerGallery a[rel^='prettyPhoto']").prettyPhoto({
			animation_speed:'fast',
			slideshow:10000,
			social_tools:false,
			deeplinking: false,
		});
	});
</script>
