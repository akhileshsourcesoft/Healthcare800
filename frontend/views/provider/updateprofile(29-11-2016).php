<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\FileInput;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;
use yii\bootstrap\Alert;
use dosamigos\datepicker\DatePicker;
use common\models\User;
use common\models\ClinicBanner;
use app\assets\AppAsset;;
use yii\web\Session;

$session = new Session;

$providerfeesModel = ArrayHelper::map($providerfeesModel, 'id', 'fees'); 
/*$listDataUserRole =	ArrayHelper::map($userRoleData,'id','role_name');
$servicesCategory = ArrayHelper::map($servicesCategory, 'category_id', 'category_name'); 
$countryModel = ArrayHelper::map($countryModel, 'country_id', 'name'); 
$statelistModel = ArrayHelper::map($statelistModel, 'state_id', 'name'); 
$qualificationlistModel = ArrayHelper::map($qualificationlistModel, 'id', 'name'); 
$qualificationlistModel['Other'] = 'Others';*/


/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="container">
	<div class="row" style="padding:15px 0 30px 0">
		<ul class="nav nav-tabs" style="padding-top:30px;">
			<li class="active"><a data-toggle="tab" href="#tab1" class="tab1" id="tab1-link">Profile</a></li>
			<li><a data-toggle="tab" href="#tab2" id="tab2-link">Change Password</a></li>
		</ul>
		<div class="tab-content" style="padding-top:20px;">
				<div id="tab1" class="tab-pane fade in active">
					<div class="col-md-12">
					<?php $form = ActiveForm::begin([
						'options'=>['enctype'=>'multipart/form-data'] // important
					]); ?>
						<div class="col-md-6">
							<?= $form->field($model, 'fname')->textInput(['maxlength' => true])->label('First Name'); ?>
						</div>
						<div class="col-md-6">
							<?= $form->field($model, 'lname')->textInput(['maxlength' => true])->label('Last Name') ?>
						</div>
						<div class="col-md-6">
							<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
							<?php
								if(isset($emailData) && (!empty($emailData))){
									echo '<span style="color:red; font-weight:bold;">'.$emailData.'</span>';
								}
							?>
						</div>
						<?php if($model->isNewRecord) { ?>
							<div class="col-md-6">
								<?= $form->field($model, 'password_hash')->input('password') ?>
							</div>
							<div class="col-md-6">
								<?= $form->field($model, 'repassword')->input('password') ?>
							</div>
							<?php  } else { ?>
							<div class="col-md-6">
								<?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => 255,'value'=>'password','onfocus'=>"if(this.value=='password') this.value='';",  'onblur'=>"if (this.value == '') {this.value = 'password';}"])->label('Password') ?>
							</div>
							<div class="col-md-6">
								<?php echo $form->field($model,'repassword')->passwordInput(['maxlength' => 255,'value'=>'password','onfocus'=>"if(this.value=='password') this.value='';",  'onblur'=>"if (this.value == '') {this.value = 'password';}"]) ?>
							</div>
							<?php }  ?>
							
							<div class="col-sm-6">
								<?= $form->field($model, 'landline')->textInput(['class'=>'form-control providerPhone'])->label('Landline / Phone No.') ?>
							</div>
							<div class="col-sm-6">
							<?= $form->field($model, 'gender')->radioList(['1' => 'Male', '0' => 'Female']);?>
							</div>
							<div class="col-sm-6">
							<?= $form->field($model, 'address')->textarea(); ?>
							</div>
							<div class="col-sm-6">
								<?= $form->field($model, 'services_category_id')->dropDownList($servicesCategory, ['prompt'=>'Select Category'])->label('Category'); ?>
							</div>
							<div class="col-sm-6">
							 <?= $form->field($model, 'qualification_id')->dropDownList($qualificationlistModel, ['prompt'=>'Select Qualification / Degree'])->label('Qualification / Degree'); ?>
							</div>
							<div class="col-sm-6">
							<?= $form->field($qualificationModel, 'other_qname')->textInput(['style' => 'display:none','placeholder'=>"Other Degree"])->label(false); ?>
								<span id="qual_other_msg" style="display:none;"></span>
							</div>

							<!--<div class="col-sm-6">-->	
								<? //= $form->field($model, 'clinic_name')->textInput()->label('Clinic Name'); ?>
							<!--</div>-->
							
							<!--<div class="col-sm-6">-->	
								<? //= $form->field($model, 'fees')->textInput()->label('Fee'); ?>
							<!--</div>-->
							
							<div class="col-sm-6">	
								<?= $form->field($model, 'experience')->textInput()->label('Work Experience'); ?>
							</div>
							<div class="col-sm-6">
							<?= $form->field($model, 'country_id')->dropDownList($countryModel, ['prompt'=>'Select Country', 'onchange'=>
								'$.post("'.Yii::$app->urlManager->createUrl('user/statelist?id=').'"+$(this).val(), function(res){
									 $( "select#user-state_id" ).html( res );
								});
							'])->label('Country'); ?>
							</div>
							<div class="col-sm-6">
							<?php
								 if($model->isNewRecord){
									echo $form->field($model, 'state_id')->dropDownList($statelistModel,['prompt'=>'Select State'])->label('State');
								 }else{
									 echo $form->field($model, 'state_id')->dropDownList($statelistModel,['prompt'=>'Select State'])->label('State');
								 } 
							?>
							</div>
							<!--<div class="col-sm-6">-->
								<?php //echo $form->field($model, 'state_id')->dropDownList($statelistModel,['prompt'=>'Select State'])->label('State');?>
							<!--</div>-->
							<div class="col-sm-6">
								<?= $form->field($model, 'city')->textInput(['maxlength' => true])->label('City'); ?>
							</div>
							<div class="col-sm-6">
							<?= $form->field($model, 'zip_code')->textInput(['maxlength' => 5]); ?>
							</div>							
							<?php foreach($userpricetypeModel as $userVal){ ?>
							<div class="col-sm-6">	
							<?php echo $form->field($model, 'state_id')->dropDownList($providerfeesModel,['prompt'=>'Select one'])->label($userVal['name']); ?>
							</div>
							<?php } ?>
							<div class="col-sm-12">
							<?= $form->field($model, 'short_desc')->textarea(['rows' => 5])->label('Description'); ?>
							</div>

							<div class="col-sm-12">
							<?php 
							if(isset($model->profile_image) && (!empty($model->profile_image))){
								echo Html::img(Yii::$app->getUrlManager()->createUrl(['uploads']).'/' . $model->profile_image, ['width'=>'100'], [
								'class'=>'img-thumbnail']);
								echo $form->field($model, 'profile_image')->fileInput();
							}else{
								echo $form->field($model, 'profile_image')->fileInput();
							}
							?>
							</div>
							<div class="col-sm-12">
						<div class="form-group">
							<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
							<?= Html::a(Yii::t('app', 'Cancel'), ['/provider/dashboard'], ['class' => 'btn btn-danger']) ?>
						</div>
						</div>
						<?php ActiveForm::end(); ?>
				</div>
				</div>
				<div id="tab2" class="tab-pane fade">
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
    </div><!--close row-->
 <script>
	$(document).ready(function(){
		$(".providerPhone").mask("999-999-9999");
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
</div>
