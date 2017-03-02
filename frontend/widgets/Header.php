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
class Header extends Widget
{
	
		public $logo; 
		public $menu; 
	
	 public function init(){

        parent::init();
		$configmodel=new Configuration();
		$this->logo = $configmodel->findOne(['config_key' => 'LOGO_HEADER'])->config_value;
		
	//	print_r($this->logo); die;
		$menumodel=new Menu();
		$this->menu = $menumodel->find()
						  ->asArray()
						  ->where(['menu_location' => 'header', 'status'=>'1',])
						  ->andWhere(['is', 'mu_parent_id', null])
						  ->orderBy(['sort_order'=>'DESC', 'mu_id'=>'ASC'])
						  ->all();
		
		

	    }

	     

	    public function run(){
			$str = '<!-- header starts -->
<header>
		
  <div class="header">
    <div class="container">
   
    
      <div class="row">
        <div class="col-sm-3 col-md-3 col-lg-3 logo no-left"> <a href="'.Url::home().'"><img src="'. Yii::$app->getUrlManager()->createUrl("uploads/".$this->logo).'" alt="" /></a></div>
        <div class="col-sm-9 col-md-9 col-lg-9 navigation-section">
          
          <nav class="navbar navbar-default">
            <div class="container-fluid  no-right"> 
              <!-- Brand and toggle get grouped for better mobile display -->
              <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
               </div>
              
              <!-- Collect the nav links, forms, and other content for toggling -->
              <div class="collapse navbar-collapse no-right" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">';
                foreach($this->menu as $k=>$item)
                {
					$str .= '<li><a href="'.$item["mu_url"].'" class="'.$item['class'].'">'.$item["mu_name"].'</a></li>'; 
					
				}
                
                
                
                 $str.=' 
                </ul>
              </div>
              <!-- /.navbar-collapse --> 
            </div>
            <!-- /.container-fluid --> 
          </nav>
        </div>
      </div>
    </div>
  </div>
</header>
<!-- header ends --> ';


	        return $str;

	    }
	    
	    }
