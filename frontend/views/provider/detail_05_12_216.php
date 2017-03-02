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
foreach($providerlist['providerUserPrices'] as $val){
	$providerFees[] = $val['providerFees'];
}
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
				 <?php
				 if(!isset(Yii::$app->user->identity->id) && (empty(Yii::$app->user->identity->id))){
					echo $form->field($modeltimeslotbooking, 'prtokenid')->hiddenInput(['value'=>Yii::$app->request->get('pid')])->label(false);
					echo $form->field($modeltimeslotbooking, 'timeSlot')->hiddenInput(['value'=>Yii::$app->request->get('ts')])->label(false);
					echo $form->field($modeltimeslotbooking, 'ajaxtslotbook')->hiddenInput(['value'=>'ajaxTimeslotbook'])->label(false);
				 }else{
					 echo $form->field($modeltimeslotbooking, 'ajaxtslotbook')->hiddenInput()->label(false);
				 }
				 ?>
				 <span id="booking_fullname_msg"></span>
              </div>
              <div>
				  <?= $form->field($modeltimeslotbooking, 'email')->textInput(['placeholder'=>'Enter Your Email Id.']); ?>
				  <span id="booking_emailid_msg"></span>
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
				 $modeltimeslotbooking->payment_method = '1';
				?>
				<span id="booking_date_msg"></span>
              </div>
              <div>
				 <?= $form->field($modeltimeslotbooking, 'phone_no')->textInput(['placeholder'=>'Enter Your Landline / Phone No.'])->label('Landline / Phone No.'); ?>
				 <span id="booking_phoneno_msg"></span>
              </div>
               <div>
				   <div class="form-group field-usertimeslotbooking-user_type_id">
					<label class="control-label" for="usertimeslotbooking-user_type_id">User Type</label>
						<div id="usertimeslotbooking-user_type_id">
							<label><input type="radio" value="1" name="UserTimeslotBooking[user_type_id]" id="user_type_id_1"> New User</label>
							<label><input type="radio" value="2" name="UserTimeslotBooking[user_type_id]" id="user_type_id_2"> Existing User</label>
						</div>
					</div>
					<span id="booking_patienttype_msg"></span>
              </div>
              <div>
				<?= $form->field($modeltimeslotbooking, 'payment_method')->radioList(['1' => 'Insurance Coverage', '2' => 'Non Insurance Coverage']);?>
              </div>
              <div id="providers_fees">
				<?php $modeltimeslotbooking->providerfees = $providerlist['provider_fees_id'];?>
				
				<?= $form->field($modeltimeslotbooking, 'insurance_companies_id')->textInput(['placeholder'=>'Enter Your Name of the insurance'])->label('Name of the insurance');?>
				<span id="insurance_companies_msg"></span>
				<?= $form->field($modeltimeslotbooking, 'insuranceid_card')->textInput(['placeholder'=>'Enter Your Insurance ID-Card No.'])->label('Insurance ID-Card No.');?>
				<span id="insuranceid_card_msg"></span>
				<?= $form->field($modeltimeslotbooking, 'insurance_comp_address')->textInput(['placeholder'=>'Enter Your Address of Insurance Company'])->label('Address of insurance company');?>
				<span id="insurance_comp_address_msg"></span>
				<?= $form->field($modeltimeslotbooking, 'group_insurance')->textInput(['placeholder'=>'Enter Your Group No. of insurance Card'])->label('Group No. of insurance card');?>
				<span id="group_insurance_msg"></span>
				<?php 
				if(!empty($providerFees['1']['fees'])){
					$modeltimeslotbooking->co_payment = $providerFees['1']['fees'];
				}
				?>
				<div class="row">
				<div class="col-md-1 col-sm-1"><input type="radio" value="C" name="UserTimeslotBooking[booking_pay_type]" id="booking_pay_type_1"></div>
				<div class="col-md-10 col-sm-10"><?= $form->field($modeltimeslotbooking, 'co_payment')->textInput(['readonly'=>false,'id'=>'copayment_fees'])->label('Insurance Co-payment <span style="color:red">($)</span>');?></div>
				
				<div class="clearfix"></div>
				<div class="col-md-1 col-sm-1"><input type="radio" value="D" name="UserTimeslotBooking[booking_pay_type]" id="booking_pay_type_2"></div> <div class="col-md-10 col-sm-10"><?= $form->field($modeltimeslotbooking, 'deductibles')->textInput(['readonly'=>false, 'id'=>'deductibles_fees', 'maxlength'=>3, 'placeholder'=>'Enter Your deductibles Price.'])->label('deductibles <span style="color:red">($)</span>');?></div>
				</div>
				<span id="deductibles_msg"></span>
              </div>
              <div id="paypalpay" style="display:none;">
					<?php 
					if(!empty($providerFees['0']['fees'])){
						$modeltimeslotbooking->deductibles = $providerFees['0']['fees'];
					}
					?>
					<div style="margin-bottom:20px; font-size:13px;">
						<input type="radio" value="P" name="UserTimeslotBooking[booking_pay_type]" id="paypal"> Paypal <br/>
						<span id="paypal_msg"></span>
					</div>
					<div id="existing_user">
						<?= $form->field($modeltimeslotbooking, 'paypal_co_payment')->hiddenInput(['readonly'=>false, 'maxlength'=>3, 'placeholder'=>'Enter Your deductibles Price.'])->label(false);?>
					</div>
					<div id="new_user">
						<?= $form->field($modeltimeslotbooking, 'paypal_deductibles')->hiddenInput(['readonly'=>false, 'maxlength'=>3, 'placeholder'=>'Enter Your deductibles Price.'])->label(false);?>
					</div>
              </div>
              <div>
				<?php
				 if(isset(Yii::$app->user->identity->id) && (!empty(Yii::$app->user->identity->id))){ 
					 if(Yii::$app->user->identity->userRole['id'] == USER::URID_USER){ ?>
						<input name="bookappointment" id="bookappointment" type="button" value="Book Appointment" onclick="userBookappointment();">
					<?php } ?>
                <?php }else if(!empty(Yii::$app->user->identity->id)){ ?>
					 <button type="button" class="login" data-toggle="modal" data-target="#myModal">Book Appointment</button>	
				<?php }else{ ?>
					<button type="button" class="login" onclick="userBookappointment();">Book Appointment</button>	
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
$completeAddress = $providerlist->city.', '.$providerlist->state->name.', '.$providerlist->country->name.', '.$providerlist->zip_code;
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

function userBookappointment(){
	<?php if(!empty($providerFees['0']['fees'])){ ?>
		var deducatbles_fee = '<?php echo $providerFees['0']['fees']?>';
	<?php } ?>
	var valid = 1;
	var bookingfullname = $("#usertimeslotbooking-fullname").val();
	if(bookingfullname==""){
		$("#booking_fullname_msg").text("Please enter fullname.").css({'color':'red','font-size':'13px'});
		$("#usertimeslotbooking-fullname").focus();
		valid = 0;
	}else{
		$("#booking_fullname_msg").empty();
	}
	var booking_email = $("#usertimeslotbooking-email").val();
	if(booking_email==""){
		$("#booking_emailid_msg").text("Please enter email.").css({'color':'red','font-size':'13px'});
		$("#usertimeslotbooking-email").focus();
		valid = 0;
	}
	
	if(booking_email!=""){
		if(!validateEmail(booking_email)){
			$("#booking_emailid_msg").text("Please enter valid email.").css({'color':'red','font-size':'13px'});
			valid = 0;
		}else{
			$("#booking_emailid_msg").empty();
		}
	}
	var booking_date = $("#usertimeslotbooking-booking_date").val();
	if(booking_date==""){
		$("#booking_date_msg").text("Please enter booking date").css({'color':'red','font-size':'13px'});
		$("#usertimeslotbooking-booking_date").focus();
		valid = 0;
	}else{
		$("#booking_date_msg").empty();
	}
	
	var booking_phone_no = $("#usertimeslotbooking-phone_no").val();
	if(booking_phone_no==""){
		$("#booking_phoneno_msg").text("Please enter landline / phone no.").css({'color':'red','font-size':'13px'});
		$("#usertimeslotbooking-phone_no").focus();
		valid = 0;
	}else{
		$("#booking_phoneno_msg").empty();
	}
	
	var user_typevalue = $("input[name='UserTimeslotBooking[user_type_id]']:checked").length;
	if(user_typevalue==0){
		jQuery("#booking_patienttype_msg").text("Please checked atleast one option.").css({'color':'red','font-size':'13px'});
		valid = 0;	
	}else{
		$("#booking_patienttype_msg").empty();
	}
	var payment_method = $("input[name='UserTimeslotBooking[payment_method]']:checked").val();
	if(payment_method==1){
		var insurance_companies = $("#usertimeslotbooking-insurance_companies_id").val();
		if(insurance_companies==""){
			$("#insurance_companies_msg").text("Please enter insurance name.").css({'color':'red','font-size':'13px'});
			$("#usertimeslotbooking-insurance_companies_id").focus();
			valid = 0;
		}
		var insuranceid_card = $("#usertimeslotbooking-insuranceid_card").val();
		if(insuranceid_card==""){
			$("#insuranceid_card_msg").text("Please enter insurance card no.").css({'color':'red','font-size':'13px'});
			$("#usertimeslotbooking-insuranceid_card").focus();
			valid = 0;
		}
		var insurance_comp_address = $("#usertimeslotbooking-insurance_comp_address").val();
		if(insurance_comp_address==""){
			$("#insurance_comp_address_msg").text("Please enter address of insurance comapny.").css({'color':'red','font-size':'13px'});
			$("#usertimeslotbooking-insurance_comp_address").focus();
			valid = 0;
		}
		var group_insurance =  $("#usertimeslotbooking-group_insurance").val();
		if(group_insurance==""){
			$("#group_insurance_msg").text("Please enter group no. of insurance card.").css({'color':'red','font-size':'13px'});
			$("#usertimeslotbooking-group_insurance").focus();
			valid = 0;
		}
		
	  var booking_paytype = $("input[name='UserTimeslotBooking[booking_pay_type]']:checked").length;
	  if(booking_paytype==0){
		jQuery("#deductibles_msg").text("Please checked atleast one option.").css({'color':'red','font-size':'13px'});
		valid = 0;	
	  }
		
	 var booking_pay_type = $("input[name='UserTimeslotBooking[booking_pay_type]']:checked").val();
	 if(booking_pay_type!=undefined){
		 if(booking_pay_type=='D'){
			var deductiblesfees = $("#deductibles_fees").val();
			if(deductiblesfees==""){
				$("#deductibles_msg").text("Please enter deductibles fee.").css({'color':'red','font-size':'13px'});
				$("#deductibles_fees").focus();
				valid = 0;
			}
			if(deductiblesfees!=""){
			   if(deductiblesfees > 300 || deductiblesfees < deducatbles_fee){
					$("#deductibles_msg").text("Please enter deductibles fee greater than "+deducatbles_fee+" ($)").css({'color':'red','font-size':'13px'});
					$("#deductibles_fees").focus();
					valid = 0;  
				}else{
					$("#deductibles_msg").empty();	
				}
			 }
		  }else{
			$("#deductibles_msg").empty();	 
		  }
	  }
	}else{
		var paypal = $("input[name='UserTimeslotBooking[booking_pay_type]']:checked").length;
		if(paypal==0){
			jQuery("#paypal_msg").text("Please checked paypal option.").css({'color':'red','font-size':'13px'});
			jQuery("#paypal").focus();
			valid = 0;	
		}else{
			$("#paypal_msg").empty();	
		}	
	}
	if(valid==0){
		return false;
	}else{
		<?php if(!empty(Yii::$app->user->identity->id)){ ?>
			$("#timeslotbookappontments").submit();
		<?php }else{ ?>
			$('#myModal').modal('show');
			var ajaxLogin = 'ajaxLogin';
			withoutLogin(ajaxLogin);
		<?php } ?>
	}
}
function withoutLogin(ajaxLogin){
		$("#loginform-booktslot").val(ajaxLogin);
}

$("input[name='UserTimeslotBooking[payment_method]']").click(function(){
	var paymentMethod = $(this).val();
	if(paymentMethod==1){
		$("#providers_fees").show(1000);
		$("#paypalpay").hide(1000);
	}else{
		$("#providers_fees").hide(1000);
		$("#paypalpay").show(1000);
		$("#usertimeslotbooking-insurance_companies_id").val(''); 
		$("#usertimeslotbooking-insuranceid_card").val('');
		$("#usertimeslotbooking-insurance_comp_address").val('');
		$("#usertimeslotbooking-group_insurance").val('');
	}
});
$("input[name='UserTimeslotBooking[user_type_id]']").click(function(){
	<?php if(!empty($providerFees['0']['fees'])){ ?>
		var deducatbles_fee = '<?php echo $providerFees['0']['fees']?>';
		var co_payment_fees = '<?php echo $providerFees['1']['fees']?>';
	<?php } ?>
	var patientType = $(this).val();
	if(patientType==1){
		$("#fees").show(1000);
		$("#copayment_fees").val(deducatbles_fee);
		$("#existing_user").hide();
		$("#new_user").show();
		$("#usertimeslotbooking-paypal_co_payment").val('');
		$("#usertimeslotbooking-paypal_deductibles").val(deducatbles_fee);
	}else if(patientType==2){
		$("#fees").hide(1000);
		$("#copayment_fees").val(co_payment_fees);
		$("#new_user").hide();
		$("#usertimeslotbooking-paypal_deductibles").val('');
		$("#usertimeslotbooking-paypal_co_payment").val(co_payment_fees);
		$("#existing_user").show();
	}
});


jQuery('#usertimeslotbooking-insurance_companies_id').autocomplete({
	source: function( request, response ) {
		jQuery.ajax({
			url: '<?php echo Yii::$app->getUrlManager()->createUrl("provider/insurancecomplisting");?>',
			dataType: "json",
			data: {insurancekey: request.term,},
			 success: function( data ) {
				 response( jQuery.map( data, function( item ) {
					return {
						label: item,
						value: item
					}
				}));
			}
		});
	},
	autoFocus: true,
	minLength: 1
});
function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}
</script>
<!-- Detail Panel --> 
