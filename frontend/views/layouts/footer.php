<?php
use common\models\Configuration;
use common\models\User;
$configEmailaddress = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'EMAIL_ADDRESS'])->one();
$configCopyright = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'COPYRIGHT'])->one();
$configPhone = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'PHONENO'])->one();
$configFacebook = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'FOOTER_FACEBOOK'])->one();
$configTwitter = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'FOOTER_TWITTER'])->one();
$configGoogleplush = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'FOOTER_GOOGLEPLUS'])->one();
$configInstragram = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'FOOTER_INSTRAGRAM'])->one();
$configFooterabout = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'FOOTER_ABOUT'])->one();
$configGooglemaps = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'FOOTER_GOOGLEMAP'])->one();

//$completeAddress = strip_tags(trim($configGooglemaps->description));
/*$completeAddress = "A 21, Sector 67, Noida, Uttar Pradesh, 201301";
$data_array = User::googleMaps($completeAddress); 
if($data_array){
	$latitude = $data_array[0];
	$longitude = $data_array[1];
	$formatted_address = $data_array[2];  
}*/
?>
<!-- Footer -->
<footer> 
  <!-- NewsLetter -->
  <div class="newsLetter">
    <div class="container">
      <div class="row">
		<div class="col-md-2">
        <label>Newsletter</label>
        </div>
        <div class="col-md-10">
			<div class="clearfix">
			<input name="subscriberEmailid" type="text" id="subscriberEmailid" placeholder="Enter you email address">
			<input name="subscribeBtn" id="subscribeBtn" type="submit" value="subscribe" onclick="newsLetter();">
			</div>
			<div class="clearfix"><span id="subscriberEmailmsg"></span></div>
        </div>
      </div>
      <div class="row">
		  
	  </div>
    </div>
  </div>
  <!-- Footer Main -->
  <div class="footerMain">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-6">
		  <?php if(!empty($configFooterabout->title)){ ?>
			<h3><?php echo $configFooterabout->title;?></h3>
          <?php } ?>
          <?php if(!empty($configFooterabout->description)){ 
			echo $configFooterabout->description;
           } ?>
          <ul class="footerIcons">
			<?php if(!empty($configFacebook)){ ?>
				<li><a href="<?php echo strip_tags($configFacebook->description);?>" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
            <?php } ?>
            <?php if(!empty($configTwitter)){ ?>
				<li><a href="<?php echo strip_tags($configTwitter->description);?>" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
            <?php } ?>
            <?php if(!empty($configGoogleplush)){ ?>
				<li><a href="<?php echo strip_tags($configGoogleplush->description);?>" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
             <?php } ?>
             <?php if(!empty($configInstragram)){ ?>
				<li><a href="<?php echo strip_tags($configInstragram->description);?>" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
            <?php } ?>
          </ul>
        </div>
        <!--<div class="col-md-3 col-sm-6">
          <h3>recent posts</h3>
          <ul class="recentPosts">
            <li> <a href="#">
              <div class="postImg"> <img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/flicker-image1.jpg');?>" class="img-responsive">
                <div class="dateBox">10 Sep</div>
              </div>
              <div class="postTitle">
                <h4>Lorem ipsum dolor </h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit,</p>
              </div>
              </a> </li>
            <li> <a href="#">
              <div class="postImg"> <img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/flicker-image1.jpg');?>" class="img-responsive">
                <div class="dateBox">10 Sep</div>
              </div>
              <div class="postTitle">
                <h4>Lorem ipsum dolor </h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit,</p>
              </div>
              </a> </li>
          </ul>
        </div>-->
        <div class="col-md-6 col-sm-6">
          <h3>Our Locations</h3>
			<div id="googlemap_canvas"></div>
            <?php /* ?><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/map.jpg');?>" class="img-responsive"><?php */?> </div>
        <div class="col-md-3 col-sm-6">
          <h3>flicker Feed</h3>
          <ul class="flickrPosts">
            <li><a href="#"><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/flicker-image1.jpg');?>" class="img-responsive"></a></li>
            <li><a href="#"><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/flicker-image2.jpg');?>" class="img-responsive"></a></li>
            <li><a href="#"><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/flicker-image1.jpg');?>" class="img-responsive"></a></li>
            <li><a href="#"><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/flicker-image2.jpg');?>" class="img-responsive"></a></li>
            <li><a href="#"><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/flicker-image1.jpg');?>" class="img-responsive"></a></li>
            <li><a href="#"><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/flicker-image2.jpg');?>" class="img-responsive"></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- Copyright -->
  <div class="footerBtm">
    <div class="container">
      <div class="row">
        <ul>
          <li><a href="mailto:<?php echo strip_tags($configEmailaddress->description);?>"><?php echo strip_tags($configEmailaddress->description);?></a></li>
          <li><?php echo strip_tags($configCopyright->description);?></li>
          <li><?php echo strip_tags($configPhone->description);?></li>
        </ul>
      </div>
    </div>
  </div>
</footer>
<!-- Footer --> 
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 

<script src="<?php echo Yii::$app->getUrlManager()->createUrl('js/bootstrap.min.js');?>"></script> 
<script src="<?php echo Yii::$app->getUrlManager()->createUrl('js/script.js');?>"></script> 
<script src="<?php echo Yii::$app->getUrlManager()->createUrl('js/tabcollapse.js');?>"></script>  
<<script type="text/javascript">
	<?php /* ?>function init_maps(){
		var myOptions = {
			zoom: 14,
			center: new google.maps.LatLng(<? //php echo $latitude; ?>, <? //php echo $longitude; ?>),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		map = new google.maps.Map(document.getElementById("googlemap_canvas"), myOptions);
		marker = new google.maps.Marker({
			map: map,
			position: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>)
		});
		infowindow = new google.maps.InfoWindow({
			content: "<?php echo $completeAddress;?>",
		});
		google.maps.event.addListener(marker, "click", function () {
			infowindow.open(map, marker);
		});
		infowindow.open(map, marker);
	}
	google.maps.event.addDomListener(window, 'load', init_maps);<?php */ ?>
	$('#searchTb').tabCollapse();
</script>-->
<!--<script type="text/javascript">
	$(document).ready(function(){
		init_maps();       	
	});
</script>-->
