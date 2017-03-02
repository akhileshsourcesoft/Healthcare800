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
//use common\models\Configuration;
//use common\models\Menu;

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
class Agents extends Widget
{
	
	
	
	 public function init(){

        parent::init();
	 }

	     

	    public function run(){
			$str = '     <!-- Agent section Start -->
  <section class="clearfix max-pad">
    <div class="container top-prp">
     <h2 class="title-txt">Our Top Agents</h2>
  <div class="row top-prp">
            <div>
                
                <div class="carousel slide carousel-fade" data-ride="carousel" id="quote-carousel">
               
                    <!-- Carousel Slides / Quotes -->
                    <div class="carousel-inner agent-slider">
                        <!-- Quote 1 -->
                        <div class="item active container">
                          
                                <div class="row">
                                    <div class="col-lg-2 col-md-2">
                                        <img  src="images/agent1.jpg" class="img-responsive" />
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                     <h4>Ron Abta , San Francisco, CA</h4>
                                     <p>
                                       Ron ranks in the top 2% of agents in San Francisco
in production, and he sells his listings 30 days faster
than the average agent. 
                                     </p>
                                     <a href="#;">More...</a>
                                     <div align="left" class="social-icn">
                                     <a href="#;" class="hvr-pop">
                                      <img src="images/twitr-icn.jpg" class="hvr-pop" />
                                     </a> 
                                     &nbsp;
                                     <a href="#;" class="hvr-pop">
                                     <img src="images/google-icn.jpg" class="hvr-pop" />
                                     </a>
                                      &nbsp;
                                     <a href="#;" class="hvr-pop"> 
                                     <img src="images/linkdn-icn.jpg" class="hvr-pop" />
                                     </a>
                                      &nbsp;
                                      <a href="#;" class="hvr-pop">
                                     <img src="images/pintrest-icn.jpg" class="hvr-pop" />
                                     </a>
                                     </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2">
                                        <img  src="images/agent2.jpg" class="img-responsive" />
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                     <h4>Ron Abta , San Francisco, CA</h4>
                                     <p>
                                       Ron ranks in the top 2% of agents in San Francisco
in production, and he sells his listings 30 days faster
than the average agent. 
                                     </p>
                                     <a href="#;">More...</a>
                                     <div align="left" class="social-icn">
                                     <a href="#;" class="hvr-pop">
                                      <img src="images/twitr-icn.jpg" class="hvr-pop" />
                                     </a> 
                                     &nbsp;
                                     <a href="#;" class="hvr-pop">
                                     <img src="images/google-icn.jpg" class="hvr-pop" />
                                     </a>
                                      &nbsp;
                                     <a href="#;" class="hvr-pop"> 
                                     <img src="images/linkdn-icn.jpg" class="hvr-pop" />
                                     </a>
                                      &nbsp;
                                      <a href="#;" class="hvr-pop">
                                     <img src="images/pintrest-icn.jpg" class="hvr-pop" />
                                     </a>
                                     </div>
                                    </div>
                     
                        </div>
                       </div>
                      <!-- Quote 2 -->
                      <div class="item container">
                          
                                <div class="row">
                                    <div class="col-lg-2 col-md-2">
                                        <img  src="images/agent3.jpg" class="img-responsive" />
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                     <h4>Ron Abta , San Francisco, CA</h4>
                                     <p>
                                       Ron ranks in the top 2% of agents in San Francisco
in production, and he sells his listings 30 days faster
than the average agent. 
                                     </p>
                                     <a href="#;">More...</a>
                                     <div align="left" class="social-icn">
                                     <a href="#;" class="hvr-pop">
                                      <img src="images/twitr-icn.jpg" />
                                     </a> 
                                     &nbsp;
                                     <a href="#;" class="hvr-pop">
                                     <img src="images/google-icn.jpg" />
                                     </a>
                                      &nbsp;
                                     <a href="#;" class="hvr-pop"> 
                                     <img src="images/linkdn-icn.jpg" />
                                     </a>
                                      &nbsp;
                                      <a href="#;" class="hvr-pop">
                                     <img src="images/pintrest-icn.jpg" />
                                     </a>
                                     </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2">
                                        <img  src="images/agent4.jpg" class="img-responsive" />
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                     <h4>Ron Abta , San Francisco, CA</h4>
                                     <p>
                                       Ron ranks in the top 2% of agents in San Francisco
in production, and he sells his listings 30 days faster
than the average agent. 
                                     </p>
                                     <a href="#;">More...</a>
                                     <div align="left" class="social-icn">
                                     <a href="#;" class="hvr-pop">
                                      <img src="images/twitr-icn.jpg" />
                                     </a> 
                                     &nbsp;
                                     <a href="#;" class="hvr-pop">
                                     <img src="images/google-icn.jpg" />
                                     </a>
                                      &nbsp;
                                     <a href="#;" class="hvr-pop"> 
                                     <img src="images/linkdn-icn.jpg" />
                                     </a>
                                      &nbsp;
                                      <a href="#;" class="hvr-pop">
                                     <img src="images/pintrest-icn.jpg" />
                                     </a>
                                     </div>
                                    </div>
                     
                        </div>
                       </div>
                </div>
            </div>
        </div>
         
  </div>
  </div>
  </section>
  <!-- Agent section End -->';


	        return $str;

	    }
	    
	    }
