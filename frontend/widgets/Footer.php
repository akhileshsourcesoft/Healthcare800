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
class Footer extends Widget
{
	
		public $logo; 
		public $menu; 
		public $fb_url, $twitter_url, $linkedin_url, $copyright; 
	
	 public function init(){

        parent::init();
		$configmodel=new Configuration();
		$data = $configmodel->find()->where(['config_key' => ['LOGO_FOOTER','FACEBOOK_URL','TWITTER_URL', 'LINKEDIN_URL','COPYRIGHT_FOOTER']])->all();
		//print_r($data); die;
		foreach($data as $k=>$item)
		{
		if($item->config_key == 'LOGO_FOOTER')
		{
			//print_r($item); die;
			$this->logo = $item->config_value;
		}
		if($item->config_key == 'FACEBOOK_URL')
		{
			//print_r($item); die;
			$this->fb_url = $item->title;
		}
		if($item->config_key == 'TWITTER_URL')
		{
			//print_r($item); die;
			$this->twitter_url = $item->title;
		}
		if($item->config_key == 'LINKEDIN_URL')
		{
			//print_r($item); die;
			$this->linkedin_url = $item->title;
		}
		if($item->config_key == 'COPYRIGHT_FOOTER')
		{
			//print_r($item); die;
			$this->copyright = $item->title;
		}
		
			
			}

		$menumodel=new Menu();
		$this->menu = $menumodel->find()
						  ->asArray()
						  ->where(['menu_location' => 'footer', 'status'=>'1',])
						  ->andWhere(['is', 'mu_parent_id', null])
						  ->orderBy(['sort_order'=>'DESC', 'mu_id'=>'ASC'])
						  ->all();
		
		

	    }

	     

	    public function run(){
			$str = '<footer>
  <div class="footer">
    <div class="container">
      <div class="row">
        <div class="col-sm-3 col-md-3 col-lg-3 footer-logo"><a href="'.Url::home().'"><img src="'.Yii::$app->getUrlManager()->createUrl("uploads/".$this->logo).'" alt="" /></a></div>
        <div class="col-sm-9 col-md-9 col-lg-9 footer-right">
          <div class="footer-social-media Padd-nt">
            <ul>
              <li><a href="'.$this->fb_url.'" class="hvr-pop"><img src="images/facebook-big-icon.png" alt="" /></a></li>
              <li><a href="'.$this->twitter_url.'" class="hvr-pop"><img src="images/twitter-big-icon.png" alt="" /></a></li>
              <li><a href="'.$this->linkedin_url.'" class="hvr-pop"><img src="images/linkedin-big-icon.png" alt="" /></a></li>
            </ul>
          </div>
          <div class="footer-navigation">
            <ul>';
                foreach($this->menu as $k=>$item)
                {
					$str .= '<li><a href="'.$item["mu_url"].'" class="'.$item['class'].'">'.$item["mu_name"].'</a></li>'; 
					
				}
              $str .='</ul>
          </div>
          <div class="footer-copyright">
            <p>'.$this->copyright.'</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>';


	        return $str;

	    }
	    
	    }
