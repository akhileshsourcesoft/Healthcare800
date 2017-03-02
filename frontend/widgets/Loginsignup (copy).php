<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\Expression;
use yii\widgets\ActiveForm;
use common\models\LoginForm;
use common\models\User;

class Loginsignup extends Widget
{
	
	 public function init(){
		 
        parent::init();
        
	 }

	public function run(){
		
		$loginmodel = new LoginForm();
		$signupmodel = new User();
		$loginForm = '<div id="tab5" class="tab-pane fade in active">
			  <ul class="nav" id="lb-tabs" style="padding-top:20px;">';
					$form = ActiveForm::begin(['id' => 'login-form','options'=>['class'=>'cd-form'],'action' => ['user/userslogin']]);
					$loginForm .= $form->field($loginmodel, "email")->textInput(["placeholder" => "Enter Your Email id", "autocomplete"=>"off", ]).$form->field($loginmodel, "password")->passwordInput(["placeholder" => "Enter Your Password", "autocomplete"=>"off",]).'<span class="displayerror" style="color:red;"></span>'.$form->field($loginmodel, "rememberMe")->checkbox().'<div class="form-group">'.Html::Button("Submit", ['id' => 'userLogin',"class" =>"button btn btn-success"]).'&nbsp;&nbsp;&nbsp;<button data-dismiss="modal" class="btn btn-default btn-danger" type="button">Cancel</button></div><input type="hidden" id="user_role" name="LoginForm[user_role]" value="3">
			   </form>
			 </ul>
			</div>';	
			$signsForm = '<div id="tab6" class="tab-pane fade">
				<form id="signups-form" action="" method="post">
				<div class="form-group field-user-fname">
					<label class="control-label" for="user-fname">First Name</label>
					<input type="text" id="user-fname" class="form-control" name="User[fname]" placeholder="Enter Your First Name">
				</div>
				<span id="fnameMsg"></span>
				<div class="form-group field-user-lname">
					<label class="control-label" for="user-lname">Last Name</label>
					<input type="text" id="user-lname" class="form-control" name="User[lname]" placeholder="Enter Your Last Name">
				</div>
				<span id="lnameMsg"></span>
				<div class="form-group field-user-email">
					<label class="control-label" for="user-email">Email</label>
					<input type="text" id="user-email" class="form-control" name="User[email]" placeholder="Enter Your Email id">
				</div>
				<span id="emailMsg"></span>
				<div class="form-group field-user-landline">
					<label class="control-label" for="user-landline">Landline / Phone No.</label>
					<input type="text" id="user-landline" class="form-control" name="User[landline]" maxlength="10" placeholder="Enter Your Landline / Phone No.">
				</div>
				<span id="mobileMsg"></span>
				<div class="form-group field-user-password_hash">
					<label class="control-label" for="user-password_hash">Password</label>
					<input type="password" id="user-password_hash" class="form-control" name="User[password_hash]" placeholder="Enter Your Password.">
				</div>
				<span id="passwordMsg"></span>
				<div class="form-group field-user-repassword">
					<label class="control-label" for="user-repassword">Repassword</label>
					<input type="password" id="user-repassword" class="form-control" name="User[repassword]" placeholder="Enter Your Confirm Password.">
				</div>
				<span id="confpasswordMsg"></span>
				<div class="form-group">
					<button type="button" id="userSignups" class="button btn btn-success">Register</button>&nbsp;&nbsp;&nbsp;<button data-dismiss="modal" class="btn btn-default btn-danger" type="button">Cancel</button>
				</div>
				<input type="hidden" id="user_role" name="User[user_role]" value="3">			
				</form>
			</div>';
			
			return $loginForm.$signsForm;
	
	}
	    
}
?>

