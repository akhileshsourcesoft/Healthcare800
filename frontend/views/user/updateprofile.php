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
						<div class="col-md-6">
							<?= $form->field($model, 'landline')->textInput()->label('Landline / Phone No.') ?>
						</div>
						<div class="col-md-6">
							<?= $form->field($model, 'address')->textarea(['maxlength' => true]) ?>
						</div>
						<div class="col-md-6">
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
						<div class="col-md-6" style="height:75px;">
						 <?= $form->field($model, 'gender')->radioList(['1' => 'Male', '0' => 'Female']);?>
						</div>
	
						<div class="form-group">
							<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
							<?= Html::a(Yii::t('app', 'Cancel'), ['/user/dashboard'], ['class' => 'btn btn-danger']) ?>
						</div>
						<?php ActiveForm::end(); ?>
				</div>
				</div>
				<div id="tab2" class="tab-pane fade">
					<div class="row">
					<div class="col-md-12" id="changePasswords"></div>
					 <div class="col-md-12">
							<?php $form = ActiveForm::begin(['id' => 'userChangePassword','options'=>['class'=>'cd-form'],'action' => ['user/userresetpassword']]);?>
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
								<button class="btn btn-success" type="button" onclick=userResetpasswords('<?php echo Yii::$app->getUrlManager()->createUrl("user/userresetpassword");?>')>Reset Password</button>
								<a class="btn btn-danger" href="<?php echo Yii::$app->getUrlManager()->createUrl('user/dashboard');?>">Cancel</a>
							</div>
							</form>
						</div>
					</div>
				</div>
		</div>
    </div><!--close row-->
 <script>
	$(document).ready(function(){
		$("#user-landline").mask("999-999-9999");
	});
 </script>
</div>
