<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\FileInput;
use yii\helpers\ArrayHelper;
use kartik\time\TimePicker;
use kartik\date\DatePicker;
use common\models\ProvidersDayAvailability;
use common\models\ProvidersTimeAvailability;
use common\models\ClinicBanner;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$listDataUserRole =	ArrayHelper::map($userRoleData,'id','role_name');
$countryModel = ArrayHelper::map($countryModel, 'country_id', 'name'); 
$servicesCategory = ArrayHelper::map($servicesCategory, 'category_id', 'category_name'); 
$statelistModel = ArrayHelper::map($statelistModel, 'state_id', 'name'); 
$qualificationlistModel = ArrayHelper::map($qualificationlistModel, 'id', 'name'); 

?>
<!-- featured Panels -->
	<div class="container">
		<div class="contact-providers-form">
		<?php $form = ActiveForm::begin([
			'options' => ['enctype'=>'multipart/form-data'],
			'id' => 'user-providers-form'
		]); ?>	
	<!------------------------------ tabs --------------------------------------->
	  <ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#tab1" class="tab1" id="tab1-link">General</a></li>
		<li><a data-toggle="tab" href="#tab2" id="tab2-link">Availability</a></li>
		<li><a data-toggle="tab" href="#tab3" id="tab3-link">Categories</a></li>
		<li><a data-toggle="tab" href="#tab4" id="tab4-link">Clinic Banners</a></li>
	  </ul>
	<!----------------------------- / tabs ----------------------------------------->
		
	 <div class="tab-content">
	<!------------------------------ Product Content -------------------------------->	   
		<div id="tab1" class="tab-pane fade in active">

			<ul class="nav" id="lb-tabs" style="padding-top:20px;">

			<?= $form->field($model, 'fname')->textInput(['maxlength' => true])->label('First Name');?>
				<span id="user_fname_msg"></span>
			<?= $form->field($model, 'lname')->textInput(['maxlength' => true])->label('Last Name');?>
				<span id="user_lname_msg"></span>
			<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
				<span id="user_email_msg"></span>
			<?= $form->field($model, 'address')->textarea() ?>
		
			<?php
			echo '<label>Date Of Birth</label>';
			echo DatePicker::widget([
				'name' => 'User[dob]', 
				'options' => ['placeholder' => 'Select date of birth'],
				'pluginOptions' => [
					'format' => 'dd-mm-yyyy',
					'autoclose' => true,	
				]
			]);
			?>
			
			<?= $form->field($model, 'gender')->radioList(['1' => 'Male', '0' => 'Female']);?>
			
			<?= $form->field($model, 'mobile')->textInput(['maxlength' => 10]) ?>
				<span id="user_mobile_msg"></span>
			<?= $form->field($model, 'landline')->textInput(['maxlength' => 12])->label('Landline / Phone No.') ?>
			
			 <?= $form->field($model, 'qualification_id')->dropDownList($qualificationlistModel, ['prompt'=>'Select Qualification / Degree'])->label('Qualification / Degree'); ?>
				<span id="user_qualification_id_msg"></span>
			<?= $form->field($model, 'clinic_name')->textInput()->label('Clinic Name'); ?>
				<span id="user_clinic_name_msg"></span>
			<?= $form->field($model, 'experience')->textInput()->label('Work Experience'); ?>
				<span id="user_experience_msg"></span>
			<?= $form->field($model, 'fees')->textInput()->label('Fee'); ?>
				<span id="user_fees_msg"></span>
			<?php
			 if($model->isNewRecord){ 
				echo $form->field($model, 'state_id')->dropDownList($statelistModel,['prompt'=>'Select State'])->label('State');
			 } 
			?>
			<span id="user_state_msg"></span>
			<?= $form->field($model, 'city')->textInput(['maxlength' => true])->label('City'); ?>
			<span id="user_city_msg"></span>
			<?php if (!empty($model->profile_image)) {
					echo Html::img(Yii::$app->urlManagerFrontend->createUrl(['uploads']).'/' . $model->profile_image, ['width'=>'100'], [
					'class'=>'img-thumbnail']);
					echo $form->field($model, 'profile_image')->fileInput();
				} else {
					echo $form->field($model, 'profile_image')->fileInput();
				}
			?>
			</ul>
		</div>

		<div id="tab2" class="tab-pane fade" style="padding-top:20px; padding-bottom:17px;">   
			<fieldset>
				<legend>Availability:</legend>			
				<div class="time_slot">
					<?php  foreach($daylistModel as $weekdayName){ ?>
					<ul>
						<li><?php echo $weekdayName['day_name'];?></li>
						<li>Morning Time
							<ul class="timeTable">
							<?php
								switch($weekdayName['id']){
										case 1:
											$className = "sundayTslot";
											break;
										case 2:
											$className = "mondayTslot";
											break;
										case 3:
											$className = "tuesdayTslot";
											break;
										case 4:
											$className = "wednesdayTslot";
											break;
										case 5:
											$className = "thursdayTslot";
											break;
										case 6:
											$className = "fridayTslot";
											break;
										case 7:
											$className = "saturdayTslot";
											break;
										default:
											$className = "noTslot";
											break;
									}
							
							 $intTime = 10;
							 for($i=9; $i<=11; $i++){
								 for($j=1; $j<=6; $j++){ 
									if($j==1){
										$intValue = $i.':'.'00';	
									}else{
										$intValue = $i.':'.($j-1)*$intTime;	
									}
								
							 ?>
								<li class="uncheckedTimeslot_<?php echo $weekdayName['id'].' '.$className?>" id="<?php echo $className.'_'.$i.'_'.$j;?>" onclick="providerAvail(this.id,<?php echo $weekdayName['id']?>,'M')"><?php echo $intValue;?></li>
							<?php } 
							}
						?>
						
							</ul>
						</li>
						<li>After Noon Time
							<ul class="timeTable">
							<?php
							 $intTime = 10;
							 for($i=12; $i<=15; $i++){
								 for($j=1; $j<=6; $j++){
									if($j==1){
										$intValue = $i.':'.'00';	
									}else{
										$intValue = $i.':'.($j-1)*$intTime;	
									}
									 
							 ?>
								<li class="uncheckedTimeslot_<?php echo $weekdayName['id'].' '.$className?>" id="<?php echo $className.'_'.$i.'_'.$j;?>" onclick="providerAvail(this.id,<?php echo $weekdayName['id']?>,'A')"><?php echo $intValue;?></li>
							<?php } 
							}
						?>
						
							</ul>
						</li>
						<li>Evening Time
						<ul class="timeTable">
							<?php
							 $intTime = 10;
							 for($i=16; $i<=19; $i++){
								 for($j=1; $j<=6; $j++){
									if($j==1){
										$intValue = $i.':'.'00';	
									}else{
										$intValue = $i.':'.($j-1)*$intTime;	
									}
									 
							 ?>
								<li class="uncheckedTimeslot_<?php echo $weekdayName['id'].' '.$className?>" id="<?php echo $className.'_'.$i.'_'.$j;?>" onclick="providerAvail(this.id,<?php echo $weekdayName['id']?>,'E')"><?php echo $intValue;?></li>
							<?php } 
							}
						?>
						
							</ul>
						</li>
						<li>Night Time
						<ul class="timeTable">
							<?php
							 $intTime = 10;
							 for($i=20; $i<=23; $i++){
								 for($j=1; $j<=6; $j++){
									if($j==1){
										$intValue = $i.':'.'00';	
									}else{
										$intValue = $i.':'.($j-1)*$intTime;	
									}
							 ?>
								<li class="uncheckedTimeslot_<?php echo $weekdayName['id'].' '.$className?>" id="<?php echo $className.'_'.$i.'_'.$j;?>" onclick="providerAvail(this.id,<?php echo $weekdayName['id']?>,'N')"><?php echo $intValue;?></li>
							<?php } 
							}
						?>
						
							</ul>
						</li>
						
					</ul>
					<?php } ?>
				</div>
			</fieldset>
			</div>
			<div id="tab3" class="tab-pane fade" style="padding-top:20px;">	  
				<?= $form->field($model, 'services_category_id')->dropDownList($servicesCategory, ['prompt'=>'Select Category'])->label('Category'); ?>
				<span id="user_services_category_id"></span>
			</div>
			
			<div id="tab4" class="tab-pane fade" style="padding-top:20px;">	  
				 <table class="table table-bordered table-hover" id="container">
					<thead>	
						<tr>
							<th class="col-sm-2">Image</th>
							<th class="col-sm-2">Preview</th>
							<th class="col-sm-2">Image Title</th>
							<th class="col-sm-2">Url</th>
							<th class="col-sm-1">Sort Order</th>
							<th class="col-sm-1">Action</th>
						</tr>
					</thead>
					<?php
					$clinicBanner = ClinicBanner::find()->where(['provider_id'=>$model->id])->all();
					if(count($clinicBanner)>0){
						$i=1;
						foreach($clinicBanner as $clinicVal):
					?>
					<tr id='addrow_<?php echo $clinicVal->id;?>' class="bannerRows">				
						<td>
							<?php echo str_replace("users/providers_banner/", "", $clinicVal->images);?>
						</td>
						<td>
							<?php
								echo Html::img(Yii::$app->urlManagerFrontend->createUrl(['uploads']).'/' . $clinicVal->images, ['width'=>'70'], [
								'class'=>'img-thumbnail']);
								echo $form->field($clinicVal, 'hidden')->hiddenInput(['name'=>'ClinicBanner[hiddenid][]', 'value'=>$clinicVal->id])->label(false);
							?>
						</td>
						<td>
							<?= $form->field($clinicVal, 'img_title')->textArea(['name'=>'ClinicBanner[img_title][]', 'id'=>'ClinicBanner_img_title_'.$i.''])->label(false) ?>
						</td>
						<td>
							<?= $form->field($clinicVal, 'url')->textArea(['name'=>'ClinicBanner[url][]'])->label(false) ?>
						</td>
						<td>
							<?= $form->field($clinicVal, 'sort_order')->textInput(['name'=>'ClinicBanner[sort_order][]'])->label(false) ?>
						</td>							
						<td>
							<button id="updateAddrow" class="btn add-more btn btn-success" type="button" <?php if($i!=1){ echo 'style="display:none"';}?>>+</button>
							<a class="btn btn-danger" href="javascript:void(0);" onclick="removeUpdatebtn(<?php echo $clinicVal->id;?>), updateAddrow();">x</a>
						</td>			
					</tr>
					<?php $i++; endforeach; ?>
					<?php }else{ ?>
					<tr id='addrow_1' class="bannerRows">				
						<td>
							<?= $form->field($clinicBannerModel, 'images')->fileInput(['name'=>'ClinicBanner[images][]', 'id'=>"ClinicBanner_images_1",  'class'=>'clinic_banner_image'])->label(false) ?>
						</td>
						<td>
							<div id="imagePreview_1" class="imagePreviewC"></div>
						</td>
						<td>
							<?= $form->field($clinicBannerModel, 'img_title')->textArea(['name'=>'ClinicBanner[img_title][]'])->label(false) ?>
						</td>
						<td>
							<?= $form->field($clinicBannerModel, 'url')->textArea(['name'=>'ClinicBanner[url][]'])->label(false) ?>
						</td>
						<td>
							<?= $form->field($clinicBannerModel, 'sort_order')->textInput(['name'=>'ClinicBanner[sort_order][]'])->label(false) ?>
						</td>							
						<td>
							<button id="add_row_1" class="btn add-more btn btn-success" type="button">+</button>
							<button id="remove_1" class="btn btn-danger remove-me" type="button" style="display:none;" onclick="removeBannerBtn(this.id)">-</button>
						</td>			
					</tr>
					<?php } ?>
				</table>			
			</div>
		</div>	

		<div class="form-group">
			<?= Html::Button($model->isNewRecord ? 'Register' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','onclick' => 'validateCantactForm()']) ?>
			 <?= Html::a('Cancel', ['/'], ['class'=>'btn btn-danger']) ?>
		</div>
		<?php ActiveForm::end(); ?>
		</div>
   </div>
<script>
	/*jQuery(".add_more").on( "click", function(){	
		var $div = $('div[class^="timeavailability"]:last');
		var $klon = $div.clone();
		var $klon_id_arr = $klon.prop("id").split("_");
	    var id = $klon_id_arr[1];
	    var num = parseInt(id) +1;
	    var $klon = $klon.prop("id","availability_"+num);
	    $div.after($klon);
	
	    jQuery("#availability_"+num).find(".daystarttime").prop('name','providerstimeavailability-start_time_'+num);   
	    jQuery("#availability_"+num).find(".daystarttime").prop('id','providerstimeavailability-start_time_'+num); 
	    jQuery("#availability_"+num).find(".daystartminutes").prop('name','providerstimeavailability-start_minutes_'+num); 
	    jQuery("#availability_"+num).find(".daystartminutes").prop('id','providerstimeavailability-start_minutes_'+num); 
	    
	    jQuery("#availability_"+num).find(".providersAvailabilitylist").prop('id','providersAvailability_'+num);
	    jQuery("#availability_"+num).find(".providersAvailabilitylist").prop('value',num);
	      
		jQuery("#availability_"+num).find(".dayendtime").prop('name','providerstimeavailability-end_time_'+num);   
	    jQuery("#availability_"+num).find(".dayendtime").prop('id','providerstimeavailability-end_time_'+num);
	    jQuery("#availability_"+num).find(".dayendminutes").prop('name','providerstimeavailability-end_minutes_'+num);
	    jQuery("#availability_"+num).find(".dayendminutes").prop('id','providerstimeavailability-end_minutes_'+num);
	        
	    jQuery("#availability_"+num).find(".dayavailbility").prop('id','providersdayavailability-day_id_'+num);   
	    jQuery("#availability_"+num).find(".dayavailbility").prop('name','providersdayavailability-day_id_'+num); 
	    jQuery("#availability_"+num).find(".removeBtn").prop('id','removeBtn_'+num);   
	    jQuery("#availability_"+num).find(".removeBtn").css('display','block'); 
	    
	    jQuery("#availability_"+num).find(".day_starttime").prop('id','day_starttime_'+num); 
	    jQuery("#availability_"+num).find(".day_endtime").prop('id','day_endtime_'+num); 
	    jQuery("#availability_"+num).find(".dayavail").prop('id','dayavail_'+num); 
	    jQuery("#availability_"+num).find(".day_starttime").empty(); 
	    jQuery("#availability_"+num).find(".dayavail").empty(); 
	    jQuery("#availability_"+num).find(".day_endtime").empty(); 

	});*/
	function removeRowsButton(id){
		var btnId = id.split("_");
		$("#availability_"+btnId[1]).remove();
		
	}
	
	jQuery("#add_row_1").on( "click", function(){	
		var $tr = $('tr[class^="bannerRows"]:last');
		var $trclone = $tr.clone();
		var $trclone_id_arr = $trclone.prop("id").split("_");
	    var id = $trclone_id_arr[1];
	    var trNum = parseInt(id) +1;
	    var $trclone = $trclone.prop("id","addrow_"+trNum);
	    $tr.after($trclone);
	    
	    $("#addrow_"+trNum).find(".bannerRows").prop("id", "addrow_"+trNum);
	    jQuery("#addrow_"+trNum).find(".remove-me").prop('id','remove_'+trNum);   
	    jQuery("#addrow_"+trNum).find(".btn-success").prop('id','add_row_'+trNum);   
	    jQuery("#addrow_"+trNum).find(".btn-success").css('display','none');   
	    jQuery("#addrow_"+trNum).find(".remove-me").css('display','block'); 
	    jQuery("#addrow_"+trNum).find(".clinic_banner_image").prop('id','ClinicBanner_images_'+trNum);
	    jQuery("#addrow_"+trNum).find(".clinic_banner_image").prop('id','ClinicBanner_images_'+trNum).val('');
	    jQuery("#addrow_"+trNum).find(".imagePreviewC").prop('id','imagePreview_'+trNum);
	    jQuery("#addrow_"+trNum).find(".imagePreviewC").prop('id','imagePreview_'+trNum).removeAttr("style");
	    
	    trNum += 1;
		var t = trNum - 1;
		$('#ClinicBanner_images_'+ t).on("change", function(){ 
			var ext = this.value.match(/\.(.+)$/)[1];
			switch (ext) {
				case 'jpg':
				case 'jpeg':
				case 'png':
				case 'gif':
				  
				  var files = !!this.files ? this.files : [];
							if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
					 
							if (/^image/.test( files[0].type)){ // only image file
								var reader = new FileReader(); // instance of the FileReader
								reader.readAsDataURL(files[0]); // read the local file
					 
								reader.onloadend = function(){ // set image data as background of div
									$("#imagePreview_"+ t).css("background-image", "url("+this.result+")");
								}
							}
					break;
				default:
					alert('This file type is not allowed. The uploader accepts among jpg,jpeg, png and gif file types.');
					this.value = '';
					$("#imagePreview_"+ t).removeAttr("style");
			}
		});
	});
	

	$('#ClinicBanner_images_1').on("change", function() { 
		var ext = this.value.match(/\.(.+)$/)[1];
		switch (ext) {
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
			  var files = !!this.files ? this.files : [];
						if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
				 
						if (/^image/.test( files[0].type)){ // only image file
							var reader = new FileReader(); // instance of the FileReader
							reader.readAsDataURL(files[0]); // read the local file
							reader.onloadend = function(){ // set image data as background of div
								$("#imagePreview_1").css("background-image", "url("+this.result+")");
							}
						}
				break;
			default:
				alert('This file type is not allowed. The uploader accepts among jpg,jpeg, png and gif file types.');
				this.value = '';
				$("#imagePreview_1").removeAttr("style");
		}
	});		
	
	
	function removeBannerBtn(id){
		var imgId = id.split("_");
		jQuery("#addrow_"+imgId[1]).remove();
	}
	
	function removeUpdatebtn(id){
		jQuery("#addrow_"+id).remove();
	}
	
	var counter = 0;
	$("#updateAddrow").click(function(){
		counter = counter+1;
		
			var $tr = $('tr[class^="bannerRows"]:last');
			$tr.after('<tr id="addrow_'+counter+'" class="bannerRows"><td><div class="form-group field-clinicbanner-images required"><input id="ClinicBanner_images_'+counter+'" class="clinic_banner_image" name="ClinicBanner[images][]" type="file"></div></td><td><div id="imagePreview_'+counter+'" class="imagePreviewC"></div></td><td><div class="form-group field-clinicbanner-img_title required"><textarea id="clinicbanner-img_title" class="form-control" name="ClinicBanner[img_title][]"></textarea><div class="help-block"></div></div></td><td><div class="form-group field-clinicbanner-url required"><textarea id="clinicbanner-url" class="form-control" name="ClinicBanner[url][]"></textarea><div class="help-block"></div></div></td><td><div class="form-group field-clinicbanner-sort_order"><input id="clinicbanner-sort_order" class="form-control" name="ClinicBanner[sort_order][]" type="text"><div class="help-block"></div></div></td><td><button id="remove_'+counter+'" class="btn btn-danger remove-me" type="button" onclick="removeBannerBtn(this.id)">-</button></td></tr>');
	});

</script>
<style>
.contact-providers-form{padding:40px 0 40px 0;}
fieldset{border:1px solid #c0c0c0;padding:5px;}
	legend{border:1px solid #c0c0c0;border:none; width:10%;}
	.add_more, .remove_rows {
		width: 100%;
		text-align: right;
		float: right;
		font-weight: 700;
		text-transform: uppercase;
		font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		cursor: pointer;
	}
   .removeBtn > img{
		margin-top: 31px;
		width: 25px;
		cursor:pointer;
	}
	.imagePreviewC {
		width: 180px;
		height: 100px;
		background-position: center center;
		background-size: cover;
		box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3);
	    background-color: #fff;
		display: inline-block;
	}
	label.error{
		color: red;
		font-weight: normal;
	}
	.time_slot {
		float:left;
		width:100%;
		height:350px;
		overflow-y:scroll;
	}
	.time_slot > ul {
	  float:left;
	  width:100%;
	}
	.time_slot > ul > li {
	  float:left;
	  margin:5px;
	  width:19%;
	}
	.time_slot .timeTable{
		float:left;
		width:100%;
	}
	.time_slot .timeTable li {
	  background:#dfe9ee;
	  border-radius:5px;
	  float:left;
	  font-size:12px;
	  line-height:30px;
	  margin:3px;
	  padding:0;
	  text-align:center;
	  width: 44px;
	}

	.uncheckedTimeslot{
		background-color:#dfe9ee;	
	}
	.checkedTimeslot_1, .checkedTimeslot_2, .checkedTimeslot_3, .checkedTimeslot_4, .checkedTimeslot_5, .checkedTimeslot_6, .checkedTimeslot_7{
		background-color:red !important;
		color:#fff;	
	}
	.time_slot li{
		  display: inline-block;
		  margin-bottom: 5px;
		  padding:5px;
		  cursor:pointer;
		  background-color:#01c9de;
	}
	.time_slot li ul li{background-color:#da452f;}
</style>
<script>
jQuery(document).ready(function() {
    jQuery('#user-providers-form').validate({
        rules:{
            'User[email]':{
				required: true,
				email:true
			},
			'User[mobile]':{
				   required: true,
				   number:true
			}
         },

        messages:{
           
            'User[email]':{
                required: 'Please enter email.'
              
            },
            'User[mobile]':{
                required: 'Please enter mobile no.'
              
            },
        }
    });
});

function validateCantactForm(){
	var valid = 1;

	/*jQuery(".daystarttime").each(function(i){
		var start_time_arr = $(this).attr('id').split("_");
		var start_time_id = start_time_arr[2];
		var start_time_val = $("#providerstimeavailability-start_time_"+start_time_id).val();
		if(start_time_val== ""){
			$("#day_starttime_"+start_time_id).text("Please select start time #"+start_time_id).css('color','red');
			valid = 0;
		}
	});
	
	jQuery(".dayendtime").each(function(i){
		var end_time_arr = $(this).attr('id').split("_");
		var end_time_id = end_time_arr[2];
		var end_time_val = $("#providerstimeavailability-end_time_"+end_time_id).val();
		if(end_time_val == ""){
			$("#day_endtime_"+end_time_id).text("Please select end time #"+end_time_id).css('color','red');
			valid = 0;
		}
	});
	
	jQuery(".dayavailbility").each(function(i){
		var day_arr = $(this).attr("id").split("_");
		var day_id = day_arr[2];
		var dayavailability_val = $("#providersdayavailability-day_id_"+day_id).val();
		if(dayavailability_val == ""){
			$("#dayavail_"+day_id).text("Please select the day #"+day_id).css('color','red');
			valid = 0;
		}
	});*/
	
	var categoryServices = jQuery("#user-services_category_id");
	if(categoryServices.length == 0 || categoryServices.val() == ""){
		$("#user_services_category_id").text("Please select category.").css('color','red');
		valid = 0;
	}
	
	var user_fname = jQuery("#user-fname");
	if(user_fname.val() == ""){
		$("#user_fname_msg").text("Please enter first name.").css('color','red');
		valid = 0;
	}
	
	var user_lname = jQuery("#user-lname");
	if(user_lname.val() == ""){
		$("#user_lname_msg").text("Please enter last name.").css('color','red');
		valid = 0;
	}
	
	var user_email = jQuery("#user-email");
	if(user_email.val() == ""){
		$("#user_email_msg").text("Please enter email name.").css('color','red');
		valid = 0;
	}
	
	var user_mobile = jQuery("#user-mobile");
	if(user_mobile.val() == ""){
		$("#user_mobile_msg").text("Please enter mobile no.").css('color','red');
		valid = 0;
	}
	var user_qualification_id = jQuery("#user-qualification_id");
	if(user_qualification_id.length == 0 || user_qualification_id.val() == ""){
		$("#user_qualification_id_msg").text("Please select qualification/degree.").css('color','red');
		valid = 0;
	}
	
	var clinic_name = jQuery("#user-clinic_name");
	if(clinic_name.val() == ""){
		$("#user_mobile_msg").text("Please enter mobile no.").css('color','red');
		valid = 0;
	}
	
	var clinic_name = jQuery("#user-clinic_name");
	if(clinic_name.val() == ""){
		$("#user_clinic_name_msg").text("Please enter clinic name.").css('color','red');
		valid = 0;
	}
	
	var clinic_experience = jQuery("#user-experience");
	if(clinic_experience.val() == ""){
		$("#user_experience_msg").text("Please enter work experience.").css('color','red');
		valid = 0;
	}
	
	var user_fees = jQuery("#user-fees");
	if(user_fees.val() == ""){
		$("#user_fees_msg").text("Please enter fee.").css('color','red');
		valid = 0;
	}
	
	var user_state_id = jQuery("#user-state_id");
	if(user_state_id.length == 0 || user_state_id.val() == ""){
		$("#user_state_msg").text("Please select state.").css('color','red');
		valid = 0;
	}
	
	var user_city = jQuery("#user-city");
	if(user_city.val() == ""){
		$("#user_city_msg").text("Please enter city.").css('color','red');
		valid = 0;
	}
	
	
	if(valid==0){
		return false;
	}else{
		jQuery("#user-providers-form").submit();
	}	
}

function providerAvail(elementId, dayid, slotTime){
	var slotTimeval = $('#'+elementId).text();
	var slotTime = slotTime;
	var dayid = dayid;
	var attrId = elementId.split("_");
	if($('#'+elementId).hasClass('checkedTimeslot_'+dayid)){
		$('#'+elementId).addClass('uncheckedTimeslot_'+dayid).removeClass('checkedTimeslot_'+dayid).removeAttr('style');
		$('#slotTime_'+attrId[1]+'_'+attrId[2]).remove();
		$('#slots_'+attrId[1]+'_'+attrId[2]).remove();
		
	}else{
		$('#'+elementId).addClass('checkedTimeslot_'+dayid).removeClass('uncheckedTimeslot_'+dayid).css("background","red");
		$('#'+elementId).after("<input id='slotTime_"+attrId[1]+'_'+attrId[2]+"' class='slotTime' type='hidden' name='slotTime["+dayid+"][]' value='"+slotTimeval+"'><input id='slots_"+attrId[1]+'_'+attrId[2]+"' class='slots' type='hidden' name='slots[]' value='"+slotTime+"'>");
	}

}
</script>

