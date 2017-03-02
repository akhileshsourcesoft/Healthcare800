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
use common\models\HealthFacility;
use app\assets\AppAsset;;
use yii\web\Session;
$session = new Session;
$countryModel = ArrayHelper::map($countryModel, 'country_id', 'name'); 
$servicesCategory = ArrayHelper::map($servicesCategory, 'category_id', 'category_name'); 
$statelistModel = ArrayHelper::map($statelistModel, 'state_id', 'name'); 
$qualificationlistModel = ArrayHelper::map($qualificationlistModel, 'id', 'name');
$qualificationlistModel['Other'] = 'Others';
/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="container">
	<div class="row" style="padding:15px 0 30px 0">
		<ul class="nav nav-tabs" style="padding-top:30px;">
			<li class="active"><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep1')?>">Basic Details</a></li>
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep2')?>" id="tab2-link">Insurance Company</a></li>
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep3')?>" id="tab3-link">Health Facility</a></li>
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep4')?>" id="tab4-link">Upload Contract</a></li>
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/providerresetpasswordstep5')?>" id="tab5-link">Change Password</a></li>
		</ul>
		<div class="tab-content" style="padding-top:20px;">
			<!--Tab1 Strat-->
			<div id="tab1" class="tab-pane fade in active">
				<div class="col-md-12">
				<?php $form = ActiveForm::begin([
					'options'=>['enctype'=>'multipart/form-data'] // important
				]); 
				foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
					echo '<div class="alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
				}
			?>
			<div class="col-sm-6">
				<?= $form->field($model, 'fname')->textInput(['maxlength' => true])->label('First Name');?>
				<?= $form->field($model, 'user_role_id')->hiddenInput(['value'=>4])->label(false);?>
			</div>
			<div class="col-sm-6">
				<?= $form->field($model, 'lname')->textInput(['maxlength' => true])->label('Last Name');?>
			</div>
			<div class="col-sm-6">
				<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
				<?php
				if(isset($emailData) && (!empty($emailData))){
					echo '<span style="color:red; font-weight:bold;">'.$emailData.'</span>';
				}
				?>
			</div>
			<div class="col-sm-6">
				<?php if($model->isNewRecord) { ?>
					<?= $form->field($model, 'passwordhash')->passwordInput(['autocomplete'=>'off'])->label("Password"); ?>
				<?php  } else { ?>
				<?= $form->field($model, 'passwordhash')->passwordInput(['maxlength' => 255,'value'=>'password','onfocus'=>"if(this.value=='password') this.value='';",  'onblur'=>"if (this.value == '') {this.value = 'password';}"])->label("Password"); ?>
				<?php }  ?>
			</div>
			<div class="col-sm-6">
				<?php if($model->isNewRecord) { ?>
				<?= $form->field($model, 'repeatpassword')->passwordInput(['autocomplete'=>'off'])->label("Confirm Password"); ?>
				<?php  } else { ?>
				<?= $form->field($model,'repeatpassword')->passwordInput(['maxlength' => 255,'value'=>'password','onfocus'=>"if(this.value=='password') this.value='';",  'onblur'=>"if (this.value == '') {this.value = 'password';}"])->label("Confirm Password"); ?>
				<?php }  ?>
			</div>		
			<div class="col-sm-6">
				<?= $form->field($model, 'landline')->textInput(['class'=>'form-control provider_phone'])->label('Landline / Phone No.') ?>
			</div>
			<div class="col-sm-6">
				<?= $form->field($model, 'gender')->radioList(['1' => 'Male', '0' => 'Female']);?>
			</div>
			<div class="col-sm-6">
				<?= $form->field($model, 'services_category_id')->dropDownList($servicesCategory, ['prompt'=>'Select Category'])->label('Category'); ?>
			</div>
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
			<div class="col-sm-6">
				<?= $form->field($model, 'city')->textInput(['maxlength' => true])->label('City'); ?>
			</div>
			<div class="col-sm-6">
				<?= $form->field($model, 'zip_code')->textInput(['maxlength' => 5]); ?>
			</div>
				<div class="col-sm-6">
				<?= $form->field($model, 'qualification_id')->dropDownList($qualificationlistModel, ['prompt'=>'Select Qualification / Degree'])->label('Qualification / Degree'); ?>
			</div>
			<div class="col-sm-6">
				<?= $form->field($qualificationModel, 'other_qname')->textInput(['style' => 'display:none','placeholder'=>"Other Degree"])->label(false); ?>
			<span id="qual_other_msg" style="display:none;"></span>
			</div>
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
				<input type="hidden" name="step" value="2">
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
   </div>
</div><!--close row-->
<script>
	$(document).ready(function(){
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
		$(".provider_phone").mask("999-999-9999");
	});
</script>
</div>
