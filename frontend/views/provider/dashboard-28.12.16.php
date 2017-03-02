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
use yii\grid\GridView;
use app\assets\AppAsset;;
use yii\web\Session;
$session = new Session;
use common\models\ProvidersDayAvailability;
use common\models\User;
use common\models\ProvidersTimeAvailability;
use yii\db\Query;
use yii\helpers\Url;

$date = new DateTime('now');
$date->modify('last day of this month');
$lastMonthdate = $date->format('Y-m-d');
$currentDate = date("Y-m-d");

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
$urlData = array();
$searchUrl = '';
$searchActive = '';
$sorting = isset($_GET['sort'])? $sorting = 'in active': $sorting = '';
$paging = isset($_GET['page'])? $paging = 'in active': $paging = '';
$userName = Url::current();
$urlData = explode('&',$userName);
if(!empty($urlData[1])){
	$searchUrl = urldecode($urlData[1]);
	if(!empty($searchUrl)){
		$searchActive = 'in active';	
	}
}
?>
<div class="container">
	<div class="row" style="padding:15px 0 30px 0">
		<div class="contact-providers-form">
		
		<ul class="nav nav-tabs" style="padding-top:30px;">
			<li <?php if($usermodel->tabname=='tab8'){ echo 'class="active"';}else if(empty($usermodel->tabname) && empty($sorting) 
			&& empty($searchActive) && empty($paging)){ echo 'class="active"';}?>><a data-toggle="tab" href="#tab8" class="tab8" id="tab8-link">Availability</a></li>
			<li <?php if($usermodel->tabname=='tab9'){ echo 'class="active"';}?>><a data-toggle="tab" href="#tab9" class="tab9" id="tab9-link">Clinic Banners</a></li>
			<li><a data-toggle="tab" href="#tab10" class="tab10" id="tab10-link">Profile</a></li>
			<li><a data-toggle="tab" href="#tab11" id="tab11-link">Change Password</a></li>
			<li <?php if(!empty($sorting) || (!empty($searchActive)) || (!empty($paging))){ echo 'class="active"';}?>><a data-toggle="tab" href="#tab13" id="tab13-link">My Patient</a></li>
		</ul>
		<div class="tab-content" style="padding-top:20px;">
				<div id="tab8" class="tab-pane fade <?php if($usermodel->tabname=='tab8'){ echo 'in active';}else if(empty($usermodel->tabname) && empty($sorting) && (empty($searchActive)) && (empty($paging))){ echo 'in active';}?>">
				<?php $form = ActiveForm::begin([
					'options' => ['enctype'=>'multipart/form-data'],
					'id' => 'user-providers-form'
				]); ?>	
		  <fieldset>
			 <?php
			  if($usermodel->tabname=='tab8'){
				foreach(@Yii::$app->session->getAllFlashes() as $key => $message) {
					echo '<div class="alert alert-' . $key . '">' . $message . '<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button></div>';
				}
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
			<?php echo $form->field($usermodel, 'tabname')->hiddenInput(['value'=>'tab8'])->label(false);?>
	     	</div>
			</fieldset>
				<div class="form-group" style="margin-top:20px;">
					<?= Html::submitButton($model->isNewRecord ? 'Register' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
					 <?= Html::a('Cancel', ['/'], ['class'=>'btn btn-danger']) ?>
				</div>
				<?php ActiveForm::end(); ?>
				</div>
				<div id="tab9" class="tab-pane fade <?php if($usermodel->tabname=='tab9'){ echo 'in active';}?>">
					<?php $form = ActiveForm::begin([
						'options' => ['enctype'=>'multipart/form-data'],
						'id' => 'user-providersbanner-form'
					]); ?>	
					 <table class="table table-bordered table-hover" id="container">
					<?php
					 if($usermodel->tabname=='tab9'){
						foreach(@Yii::$app->session->getAllFlashes() as $key => $message) {
							echo '<div class="alert alert-' . $key . '">' . $message . '<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button></div>';
						}
					}
					?>
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
							<button id="updateAddrow" onclick="updateAddrows()" class="btn add-more btn btn-success" type="button" <?php if($i!=1){ echo 'style="display:none"';}?>>+</button>
							<a class="btn btn-danger" href="javascript:void(0);" onclick="removeUpdatebtn(<?php echo $clinicVal->id;?>)">x</a>
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
					<?php echo $form->field($usermodel, 'tabname')->hiddenInput(['value'=>'tab9'])->label(false);?>
				</table>
				<div class="form-group" style="margin-top:20px;">
					<?= Html::submitButton($model->isNewRecord ? 'Register' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
					 <?= Html::a('Cancel', ['/provider/dashboard'], ['class'=>'btn btn-danger']) ?>
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
								<a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep1');?>"><button  name="Edit Profile" class="btn btn-success">Edit Profile</button></a>
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
								<?php /* ?><div class="col-md-3">Clinic Name </div><div class="col-md-8"><b><?php echo $userData->clinic_name;?></b></div><br /><?php */ ?>
								<div class="col-md-3">Work Experience </div><div class="col-md-8"><b><?php echo $userData->experience;?></b></div><br />
								<?php /* ?><div class="col-md-3">Fee </div><div class="col-md-8"><b><?php echo $userData->fees;?></b></div><br /><?php */ ?>
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
										<img src="<?php echo Yii::$app->getUrlManager()->createUrl(['uploads']).'/users/providers/default.png';?>" width="100px">
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

					<div id="tab13" class="tab-pane fade <?php if(!empty($sorting) || (!empty($searchActive)) || (!empty($paging))){ echo 'in active';}?>">
					<div class="row">
						<div class="col-md-12" id="userTimeslot">
							<div class="user-timeslot-booking-index">
								<p>
									<?= Html::a('Reset Filter', ['/user/dashboard'], ['class'=>'btn btn-danger']) ?>
								</p>
								<div class="clearfix"></div>
								<input type="hidden" name="UserTimeslotBooking[t]" id="UserTimeslotBooking[t]" value="tab12">
								<?= GridView::widget([
									'dataProvider' => $dataProvider,
									'filterModel' => $searchModel,
									'columns' => [
										['class' => 'yii\grid\SerialColumn'],
										[
											'attribute'=>'userName',
											'filter' => true,
											'content'=>function($data){
												return $data->user->fname.' '.$data->user->lname;	
											}
										],
										'fullname',
										'email:email',
										'phone_no',
										'booking_date',
										'booking_time',
										[
											'attribute'=>'payment_method',
											'filter' => array('1'=>'Insurance', '2'=>'Non Insurance'),
											'content'=>function($data){
												return $data->payment_method==1 ? 'Insurance' : 'Non Insurance';	
											}
										],
										[
											'attribute'=>'bookingStatusId',
											'filter' => true,
											'content' => function($data){
												return $data->bookingStatus->name;	
											}
										],
									],
								]); ?>
							</div>
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
	

	function updateAddrows(){
		counter = counter+1;
		var $tr = $('tr[class^="bannerRows"]:last');
		var $tr_id_arr = $tr.prop("id").split("_");
	    var id = $tr_id_arr[1];
	    var counter = parseInt(id)+1
			
			$tr.after('<tr id="addrow_'+counter+'" class="bannerRows"><td><div class="form-group field-clinicbanner-images required"><input id="ClinicBanner_images_'+counter+'" class="clinic_banner_image" name="ClinicBanner[images][]" type="file"></div></td><td><div id="imagePreview_'+counter+'" class="imagePreviewC"></div></td><td><div class="form-group field-clinicbanner-image_title required"><textarea id="clinicbanner-image_title_'+counter+'" class="form-control" name="ClinicBanner[image_title][]"></textarea><div class="help-block"></div></div></td><td><div class="form-group field-clinicbanner-url required"><textarea id="clinicbanner-urls_'+counter+'" class="form-control" name="ClinicBanner[urls][]"></textarea><div class="help-block"></div></div></td><td><div class="form-group field-clinicbanner-sort_orders"><input id="clinicbanner-sort_orders_'+counter+'" class="form-control" name="ClinicBanner[sort_orders][]" type="text"><div class="help-block"></div></div></td><td><button id="remove_'+counter+'" class="btn btn-danger remove-me" type="button" onclick="removeBannerBtn(this.id)">-</button></td></tr>');
			
		var t = counter;
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
		
	}
	var counter = 0;
	function removeUpdatebtn(id){
		var totalLength;
		jQuery(".bannerRows").each(function(index,value){
			totalLength = jQuery(".bannerRows").length;
			
		});
		if(totalLength==1){
			counter = counter+1;
			var $tr = $('tr[class^="bannerRows"]:last');
			$tr.after('<tr id="addrow_'+counter+'" class="bannerRows"><td><div class="form-group field-clinicbanner-images required"><input id="ClinicBanner_images_'+counter+'" class="clinic_banner_image" name="ClinicBanner[images][]" type="file"></div><div class="form-group field-clinicbanner-hidden"><input id="clinicbanner-hidden" class="form-control" type="hidden" value="" name="ClinicBanner[hiddenid][]"></div></td><td><div id="imagePreview_'+counter+'" class="imagePreviewC"></div></td><td><div class="form-group field-clinicbanner-image_title required"><textarea id="clinicbanner-image_title_'+counter+'" class="form-control" name="ClinicBanner[image_title][]"></textarea><div class="help-block"></div></div></td><td><div class="form-group field-clinicbanner-url required"><textarea id="clinicbanner-urls_'+counter+'" class="form-control" name="ClinicBanner[urls][]"></textarea><div class="help-block"></div></div></td><td><div class="form-group field-clinicbanner-sort_orders"><input id="clinicbanner-sort_orders_'+counter+'" class="form-control" name="ClinicBanner[sort_orders][]" type="text"><div class="help-block"></div></div></td><td><button type="button" class="btn add-more btn btn-success" id="updateAddrow" onclick="addRemoveUpdatebtn()">+</button> <a onclick="removeUpdatebtn('+counter+')" href="javascript:void(0);" class="btn btn-danger">x</a></td></tr>');
			
		  var t = counter;
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
			
		}
		jQuery("#addrow_"+id).remove();
	}
	
	var addcounter=1;
	function addRemoveUpdatebtn(){
		
		addcounter = addcounter+1;
			var $tr = $('tr[class^="bannerRows"]:last');
			$tr.after('<tr id="addrow_'+addcounter+'" class="bannerRows"><td><div class="form-group field-clinicbanner-images required"><input id="ClinicBanner_images_'+addcounter+'" class="clinic_banner_image" name="ClinicBanner[images][]" type="file"></div></td><td><div id="imagePreview_'+addcounter+'" class="imagePreviewC"></div></td><td><div class="form-group field-clinicbanner-image_title required"><textarea id="clinicbanner-image_title_'+addcounter+'" class="form-control" name="ClinicBanner[image_title][]"></textarea><div class="help-block"></div></div></td><td><div class="form-group field-clinicbanner-url required"><textarea id="clinicbanner-urls_'+addcounter+'" class="form-control" name="ClinicBanner[urls][]"></textarea><div class="help-block"></div></div></td><td><div class="form-group field-clinicbanner-sort_orders"><input id="clinicbanner-sort_orders_'+addcounter+'" class="form-control" name="ClinicBanner[sort_orders][]" type="text"><div class="help-block"></div></div></td><td><button id="remove_'+addcounter+'" class="btn btn-danger remove-me" type="button" onclick="removeBannerBtn(this.id)">-</button></td></tr>');
			
		var t = addcounter;
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
		
	}
	
	function removeBannerBtn(id){
		var imgId = id.split("_");
		jQuery("#addrow_"+imgId[1]).remove();
	}
	

</script>
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

