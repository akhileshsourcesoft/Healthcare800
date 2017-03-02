<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\FileInput;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;
use dosamigos\datepicker\DatePicker;
use common\models\Dayname;
use common\models\ClinicBanner;
use app\assets\AppAsset;;
use yii\web\Session;
$session = new Session;
use common\models\ProvidersDayAvailability;
use common\models\User;
use common\models\ProvidersTimeAvailability;
use yii\db\Query;

$date = new DateTime('now');
$date->modify('last day of this month');
$lastMonthdate = $date->format('Y-m-d');
$currentDate = date("Y-m-d");

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="container">
	<div class="row" style="padding:15px 0 30px 0">
		<div class="contact-providers-form">
		
		<ul class="nav nav-tabs" style="padding-top:30px;">
			<li class="active"><a data-toggle="tab" href="#tab8" class="tab8" id="tab8-link">Availability</a></li>
			<li><a data-toggle="tab" href="#tab9" class="tab9" id="tab9-link">Clinic Banners</a></li>
			<li><a data-toggle="tab" href="#tab10" class="tab10" id="tab10-link">Profile</a></li>
			<li><a data-toggle="tab" href="#tab11" id="tab11-link">Change Password</a></li>
		</ul>
		<div class="tab-content" style="padding-top:20px;">
				<div id="tab8" class="tab-pane fade in active">
				<?php $form = ActiveForm::begin([
					'options' => ['enctype'=>'multipart/form-data'],
					'id' => 'user-providers-form'
				]); ?>	
		  <fieldset>
			  <?php
				foreach(@Yii::$app->session->getAllFlashes() as $key => $message) {
					echo '<div class="alert alert-' . $key . '">' . $message . '<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button></div>';
				}
			?>
			<legend>Availability:</legend>
			<div class="time_slot">
				<?php
				if(!$model->isNewRecord){
				  $weekdaysName = array();
				  foreach($daylistModel as $weekdayName){ 
					$weekdaysName[] = $weekdayName['day_name'];
				  }
				
				  for($m=$currentDate; $m<=$lastMonthdate; $m++){
					 $monthName = explode("-",$m);
					 $day_name = date("l", mktime(0,0,0,$monthName[1],$monthName[2],$monthName[0]));
					 $dayData = Dayname::find()->where(['status'=>1])->andWhere(['day_name'=>$day_name])->one();
					 
					 $providerDayAvail = ProvidersDayAvailability::find()->where(['provider_id'=>$model->id])->andWhere(['slot_date'=>$m])->one();
					
					 $query = new Query;
					 $query->select('*')
						->from('hc_providers_time_availability')
						->where(['day_availability_id'=>$providerDayAvail['id']])->all();
					 $providerTimeAvail = $query->createCommand()->queryAll();
					 $start_time = array();
					 $timeAvailId = array();
					 foreach($providerTimeAvail as $providerVal){
						$start_time[] = $providerVal['start_time'];
						$timeAvailId[] = $providerVal['id'];
					 }
					
				 ?>
					<ul>
						<li>
							<?php if(in_array($day_name, $weekdaysName)){ echo $day_name; } ?>
							<input type="hidden" name="dayId_<?php echo $m;?>" id="dayId_<?php echo $m;?>" value="<?php echo $dayData->id;?>">
						</li>
						<li>Morning Time
							<ul class="timeTable">
							<?php
								switch($dayData->id){
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
							
							 $intTime = 15;
							 for($i=9; $i<=11; $i++){
								 for($j=1; $j<=4; $j++){ 
									if($j==1){
										$intValue = $i.':'.'00';	
									}else{
										$intValue = $i.':'.($j-1)*$intTime;	
									}
									
								if(@in_array($intValue, $start_time)){
									$checked = 'checkedTimeslot';
								}else{
									$checked = 'uncheckedTimeslot';
								} 
								?>
								<?php
								 if(@in_array($intValue, $start_time)){ 
									  $providerDayAvail = ProvidersDayAvailability::find()->where(['provider_id'=>$model->id])->andWhere(['slot_date'=>$m])->one();								  
									  $timeAvail = ProvidersTimeAvailability::find()->where(['day_availability_id'=>$providerDayAvail['id']])->andWhere(['start_time'=>$intValue])->one();
								?>
									<li style="background-color:red !important; color:#fff;"class="<?php echo $checked.'_'.$m.' '.$className?>" id="<?php echo $className.'_'.$i.'_'.$j.'_'.$timeAvail->id;?>" onclick="providerupAvail(this.id,'<?php echo $m;?>','M',<?php echo $timeAvail->id;?>)"><?php echo $intValue;?>
									<input id="slotTimeUpd_<?php echo $timeAvail->id;?>" class="slotTimeUpd" type="hidden" value="<?php echo $intValue;?>" name="slotTimeUpd_<?php echo $timeAvail->id;?>">
									<input id="slotsUpd_<?php echo $timeAvail->id;?>" class="slotsUpd" type="hidden" value="M" name="slotsUpd_<?php echo $timeAvail->id;?>">
									<input id="updateAvailability_<?php echo $timeAvail->id;?>" type="hidden" value="<?php echo $timeAvail->id;?>" name="updateAvailability[<?php echo $m;?>][]"></li>
								<?php }else{ ?>
									<li class="<?php echo $checked.'_'.$m.' '.$className?>" id="<?php echo $className.'_'.$i.'_'.$j.'_'.$m;?>" onclick="providerAvail(this.id,'<?php echo $m;?>','M')"><?php echo $intValue;?></li>								
								<?php } ?>
							<?php	} 
								} 
							?>
						
							</ul>
						</li>
						<li>After Noon Time
							<ul class="timeTable">
							<?php
							 $intTime = 15;
							 for($i=12; $i<=15; $i++){
								 for($j=1; $j<=4; $j++){
									if($j==1){
										$intValue = $i.':'.'00';	
									}else{
										$intValue = $i.':'.($j-1)*$intTime;	
									}
								if(@in_array($intValue, $start_time)){
									$checked = 'checkedTimeslot';
								}else{
									$checked = 'uncheckedTimeslot';
								} 
									 
							 ?>
								<?php
								 if(@in_array($intValue, $start_time)){ 
									
									  $providerDayAvail = ProvidersDayAvailability::find()->where(['provider_id'=>$model->id])->andWhere(['slot_date'=>$m])->one();								  
									  $timeAvail = ProvidersTimeAvailability::find()->where(['day_availability_id'=>$providerDayAvail['id']])->andWhere(['start_time'=>$intValue])->one();
								?>
									<li style="background-color:red !important; color:#fff;" class="<?php echo $checked.'_'.$m.' '.$className?>" id="<?php echo $className.'_'.$i.'_'.$j.'_'.$timeAvail->id;?>" onclick="providerupAvail(this.id,'<?php echo $m;?>','A',<?php echo $timeAvail->id;?>)"><?php echo $intValue;?>
									<input id="slotTimeUpd_<?php echo $timeAvail->id;?>" class="slotTimeUpd" type="hidden" value="<?php echo $intValue;?>" name="slotTimeUpd_<?php echo $timeAvail->id;?>">
									<input id="slotsUpd_<?php echo $timeAvail->id;?>" class="slotsUpd" type="hidden" value="A" name="slotsUpd_<?php echo $timeAvail->id;?>">
									<input id="updateAvailability_<?php echo $timeAvail->id;?>" type="hidden" value="<?php echo $timeAvail->id;?>" name="updateAvailability[<?php echo $m;?>][]"></li>
								<?php }else{ ?>
									<li class="<?php echo $checked.'_'.$m.' '.$className?>" id="<?php echo $className.'_'.$i.'_'.$j.'_'.$m;?>" onclick="providerAvail(this.id,'<?php echo $m;?>','A')"><?php echo $intValue;?></li>
								<?php } ?>
							<?php } 
							}
						?>
						
							</ul>
						</li>
						<li>Evening Time
						<ul class="timeTable">
							<?php
							 $intTime = 15;
							 for($i=16; $i<=19; $i++){
								 for($j=1; $j<=4; $j++){
									if($j==1){
										$intValue = $i.':'.'00';	
									}else{
										$intValue = $i.':'.($j-1)*$intTime;	
									}
								if(@in_array($intValue, $start_time)){
									$checked = 'checkedTimeslot';
								}else{
									$checked = 'uncheckedTimeslot';
								} 	 
							 ?>
								<?php
								 if(@in_array($intValue, $start_time)){ 
									
									  $providerDayAvail = ProvidersDayAvailability::find()->where(['provider_id'=>$model->id])->andWhere(['slot_date'=>$m])->one();								  
									  $timeAvail = ProvidersTimeAvailability::find()->where(['day_availability_id'=>$providerDayAvail['id']])->andWhere(['start_time'=>$intValue])->one();
								?>
									<li style="background-color:red !important; color:#fff;" class="<?php echo $checked.'_'.$m.' '.$className?>" id="<?php echo $className.'_'.$i.'_'.$j.'_'.$timeAvail->id;?>" onclick="providerupAvail(this.id,'<?php echo $m;?>','E',<?php echo $timeAvail->id;?>)"><?php echo $intValue;?>
									<input id="slotTimeUpd_<?php echo $timeAvail->id;?>" class="slotTimeUpd" type="hidden" value="<?php echo $intValue;?>" name="slotTimeUpd_<?php echo $timeAvail->id;?>">
									<input id="slotsUpd_<?php echo $timeAvail->id;?>" class="slotsUpd" type="hidden" value="E" name="slotsUpd_<?php echo $timeAvail->id;?>">
									<input id="updateAvailability_<?php echo $timeAvail->id;?>" type="hidden" value="<?php echo $timeAvail->id;?>" name="updateAvailability[<?php echo $m;?>][]"></li>
								<?php }else{ ?>
									<li class="<?php echo $checked.'_'.$m.' '.$className?>" id="<?php echo $className.'_'.$i.'_'.$j.'_'.$m;?>" onclick="providerAvail(this.id,'<?php echo $m;?>','E')"><?php echo $intValue;?></li>
								<?php } ?>
							<?php } 
							}
						?>
						
							</ul>
						</li>
						<li>Night Time
						<ul class="timeTable">
							<?php
							 $intTime = 15;
							 for($i=20; $i<=23; $i++){
								 for($j=1; $j<=4; $j++){
									if($j==1){
										$intValue = $i.':'.'00';	
									}else{
										$intValue = $i.':'.($j-1)*$intTime;	
									}
								if(@in_array($intValue, $start_time)){
									$checked = 'checkedTimeslot';
								}else{
									$checked = 'uncheckedTimeslot';
								} 
							 ?>
								<?php
								 if(@in_array($intValue, $start_time)){ 
									
									  $providerDayAvail = ProvidersDayAvailability::find()->where(['provider_id'=>$model->id])->andWhere(['slot_date'=>$m])->one();								  
									  $timeAvail = ProvidersTimeAvailability::find()->where(['day_availability_id'=>$providerDayAvail['id']])->andWhere(['start_time'=>$intValue])->one();
								?>
									<li style="background-color:red !important; color:#fff;" class="<?php echo $checked.'_'.$m.' '.$className?>" id="<?php echo $className.'_'.$i.'_'.$j.'_'.$timeAvail->id;?>" onclick="providerupAvail(this.id,'<?php echo $m;?>','E',<?php echo $timeAvail->id;?>)"><?php echo $intValue;?>
									<input id="slotTimeUpd_<?php echo $timeAvail->id;?>" class="slotTimeUpd" type="hidden" value="<?php echo $intValue;?>" name="slotTimeUpd_<?php echo $timeAvail->id;?>">
									<input id="slotsUpd_<?php echo $timeAvail->id;?>" class="slotsUpd" type="hidden" value="N" name="slotsUpd_<?php echo $timeAvail->id;?>">
									<input id="updateAvailability_<?php echo $timeAvail->id;?>" type="hidden" value="<?php echo $timeAvail->id;?>" name="updateAvailability[<?php echo $m;?>][]"></li>
								<?php }else{ ?>
									<li class="<?php echo $checked.'_'.$m.' '.$className?>" id="<?php echo $className.'_'.$i.'_'.$j.'_'.$m;?>" onclick="providerAvail(this.id,'<?php echo $m;?>','N')"><?php echo $intValue;?></li>
								<?php } ?>
							<?php } 
								}
							?>
							</ul>
						</li>
					</ul>
					<?php } ?>		
			<?php } ?>
	     	</div>
			</fieldset>
				<div class="form-group" style="margin-top:20px;">
					<?= Html::submitButton($model->isNewRecord ? 'Register' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
					 <?= Html::a('Cancel', ['/'], ['class'=>'btn btn-danger']) ?>
				</div>
				<?php ActiveForm::end(); ?>
				</div>
				<div id="tab9" class="tab-pane fade">
					<?php $form = ActiveForm::begin([
						'options' => ['enctype'=>'multipart/form-data'],
						'id' => 'user-providerscalender-form'
					]); ?>	
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
								echo Html::img(Yii::$app->urlManager->createUrl(['uploads']).'/' . $clinicVal->images, ['width'=>'70'], [
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
							<?= $form->field($clinicBannerModel, 'img_title')->textArea(['name'=>'ClinicBanner[img_title][]', 'id'=>'Clinicbanner-img_title_1', 'class'=>'form-control clinicbanner_imgtitle'])->label(false) ?>
						</td>
						<td>
							<?= $form->field($clinicBannerModel, 'url')->textArea(['name'=>'ClinicBanner[url][]', 'id'=>'Clinicbanner-url_1', 'class'=>'form-control clinicbanner_url'])->label(false) ?>
						</td>
						<td>
							<?= $form->field($clinicBannerModel, 'sort_order')->textInput(['name'=>'ClinicBanner[sort_order][]', 'id'=>'Clinicbanner-sort_order_1', 'class'=>'form-control clinicbanner_sortorder'])->label(false) ?>
						</td>							
						<td>
							<button id="add_row_1" class="btn add-more btn btn-success" type="button">+</button>
							<button id="remove_1" class="btn btn-danger remove-me" type="button" style="display:none;" onclick="removeBannerBtn(this.id)">-</button>
						</td>			
					</tr>
					<?php } ?>
				</table>
				<div class="form-group" style="margin-top:20px;">
					<?= Html::submitButton($model->isNewRecord ? 'Register' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
					 <?= Html::a('Cancel', ['/'], ['class'=>'btn btn-danger']) ?>
				</div>
				<?php ActiveForm::end(); ?>		
				</div>
				<div id="tab10" class="tab-pane fade">
					 <?php if(isset($userData) && !empty($userData)){
						foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
							echo '<div class="alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
						}
					  ?>
						 <div class="col-md-12" style="text-align: right">
							<input type="hidden" id="userid" value="<?php echo $userData->user_role_id;?>">
							<span id="editprofile">
								<a href="<?php echo Yii::$app->homeUrl;?>provider/updateprofile"><button  name="Edit Profile" class="btn btn-success">Edit Profile</button></a>
							</span>									
						</div>
						<div class="col-md-12"><div class="profileshow">
							<div class="col-md-9">
								<div class="well">		   							
								<div class="col-md-3">Name </div><div class="col-md-8"><b><?php echo $userData->fname.' '.$userData->lname;?></b></div><br />
								<div class="col-md-3">Email </div><div class="col-md-8"><b><?php echo $userData->email;?></b></div><br />
								<div class="col-md-3">Gender </div><div class="col-md-8"><b>
								<?php if($userData->gender) { echo 'Male'; } else { echo 'Female'; } ?>
								</b></div><br />									
								<div class="col-md-3">Landline / Phone No. </div><div class="col-md-8"><b><?php echo $userData->landline;?></b></div><br />
								<div class="col-md-3">Address </div><div class="col-md-8"><b><?php echo $userData->address;?></b></div><br />
								<div class="col-md-3">Category </div><div class="col-md-8"><b><?php echo $userData->servicesCategory->category_name;?></b></div><br />
								<div class="col-md-3">Qualification / Degree </div><div class="col-md-8"><b><?php echo $userData->qualification->name;?></b></div><br />
								<div class="col-md-3">Clinic Name </div><div class="col-md-8"><b><?php echo $userData->clinic_name;?></b></div><br />
								<div class="col-md-3">Work Experience </div><div class="col-md-8"><b><?php echo $userData->experience;?></b></div><br />
								<div class="col-md-3">Fee </div><div class="col-md-8"><b><?php echo $userData->fees;?></b></div><br />
								<div class="col-md-3">State </div><div class="col-md-8"><b><?php echo $userData->state->name;?></b></div><br />
								<div class="col-md-3">City </div><div class="col-md-8"><b><?php echo $userData->city;?></b></div><br />
								<div class="col-md-3">Zip Code </div><div class="col-md-8"><b><?php echo $userData->zip_code;?></b></div><br />
								<div class="short_description" style="min-height:135px;">
									<div class="col-md-3">Description </div><div class="col-md-8"><b><?php echo $userData->short_desc;?></b></div><br />
								</div>
							
								</div></div>
								<div class="col-md-3"><div class="col-md-6">
									<?php if(isset($userData->profile_image) && (!empty($userData->profile_image))){ ?>
										<img src="<?php echo Yii::$app->getUrlManager()->createUrl(['uploads']).'/'.$userData->profile_image;?>" width="100px">
									<?php }else{ ?>
										<img src="<?php echo Yii::$app->getUrlManager()->createUrl(['uploads']).'/provider/user_profile/default.png';?>" width="100px">
									<?php } ?>
										Profile Image </div>
								</div>
							</div>
						</div>
					 <?php } ?>
				</div>
				<div id="tab11" class="tab-pane fade">
					<div class="row">
					<div class="col-md-12" id="providerchangePasswords"></div>
					 <div class="col-md-12">
							<?php $form = ActiveForm::begin(['id' => 'providerChangePassword','options'=>['class'=>'cd-form'],'action' => ['provider/providerresetpassword']]);?>
							<div class="col-md-6">
								<div class="form-group field-user_password_hash">
									<label class="control-label" for="user_password_hash">New Password</label>
									<input id="user_password_hash" class="form-control" type="password" placeholder="Enter Your New Password" name="User[password_hash]">
								</div>
								<div id="cpasswordmsg"></div>
							</div>
							<div class="clear"></div>
							<div class="col-md-6">
								<div class="form-group field-user_repassword">
									<label class="control-label" for="user_repassword">Confirm Password</label>
									<input id="user_repassword" class="form-control" type="password" placeholder="Enter Your Confirm Password" name="User[repassword]">
								</div>
								<div id="confpasswordsmsg"></div>
							</div>
							<div class="clear"></div>
							<div class="col-md-6">
								<button class="btn btn-success" type="button" onclick=providerResetpasswords('<?php echo Yii::$app->getUrlManager()->createUrl("provider/providerresetpassword");?>')>Reset Password</button>
								<a class="btn btn-danger" href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/dashboard');?>">Cancel</a>
							</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div><!--close row-->
</div>
<script>
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
	    
	    jQuery("#addrow_"+trNum).find(".clinicbanner_imgtitle").prop('id','ClinicBanner_title_'+trNum).val('');
	    jQuery("#addrow_"+trNum).find(".clinicbanner_url").prop('id','Clinicbanner_url_'+trNum).val('');
	    jQuery("#addrow_"+trNum).find(".clinicbanner_sortorder").prop('id','Clinicbanner-sort_order_'+trNum).val('');
	    
	    
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

function providerAvail(elementId, slotdate, slotTime){
	var slotTimeval = $('#'+elementId).text();
	var slotTime = slotTime;
	var slotdate = slotdate;
	var attrId = elementId.split("_");
	if($('#'+elementId).hasClass('checkedTimeslot_'+slotdate)){
		$('#'+elementId).addClass('uncheckedTimeslot_'+slotdate).removeClass('checkedTimeslot_'+slotdate).removeAttr('style');
		$('#slotTime_'+attrId[1]+'_'+attrId[2]+'_'+attrId[3]).remove();
		$('#slots_'+attrId[1]+'_'+attrId[2]+'_'+attrId[3]).remove();
		
	}else{
		$('#'+elementId).addClass('checkedTimeslot_'+slotdate).removeClass('uncheckedTimeslot_'+slotdate).css({"background":"red", "color":"#fff"});
		$('#'+elementId).after("<input id='slotTime_"+attrId[1]+'_'+attrId[2]+'_'+attrId[3]+"' class='slotTime' type='hidden' name='slotTime["+slotdate+"][]' value='"+slotTimeval+"'><input id='slots_"+attrId[1]+'_'+attrId[2]+'_'+attrId[3]+"' class='slots' type='hidden' name='slots[]' value='"+slotTime+"'>");
	}

}

function providerupAvail(elementId, slotdate, slotTime, slotid){
	var slotTimeval = $('#'+elementId).text();
	var slotTime = slotTime;
	var slotid = slotid;
	var attrId = elementId.split("_");

	if($('#'+elementId).hasClass('checkedTimeslot_'+slotdate)){
		$('#'+elementId).addClass('uncheckedTimeslot_'+slotdate).removeClass('checkedTimeslot_'+slotdate).removeAttr('style');
		$('#slotTimeUpd_'+slotid).remove();
		$('#slotsUpd_'+slotid).remove();
		$('#updateAvailability_'+slotid).remove();
	}else{
		$('#'+elementId).addClass('checkedTimeslot_'+slotdate).removeClass('uncheckedTimeslot_'+slotdate).css({"background":"red", "color":"#fff"});
		$('#'+elementId).after("<input id='slotTimeUpd_"+slotid+"' class='slotTimeUpd' type='hidden' name='slotTimeUpd_"+slotid+"' value='"+slotTimeval+"'><input id='slotsUpd_"+slotid+"' class='slotsUpd' type='hidden' name='slotsUpd_"+slotid+"' value='"+slotTime+"'><input id='updateAvailability_"+slotid+"' type='hidden' name='updateAvailability["+slotdate+"][]' value='"+slotid+"'>");
	}
}
$(document).ready(function(){
	$(".provider_phone").mask("999-999-9999");
	$("#user-qualification_id").change(function(){
		var qValue = $(this).val();	
		if(qValue=='Other'){
			$("#qualification-other_qname").show();
			$("#qual_other_msg").show();
		}else{
			$("#qualification-other_qname").hide();
			$("#qual_other_msg").hide();
		}
	});
});
</script>

