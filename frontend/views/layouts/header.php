<?php
use frontend\widgets\Loginsignup;
use frontend\widgets\Headermenu;
use yii\web\Session;
use yii\helpers\Url;
use common\models\User;
use common\models\Configuration;
use yii\helpers\Html;
$session = new Session;
$configLogoList = Configuration::find()->where(['status'=> '1' ])->andWhere(['config_key'=>'LOGO_HEADER'])->one();
?>
<!-- header -->
<header>
  <div class="container">
    <div class="row">
      <aside class="col-md-2 col-sm-2"> 
		  <?php 
			$configLogosArray = array();
			if(isset($configLogoList->config_value) && (!empty($configLogoList->config_value))){
				$configLogosArray = explode("/", $configLogoList->config_value);
				if(file_exists('uploads/'.$configLogoList->config_value) && (!empty($configLogosArray[1]))){
					echo '<a href="'.Yii::$app->homeUrl.'" class="logo"><img src="'.Yii::$app->getUrlManager()->createUrl('uploads/configuration/'.$configLogosArray[1]).'" class="img-responsive"></a>';
				}else{
					echo '<a href="'.Yii::$app->homeUrl.'" class="logo"><img src="'.Yii::$app->getUrlManager()->createUrl('images/logo.png').'" class="img-responsive"></a>';
				}
			} ?>		  
		  </aside>
      <aside class="col-md-10 col-sm-10">
        <div class="headerRight">
          <ul class="loginBox">
            <?php
                if(!empty(Yii::$app->session['usersid'])){
                    $userdata = Yii::$app->session['userdata'];
					echo '<li class="dropdown dashboard"> <a aria-expanded="false" aria-haspopup="true" role="button" data-toggle="dropdown" class="dropdown-toggle">Welcome '.$userdata['userFullname'].' <span class="fa fa-angle-down"></span></a>
						<ul class="dropdown-menu">';
						   if(Yii::$app->user->identity->userRole['id'] == USER::URID_PROVIDER) {
							   echo '<li><a href="'.Yii::$app->getUrlManager()->createUrl("provider/dashboard").'">My Dashboard</a></li>';
							   echo '<li><a href="'.Yii::$app->getUrlManager()->createUrl("provider/logout").'">Logout</a></li>';
						   } else if(Yii::$app->user->identity->userRole['id'] == USER::URID_USER) {
							   echo '<li><a href="'.Yii::$app->getUrlManager()->createUrl("user/dashboard").'">My Dashboard</a></li>';
							   echo '<li><a href="'.Yii::$app->getUrlManager()->createUrl("user/logout").'">Logout</a></li>';
						   }
						echo '</ul>
					</li>';
					}else{ 
					  echo '<li><button type="button" class="login" data-toggle="modal" data-target="#myModal">Patient Login</button></li>
						 <li><button type="button" class="login" data-toggle="modal" data-target="#myModal">Sign Up</button></li>';
					} ?>
          </ul>
          <?php echo Headermenu::widget();?>
        </div>
      </aside>
    </div>
  </div>
</header>
<!-- header --> 

<div class="modal fade" id="myModalconfirm" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content signupBox">
        <div class="modal-header">
		  <h4 style="padding:20px; font-weight:normal; color:#da452f;">Your account has been created successfully. Please check your mail and confirm your account.</h4>
        </div>
		<div class="modal-body">
			<div class="row">
			  <div class="col-md-12">
				<button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
			  </div>
			</div>
		</div>
  </div>
</div>
</div>


<div class="tab-content">	  
<div class="col-md-4 col-md-offset-8 col-sm-8 col-sm-offset-2">
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content signupBox">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
		<div class="modal-body">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#tab5" class="tab5" id="tab5-link">Login</a></li>
				<li><a data-toggle="tab" href="#tab6" id="tab6-link">Sign Up</a></li>
			</ul>
			<?php echo Loginsignup::widget();?>
		</div>
  </div>
</div>
</div>
</div>
</div>
<script>
	$(document).ready(function(){
		$(".signupBox").find(".modal-dialog").css({"width":"400px", "height":"auto"});
		$(".signupBox").find(".modal-body").css("height","350");
		$(".signupBox").on("click", ".nav-tabs li a", function(){
			var hrefVal = $(this).attr('href');
			if(hrefVal=='#tab6'){
				$("#tab5").find("div").hide();
				$(".displayerror").text('');
				$(".signupBox").find(".modal-body").css("height","auto");
				$("#loginform-email").val('');
				$("#loginform-password").val('');
			}else{
				$("#tab5").find("div").show();
				$(".signupBox").find(".modal-body").css("height","350");
				$("#tab6").removeClass('active in');
				$("#user-fname").val('');
				$("#user-lname").val('');
				$("#user-email").val('');
				$("#user-mobile").val('');
				$("#user-password_hash").val('');
				$("#user-repassword").val('');
			}
		});
	});
</script>

