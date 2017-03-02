<?php
use frontend\widgets\Testimonial;
use common\models\Configuration;
$configMiddleHeading = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'HOMEMIDDLEHEADING'])->one();
$configMiddle1List = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'HOMEMIDDLE1'])->one();
$configMiddle2List = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'HOMEMIDDLE2'])->one();
$configMiddle3List = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'HOMEMIDDLE3'])->one();
$configMiddle4List = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'HOMEMIDDLE4'])->one();
$configWhyChooseusHeading = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'WHY-CHOOSE-US-HEADING'])->one();
$configWhyChooseus1 = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'WHY-CHOOSE-US1'])->one();
$configWhyChooseus2 = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'WHY-CHOOSE-US2'])->one();
$configWhyChooseus3 = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'WHY-CHOOSE-US3'])->one();
$configWhyChooseus4 = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'WHY-CHOOSE-US4'])->one();
$configWhyChooseusBanner = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'WHY-CHOOSE-US-BANNER'])->one();
$configDoctoronCall = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'DOCTOR_ON_CALL'])->one();
$configDoctorHeading = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'DOCTOR_ON_CALL_HEADING'])->one();
?>
<!-- featured Panels -->
<div class="container">
  <div class="row">
    <div class="featuredHeadings">
      <h2>Services</h2>
      <div class="hPanels">
        <div class="col-md-3 col-sm-3">
          <figure><a href="#"><img src="<?php echo Yii::$app->getUrlManager()->createUrl('uploads/'.$configMiddle1List->config_value);?>"></a></figure>
          <h4><a href="#"><?php echo $configMiddle1List->title;?></a></h4>
		  <?php echo $configMiddle1List->description;?>
        </div>
        <div class="col-md-3 col-sm-3">
          <figure><a href="#"><img src="<?php echo Yii::$app->getUrlManager()->createUrl('uploads/'.$configMiddle2List->config_value);?>"></a></figure>
          <h4><a href="#"><?php echo $configMiddle2List->title;?></a></h4>
          <?php echo $configMiddle2List->description;?>
        </div>
        <div class="col-md-3 col-sm-3">
          <figure><a href="#"><img src="<?php echo Yii::$app->getUrlManager()->createUrl('uploads/'.$configMiddle3List->config_value);?>"></a></figure>
          <h4><a href="#"><?php echo $configMiddle3List->title;?></a></h4>
          <?php echo $configMiddle3List->description;?>
        </div>
        <div class="col-md-3 col-sm-3">
          <figure><a href="#"><img src="<?php echo Yii::$app->getUrlManager()->createUrl('uploads/'.$configMiddle4List->config_value);?>"></a></figure>
          <h4><a href="#"><?php echo $configMiddle4List->title;?></a></h4>
          <?php echo $configMiddle4List->description;?>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- featured Panels --> 
<!-- Why Us -->
<section class="whyUs">
  <div class="container">
    <div class="row">
      <aside class="col-md-6 col-sm-6">
		<?php
		if(isset($configWhyChooseusList->description) && (!empty($configWhyChooseusList->description))){
			echo $configWhyChooseusList->description;
		}
		?>
        <div class="leftPoints">
          <h2><?php echo $configWhyChooseusHeading->title;?></h2>
          <ul>
            <li><?php echo $configWhyChooseus1->description;?></li>
            <li><?php echo $configWhyChooseus2->description;?></li>
            <li><?php echo $configWhyChooseus3->description;?></li>
            <li><?php echo $configWhyChooseus4->description;?></li>
          </ul>
        </div>
      </aside>
      <aside class="col-md-6 col-sm-6">
        <div class="whyImg"> 
		<?php 
			$configWhyChooseusBanners = array();
			if(isset($configWhyChooseusBanner->config_value) && (!empty($configWhyChooseusBanner->config_value))){
				$configWhyChooseusBanners = explode("/", $configWhyChooseusBanner->config_value);
				if(file_exists('uploads/'.$configWhyChooseusBanner->config_value) && (!empty($configWhyChooseusBanners[1]))){
					echo '<img src="'.Yii::$app->getUrlManager()->createUrl('uploads/configuration/'.$configWhyChooseusBanners[1]).'" class="img-responsive">';
				}else{
					echo '<img src="'.Yii::$app->getUrlManager()->createUrl('images/why-us-image.png').'" class="img-responsive">';
				}
			} ?>	
			</div>
      </aside>
    </div>
  </div>
</section>
<!-- Why Us --> 
<!-- Call Doctor -->
<section class="callDoctor">
  <div class="container">
    <div class="row">
      <aside class="col-md-6 col-sm-6">
        <ul class="leftIcons">
          <li><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/certificate-icon1.jpg');?>" class="img-responsive"></li>
          <li><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/certificate-icon2.jpg');?>" class="img-responsive"></li>
          <li><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/certificate-icon3.jpg');?>" class="img-responsive"></li>
          <li><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/certificate-icon4.jpg');?>" class="img-responsive"></li>
          <li><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/certificate-icon5.jpg');?>" class="img-responsive"></li>
          <li><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/certificate-icon6.jpg');?>" class="img-responsive"></li>
        </ul>
      </aside>
      <aside class="col-md-6 col-sm-6">
        <h2><?php echo strip_tags($configDoctorHeading->description);?></h2>
			<?php echo $configDoctoronCall->description;?>
        <button type="button">Email Provider</button>
      </aside>
    </div>
  </div>
</section>
<!-- Call Doctor --> 
<!-- Popularity -->
<section class="ourPopularity">
  <div class="container-fluid">
    <div class="row">
      <aside class="col-md-4 popLeft">
        <div class="leftInside">
          <h3>Know about our</h3>
          <h2>popularity</h2>
        </div>
      </aside>
      <aside class="col-md-8 popRight">
        <div class="rightInside">
          <ul>
            <li>
              <figure><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/smiley.png');?>"></figure>
              <h3>200+</h3>
              <h4>Happy Client</h4>
            </li>
            <li>
              <figure><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/award.png');?>"></figure>
              <h3>18+</h3>
              <h4>Award Win</h4>
            </li>
            <li>
              <figure><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/follower.png');?>"></figure>
              <h3>24x7</h3>
              <h4>Available</h4>
            </li>
          </ul>
        </div>
      </aside>
    </div>
  </div>
</section>
<!-- Popularity --> 
<!-- testiMonials -->
<?php
	echo Testimonial::widget();
?>
<!-- testiMonials --> 
