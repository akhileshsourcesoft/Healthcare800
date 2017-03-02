<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\FileInput;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;
use dosamigos\datepicker\DatePicker;
use common\models\Dayname;
use common\models\ClinicBanner;
use app\assets\AppAsset;
use yii\web\Session;
use yii\helpers\Url;
$session = new Session;

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
		<ul class="nav nav-tabs" style="padding-top:30px;">
			<li <?php if($model->tabname=='tab8'){ echo 'class="active"';}else if(empty($model->tabname) && empty($sorting) 
			&& empty($searchActive) && empty($paging)){ echo 'class="active"';}?>><a data-toggle="tab" href="#tab8" class="tab8" id="tab8-link">Profile</a></li>
			<li <?php if($model->tabname=='tab9'){ echo 'class="active"';}?>><a data-toggle="tab" href="#tab9" id="tab9-link">Change Password</a></li>
			<li <?php if(!empty($sorting) || (!empty($searchActive)) || (!empty($paging))){ echo 'class="active"';}?>> <a data-toggle="tab" href="#tab12" id="tab12-link">My Appointment list</a></li>
		</ul>
		<div class="tab-content" style="padding-top:20px;">
				<div id="tab8" class="tab-pane fade <?php if($model->tabname=='tab8'){ echo 'in active';}else if(empty($model->tabname) && empty($sorting) && (empty($searchActive)) && (empty($paging))){ echo 'in active';}?>">
					 <?php if(isset($userData) && !empty($userData)){
						foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
							echo '<div class="alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
						}
					  ?>
						 <div class="col-md-12" style="text-align: right">
							<input type="hidden" id="userid" value="<?php echo $userData->user_role_id;?>">
							<span id="editprofile">
								<a href="<?php echo Yii::$app->homeUrl;?>user/updateprofile"><button  name="Edit Profile" class="btn btn-success">Edit Profile</button></a>
							</span>									
						</div>
						<div class="col-md-12"><div class="profileshow">
							<div class="col-md-7">
								<div class="well">		   							
								<div class="col-md-5">Name </div><div class="col-md-6"><b><?php echo $userData->fname.' '.$userData->lname;?></b></div><br />
								<div class="col-md-5">Email </div><div class="col-md-6"><b><?php echo $userData->email;?></b></div><br />
								<div class="col-md-5">Gender </div><div class="col-md-6"><b>
								<?php if($userData->gender) { echo 'Male'; } else { echo 'Female'; } ?>
								</b></div><br />									
								<div class="col-md-5">Landline / Phone No. </div><div class="col-md-6"><b><?php echo $userData->landline;?></b></div><br />
								<?php if(!empty($userData->insurance_no)){ ?>
								<div class="col-md-5">Insurance No.  </div><div class="col-md-6"><b><?php echo $userData->insurance_no;?></b></div><br />
								<?php } ?>
								<div class="col-md-5">Address </div><div class="col-md-6"><b><?php echo $userData->address;?></b></div><br />
							
								</div></div>
								<div class="col-md-5"><div class="col-md-4">
									<?php if(isset($userData->profile_image) && (!empty($userData->profile_image))){ ?>
										<img src="<?php echo Yii::$app->getUrlManager()->createUrl(['uploads']).'/'.$userData->profile_image;?>" width="100px">
									<?php }else{ ?>
										<img src="<?php echo Yii::$app->getUrlManager()->createUrl(['uploads']).'/users/user_profile/default.png';?>" width="100px">
									<?php } ?>
										Profile Image </div>
								</div>
							</div>
						</div>
					 <?php } ?>
				</div>
				<div id="tab9" class="tab-pane fade">
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
				 <div id="tab12" class="tab-pane fade <?php if(!empty($sorting) || (!empty($searchActive)) || (!empty($paging))){ echo 'in active';}?>">
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
											'attribute'=>'providerName',
											'filter' => true,
											'content'=>function($data){
												return $data->provider->fname.' '.$data->provider->lname;	
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
												return $data->payment_method===1 ? 'Insurance' : 'Non Insurance';	
											}
										],
										[
											'attribute'=>'bookingStatusId',
											'filter' => true,
											'content' => function($data){
												return $data->bookingStatus->name;	
											}
										]
									],
								]); ?>
							</div>
							
						</div>
					</div>
			   </div>
			   
			</div>
		</div>
	</div>
</div><!--close row-->

