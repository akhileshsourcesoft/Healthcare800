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
/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="container">
	<div class="row" style="padding:15px 0 30px 0">
		<ul class="nav nav-tabs" style="padding-top:30px;">
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep1')?>">Step 1</a></li>
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep2')?>" id="tab2-link">Step 2</a></li>
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep3')?>" id="tab3-link">Step 3</a></li>
			<li class="active"><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/providerresetpasswordstep4')?>" id="tab4-link">Change Password</a></li>
		</ul>
		<div class="tab-content" style="padding-top:20px;">
			<!--Tab1 Strat-->
			<div id="tab4" class="tab-pane fade in active">
					<div class="row">
					 <div class="col-md-12">
							<?php $form = ActiveForm::begin(['id' => 'providerChangePasswordstep4','options'=>['class'=>'cd-form'],'action' => ['provider/providerresetpasswordstep4']]);
							foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
								echo '<div class="alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
							}
							?>
							<div class="col-md-6">
								<div class="form-group field-user_password_hash">
									<label class="control-label" for="user_password_hash">New Password</label>
									<input id="user_password_hash" class="form-control" type="password" placeholder="Enter Your New Password" name="User[password_hash]" autocomplete="off">
								</div>
								<div id="cpasswordmsg"></div>
							</div>
							<div class="clear"></div>
							<div class="col-md-6">
								<div class="form-group field-user_repassword">
									<label class="control-label" for="user_repassword">Confirm Password</label>
									<input id="user_repassword" class="form-control" type="password" placeholder="Enter Your Confirm Password" name="User[repassword]" autocomplete="off">
								</div>
								<div id="confpasswordsmsg"></div>
							</div>
							<div class="clear"></div>
							<div class="col-md-6">
								<button class="btn btn-success" type="button" onclick=providerResetpasswordsstep4('<?php echo Yii::$app->getUrlManager()->createUrl("provider/providerresetpasswordstep4");?>')>Reset Password</button>
								<a class="btn btn-danger" href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/dashboard');?>">Cancel</a>
							</div>
							</form>
						</div>
					</div>
				</div>
			   <!--Tab4 End-->
		</div>
    </div><!--close row-->
</div>
