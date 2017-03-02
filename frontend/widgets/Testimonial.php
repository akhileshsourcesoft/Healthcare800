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
use common\models\Testimonials;
use yii\db\Expression;

class Testimonial extends Widget
{
	
	 public function init(){
		 
        parent::init();
        
	 }

	public function run(){
		
			$testimoniallist = Testimonials::find()
						->joinWith('user')->where(['hc_testimonials.status'=>'1'])
						->andWhere('hc_users.id=hc_testimonials.user_id')
						->orderBy(['RAND()' => SORT_DESC])->limit(3)->all();
		
					$testimonials = '<section class="testiMonials">
					  <div class="container">
						<div class="row">
						  <h2>testimonial</h2>
						  <h3>Lorem ipsum dolor sit amet</h3>
						  <div class="clientComments">';
						   foreach($testimoniallist as $val):
					
						   if(file_exists('uploads/'.$val['profile_image'].'') && (!empty($val['profile_image']))){
								$profileImage = Yii::$app->getUrlManager()->createUrl('uploads/'.$val['profile_image'].'');
						   }else{
							   $profileImage = Yii::$app->getUrlManager()->createUrl('uploads/testimonials/default.png');
						   }
						   if(isset($val['testimonial']) && (!empty($val['testimonial']))){
								$description = (strlen($val['testimonial'])>125)?substr($val['testimonial'],0,100).'...':$val['testimonial'];
							}
								$testimonials .= '<div class="col-md-4 col-sm-4">
								  <figure><img src="'.$profileImage.'" class="img-responsive img-circle"></figure>
								  <p>'.@$description.'</p>
								  <div class="clientName">'.$val['title'].'</div>
								  <div class="compName">'.$val['user']['fname'].' '.$val['user']['lname'].'</div>
							</div>';
							endforeach;
						  $testimonials .='</div>
						</div>
					  </div>
					</section>';
			return $testimonials;
	
	}
	    
}
