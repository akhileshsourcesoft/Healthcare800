<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Dayname;
use common\models\User;
use frontend\widgets\Feedbackform;
use frontend\widgets\Prettyphoto;
use yii\web\Session;

$currentDate = date("Y-m-d");
$session = new Session;  

?>
<!-- Detail Panel -->
<section class="detailPanel">
  <div class="container">
    <div class="row">
      <div class="detailsBox">
        <aside class="col-md-9 col-sm-8">
          <div class="detailsLeft">
            <div class="leftTop">
              <aside class="col-md-8">
                <div class="col-md-3">
                  <div class="row">
                    <figure><a href="#">
						<?php 
						$profile_image = str_replace("users/providers/","",$providerlist['profile_image']);
						if($providerlist['gender']==1){
							$defaultImage = 'male_icon.png';
						}else{
							$defaultImage = 'female_icon.png';
						}
						if(file_exists('uploads/'.$providerlist['profile_image']) && (!empty($profile_image))){ ?>
							<img src="<?php echo Yii::$app->getUrlManager()->createUrl('uploads/'.$providerlist['profile_image']);?>" class="img-responsive">
						<?php }else{ ?>
							<img src="<?php echo Yii::$app->getUrlManager()->createUrl('uploads/users/providers/'.$defaultImage);?>" class="img-responsive">
						<?php } ?>
					</a></figure>
                  </div>
                </div>
                <div class="col-md-9">
                  <h3><a href="#">Dr. <?php echo $providerlist->fname.' '.$providerlist->lname;?></a></h3>
                  <h4><?php echo $providerlist->qualification->name;?></h4>
                  <p><?php echo $providerlist->experience;?> years experience, General Physician </p>
                  <?php if(isset($providerlist->short_desc) && (!empty($providerlist->short_desc))){ ?>
					<div class="descRiption">
						<h5>Description</h5>
						<p><span class="more"><?php echo strip_tags($providerlist->short_desc);?></span></p>
						</div>
					</div>
				 <?php } ?>
              </aside>
              <aside class="col-md-4 text-center">
				<?php
				if(isset(Yii::$app->user->id) && (!empty(Yii::$app->user->id))){
					if(Yii::$app->user->identity->userRole['id'] == USER::URID_USER) {
						echo '<button type="button" class="feedbackform" data-toggle="modal" data-target="#myFeedbackModal">Give Feedback</button>';
					}
				}else{
					echo '<button type="button" class="login" data-toggle="modal" data-target="#myModal">Give Feedback</button>';
				}
				?>
                <div class="shareIcons"> <span>Share</span>
                  <ul>
                    <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                  </ul>
                </div>
              </aside>
            </div>
            <div class="infoTabs">
              <ul id="infoTabs" class="nav nav-tabs" role="tablist">
                <li <?php if(!isset($_GET['t']) && (empty($_GET['t']))){ echo 'class="active"';}?>><a href="#tabs1" data-toggle="tab">Info</a></li>
                <li <?php if(isset($_GET['t']) && (!empty($_GET['t']))){ echo 'class="active"';}?>><a href="#tabs2" data-toggle="tab">feedback</a></li>
                <li><a href="#tabs3" data-toggle="tab">Consult Q&A </a></li>
              </ul>
              <div class="tab-content">
                <div role="tabpanel" class="tab-pane <?php if(!isset($_GET['t']) && (empty($_GET['t']))){ echo 'active';}?>" id="tabs1">
                  <div class="col-md-6">
                    <p><i aria-hidden="true" class="fa fa-map-marker"></i><a href="#"><?php echo $providerlist->state->name.', '.$providerlist->city;?></a></p>
                    <p><i class="fa fa-dollar" aria-hidden="true"></i><?php echo $providerlist->provider_fees_id;?></p>
                    <p><i aria-hidden="true" class="fa fa-clock-o"></i>
                    <?php
					$dayAvails = '';
					foreach($providerlist->providersDayAvailabilities as $dayAvail){
						if($dayAvail['slot_date']>=$currentDate){
							$dayAvails[] = $dayAvail['day_id'];
							$slot=1;
							foreach($dayAvail->providersTimeAvailabilities as $timeValue){
								if($slot==1){
									$slotStartTime = $timeValue['start_time'];
								}else{
									$slotEndTime = $timeValue['start_time'];
								}
							$slot++;}
						}
					}
					$uniqueDays = array_unique($dayAvails);
					$currentDay = current($dayAvails);
					$endDay = end($dayAvails);
					if(!empty($currentDay)){
						$daylist = Dayname::find()->where(['id'=>$currentDay])->andWhere(['status'=>1])->one();
						echo $daylist->day_name;
					}
					if(!empty($currentDay)){
						$daylist = Dayname::find()->where(['id'=>$endDay])->andWhere(['status'=>1])->one();
						echo '- '.$daylist->day_name;
					}
					?>
					<span>
						<?php if(!empty($slotStartTime)){ echo $slotStartTime;}?>
						<?php if(!empty($slotEndTime)){ echo '- '.$slotEndTime;}?>
					</span>
                    </p>
                 <?php
					echo Prettyphoto::widget(['ptokenid'=>Yii::$app->request->get('pid')]);
                 ?>
                  </div>
                  <div class="col-md-6 rgt">
                    <div class="mapArea" id="gmap_canvas"></div>
                  </div>
                </div>
                <div role="tabpanel" class="tab-pane <?php if(isset($_GET['t']) && (!empty($_GET['t']))){ echo 'active';}?>" id="tabs2">
				  <div class="usersfeedback">
				  <?php
				  if(count($providerfeedback)>0){
					 foreach($providerfeedback as $pkey=>$value){  
						 $userImages = User::find()->where(['id'=>$value['user_id']])->one();
						 $profileImage = explode('/',$userImages->profile_image); 
						 if(file_exists('uploads/'.$userImages->profile_image) && (!empty($userImages->profile_image))){ ?>
							<div class="col-md-2 sm-col-3 userName">
								<img src="<?php echo Yii::$app->getUrlManager()->createUrl('uploads/'.$userImages->profile_image);?>" class="img-responsive">
								<div class="picName"><?php echo  $userImages->fname.' '.$userImages->lname;?></div>
							</div>
							<div class="col-md-10  sm-col-9"><p><?php echo $value['message'];?></p><span><?php echo  $datetime2 = date("M d Y",strtotime($value['created_date']));?></span></div>
                            <div class="clearfix"></div>
						<?php }else{ ?>
							<div class="col-md-2 sm-col-3 userName">
								<img src="<?php echo Yii::$app->getUrlManager()->createUrl('uploads/users/providers/'.$defaultImage);?>" class="img-responsive">
								<div class="picName"><?php echo  $userImages->fname.' '.$userImages->lname;?></div>
							</div>
							<div class="col-md-10  sm-col-9"><p><?php echo $value['message'];?></p><span><?php echo  $datetime2 = date("M d Y",strtotime($value['created_date']));?></span></div>
                            <div class="clearfix"></div>
						<?php } ?>

					<?php } 
				  }else{
						echo 'No record found.';
				   } ?>
				   </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="tabs3">
                  <p> <strong>Lorem ipsum dolor sit amet, consectetur</strong> Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur </p>
                  <p> <strong>Lorem ipsum dolor sit amet, consectetur</strong> Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur </p>
                  <p> <strong>Lorem ipsum dolor sit amet, consectetur</strong> Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur </p>
                  <p> <strong>Lorem ipsum dolor sit amet, consectetur</strong> Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur </p>
                  <p> <strong>Lorem ipsum dolor sit amet, consectetur</strong> Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur Lorem ipsum dolor sit amet, consectetur </p>
                </div>
              </div>
            </div>
          </div>
        </aside>
        <aside class="col-md-3 col-sm-4">
          <div class="detailsRight">
			  <?php $form = ActiveForm::begin(['id'=>'timeslotbookappontments']); ?>
              <div>
				 <?= $form->field($modeltimeslotbooking, 'fullname')->textInput(['placeholder'=>'Enter Your Fullname.'])->label('Patient/Visitor Name'); ?>
              </div>
              <div>
				  <?= $form->field($modeltimeslotbooking, 'email')->textInput(['placeholder'=>'Enter Your Email Id.']); ?>
              </div>
              <div>
				 <?php
				 if(!empty($bookingTime)){
					  echo $form->field($modeltimeslotbooking, 'booking_date')->textInput(['value'=>date("Y M d",strtotime($bookingDate)).'  '.$bookingTime, 'readonly'=>true])->label('<a href="javascript:void(0);" title="<< Go back for book the appointment." class="editTimeSlot" onclick="goBack()"><i class="fa fa-edit" aria-hidden="true"></i>Edit Time</a>Appointment Date &amp; Time');
					   echo $form->field($modeltimeslotbooking, 'bookingdate')->hiddenInput(['value'=>$bookingDate])->label(false);  
					   echo $form->field($modeltimeslotbooking, 'bookingtime')->hiddenInput(['value'=>$bookingTime])->label(false);  
				 }else{
					  echo $form->field($modeltimeslotbooking, 'booking_date')->textInput(['readonly'=>true,])->label('<a href="javascript:void(0);" title="<< Go back for book the appointment." class="editTimeSlot" onclick="goBack()"><i class="fa fa-edit" aria-hidden="true"></i>Edit Time</a>Appointment Date &amp; Time'); 
				 }
				?>
              </div>
              <div>
				 <?= $form->field($modeltimeslotbooking, 'phone_no')->textInput(['placeholder'=>'Enter Your Landline / Phone No.'])->label('Landline / Phone No.'); ?>
              </div>
              <div>
				<?= $form->field($modeltimeslotbooking, 'payment_method')->radioList(['1' => 'Co-Payment', '2' => 'Paypal']);?>
              </div>
              <div id="providers_fees" style="display:none;">
				<?php $modeltimeslotbooking->providerfees = $providerlist['provider_fees_id'];?>
				<?= $form->field($modeltimeslotbooking, 'providerfees')->textInput(['readonly'=>true])->label('Provider Fee ($) ');?>
				<?= $form->field($modeltimeslotbooking, 'copaymentfees')->textInput()->label(false); ?>
				<span id="copaymentfeesmsg"></span>
              </div>
              <div>
				<?php
				 if(isset(Yii::$app->user->id) && (!empty(Yii::$app->user->id))){ 
					 if(Yii::$app->user->identity->userRole['id'] == USER::URID_USER){ ?>
						<input name="bookappointment" id="bookappointment" type="submit"  value="Book Appointment">
					<?php } ?>
                <?php }else{ ?>
					<button type="button" class="login" data-toggle="modal" data-target="#myModal">Book Appointment</button>	
				<?php } ?>
              </div>
             <?php ActiveForm::end(); ?>
          </div>
        </aside>
      </div>
      
	<div class="modal fade" id="myFeedbackModal" role="dialog">
	<div class="modal-dialog">
		  <!-- Modal content-->
		  <div class="modal-content signupBox">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body feedbackform">
					<h3>Feedback Form</h3>
					<?php echo Feedbackform::widget(['user_id'=>Yii::$app->user->id, 'provider_id'=>$_GET['pid']]);?>
			</div>
	  </div>
	</div>
	</div>
      
    <div class="modal fade" id="myModalfeedback" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
		  <h4 style="padding:20px; font-weight:normal; color:#da452f;">Your message has been submitted successfully.</h4>
        </div>
		<div class="modal-body feedbackforms" style="height: 100px;">
			<div class="row">
			  <div class="col-md-12">
				<button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
			  </div>
			</div>
		</div>
  </div>
