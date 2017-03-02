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
use common\models\Configuration;
use common\models\Menu;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * \Yii::$app->getSession()->setFlash('error', 'This is the message');
 * \Yii::$app->getSession()->setFlash('success', 'This is the message');
 * \Yii::$app->getSession()->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * \Yii::$app->getSession()->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Partner extends Widget
{
	
		public $config; 
		public $tagline; 
		public $partnerimage; 
		//public $menu; 
	
	 public function init(){

        parent::init();
		$configmodel=new Configuration();
		$this->config = $configmodel->find()
					->where(['like','config_key', 'PARTNER%', false])
					->orderBy(['id'=>'ASC'])
					->asArray()->all();
				
		$this->tagline = $this->config[0]['title']; 
		for($i=1; $i< count($this->config); $i++)
		{
		$this->partnerimage[] = $this->config[$i]['config_value']; 
		}
	//print_r($config); die;
		
		
		

	    }

	     

	    public function run(){
			$str = ' <!-- Partner section Start -->
    <section class="clearfix partner-sec top-prp">
     <div class="container">
     <h2 class="title-txt">'.$this->tagline.'</h2>
     <div class="row top-prp">
            <div>
                
                <div class="carousel slide" data-ride="carousel" id="quote-carousel">
               
                    <!-- Carousel Slides / Quotes -->
                    <div class="carousel-inner agent-slider">
                        <!-- Quote 1 -->
                        <div class="item active">
                          
                                <div class="row">';
                                foreach($this->partnerimage as $k=> $val)
                                {
									$str .='<div align="center" class="col-lg-3 col-md-3">
                                        <img  src="'.Yii::$app->getUrlManager()->createUrl("uploads/".$val).'" class="img-responsive" />
                                    </div>';
								}
                                
                                
                                   $str .='</div>
                       </div>
                       
                    
                     
                </div>
            </div>
        </div>
         
  </div>
  </div>
    </section>
  <!-- Partner section End -->';


	        return $str;

	    }
	    
	    }