<script type="text/javascript"> 
	$(document).ready(function(){
		$("#userLogin").click(function(){   
				$.ajax({
					 type: "POST",
					 dataType: "json",
					 url:"<?php echo Yii::$app->getUrlManager()->createUrl("user/userslogin");?>", 
					 data:$("#login-form").serialize(),
						success: function(result){   
							var currenturl = document.URL;            
						    if(result.login==3 && result.login!=undefined){
								if(currenturl=='<?php echo Yii::$app->getUrlManager()->createUrl("provider/register");?>'){
									location.href = "<?php echo Yii::$app->getUrlManager()->createUrl("user/dashboard");?>";
								}else{
									location.href = currenturl;
								}
							}else if(result.login==4 && result.login!=undefined){	
								location.href = "<?php echo Yii::$app->getUrlManager()->createUrl("provider/dashboard");?>";
							}else{			
								 $(".displayerror").html(result.errordisplay);                          
							}					   
					 }
			});            
		});
		
		$("#userSignups").click(function(){
			var valid = 1;
			var fname = $("#user-fname").val(); 
			if(fname==''){
				$("#fnameMsg").text("Please enter first name.").css('color','red');
				$("#fnameMsg").focus();	
				valid = 0;	
			}else{
				$("#fnameMsg").empty();	
			}
			var lname = $("#user-lname").val(); 
			if(lname==''){
				$("#lnameMsg").text("Please enter last name.").css('color','red');		
				$("#lnameMsg").focus();
				valid = 0;
			}else{
				$("#lnameMsg").empty();	
			}
			var email = $("#user-email").val(); 
			if(email==''){
				$("#emailMsg").text("Please enter email.").css('color','red');	
				$("#emailMsg").focus();	
				valid = 0;
			}else{
				$("#emailMsg").empty();	
			}
			if(email!=''){
				if(!validateEmail(email)){
					$("#emailMsg").text("Please enter valid email.").css("color", "red");
					valid = 0;
				}
			}
			
			var mobile = $("#user-landline").val(); 
			if(mobile==''){
				$("#mobileMsg").text("Please enter landline / phone no.").css('color','red');	
				$("#mobileMsg").focus();	
				valid = 0;
			}else{
				$("#mobileMsg").empty();	
			}
			var password = $("#user-password_hash").val(); 
			if(password==''){
				$("#passwordMsg").text("Please enter password.").css('color','red');
				$("#passwordMsg").focus();		
				valid = 0;
			}else{
				$("#passwordMsg").empty();
			}
			var userRepassword = $("#user-repassword").val(); 
			if(userRepassword==''){
				$("#confpasswordMsg").text("Please enter confirm password.").css('color','red');
				$("#confpasswordMsg").focus();		
				valid = 0;
			}
		if(password!='' && userRepassword!=''){
			if(password.length < 6) {
				$("#confpasswordMsg").text("Error: Password must contain at least six characters!").css('color','red');	
				$("#confpasswordMsg").focus();
				valid = 0;
			}else if(password != userRepassword) {
				$("#confpasswordMsg").text("Password doesn't match. Please use correct password.").css('color','red');
				$("#confpasswordMsg").focus();	
				valid = 0;
			}else{
				$("#confpasswordMsg").empty();
			}
		}
			
			if(valid==0){
				return false;
			}else{
				$.ajax({
					 type: "POST",
					 url:"<?php echo Yii::$app->getUrlManager()->createUrl("user/usersignup");?>", 
					 data:$("#signups-form").serialize(),
						success: function(res){ 
							if(res.length==16){                         
								 $('#myModal').modal('hide');
								 $('#myModalconfirm').modal();
								 $("#user-fname").val('');
								 $("#user-lname").val('');
								 $("#user-email").val('');
								 $("#user-landline").val('');
								 $("#user-password_hash").val('');
								 $("#user-repassword").val('');
							}else{
								$("#emailMsg").html(res).css({'color':'red','font-weight':'500'});
							}
							   
					 }
				}); 	
			}            
		});                 	                 
	});
function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

</script>