</div>
</div>
 
     
    </div>
  </div>
</section>
<?php
$completeAddress = $providerlist->address.', '.$providerlist->city.', '.$providerlist->state->name.', '.$providerlist->zip_code;
$data_arr = User::googleMaps($completeAddress); 
if($data_arr){
	$latitude = $data_arr[0];
	$longitude = $data_arr[1];
	$formatted_address = $data_arr[2]; 
	if(!empty($latitude) && (!empty($longitude))){
	?>
	<script>
		function init_map(){
			var myOptions = {
				zoom: 14,
				center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
			marker = new google.maps.Marker({
				map: map,
				position: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>)
			});
			infowindow = new google.maps.InfoWindow({
				content: "<?php echo $completeAddress; ?>"
			});
			google.maps.event.addListener(marker, "click", function () {
				infowindow.open(map, marker);
			});
			infowindow.open(map, marker);
		}
		google.maps.event.addDomListener(window, 'load', init_map);	
	 </script>
<?php } 
  }
?>
<style>
.morecontent span {
    display: none;
}
.morelink {
    display: block;
}
</style>
<script>
 $(document).ready(function(){
    var showChar = 200; 
    var ellipsestext = "...";
    var moretext = "More...";
    var lesstext = "Less...";
    $('.more').each(function() {
        var content = $(this).html();
        if(content.length > showChar) {
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink more">' + moretext + '</a></span>';
            $(this).html(html);
        }
    });
    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
	$("#usertimeslotbooking-phone_no").mask("999-999-9999");
	//$("#gmap_canvas").css({'width':'100%', 'height':'400px'});
		init_map();       	
});
function goBack(){
    window.history.back();
}
$("input[name='UserTimeslotBooking[payment_method]']").click(function(){
	var paymentMethod = $(this).val();
	if(paymentMethod==1){
		$("#providers_fees").show(1000);
	}else{
		$("#providers_fees").hide(1000);
	}
});

</script>
<!-- Detail Panel --> 
