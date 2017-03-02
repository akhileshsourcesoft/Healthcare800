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
use common\models\UserFeedback;

class Feedbackform extends Widget
{
	public $provider_id;
	public $user_id;
	
	public function init(){
	 
		parent::init();
	
	}

	public function run(){
		    $provider_id = $this->provider_id;
		    $user_id = $this->user_id;
			$feedbackmodel = new UserFeedback();
					$form = ActiveForm::begin(['id' => 'feedback-form','options'=>['class'=>'cd-form'],'action' => ['user/feedbackform']]);
					$feedbackForm = $form->field($feedbackmodel, "message")->textarea(["placeholder" => "Enter Your Message", 'rows' => 6]).'<span id="feedbackMessage"></span>
					<div class="form-group">
						<button type="button" id="userFeedbackform" class="button btn btn-success">Submit</button>&nbsp;&nbsp;&nbsp;<button data-dismiss="modal" class="btn btn-default btn-danger" type="button">Cancel</button>
					</div>
					<input type="hidden" id="provider_id" name="UserFeedback[provider_id]" value="'.$provider_id.'">			
					<input type="hidden" id="user_id" name="UserFeedback[user_id]" value="'.$user_id.'">			
					</form>';
			
			return $feedbackForm;
	
	}
	    
}
?>
<style>
.field-userfeedback-message{margin-top:25px;}
.feedbackforms{height:100px;}
</style>
<script type="text/javascript"> 
	$(document).ready(function(){
		$("#userFeedbackform").click(function(){
			var valid = 1;
			var userfeedbackmessage = $("#userfeedback-message").val(); 
			if(userfeedbackmessage==''){
				$("#feedbackMessage").text("Please enter message.").css('color','red');
				$("#feedbackMessage").focus();	
				valid = 0;	
			}else{
				$("#feedbackMessage").empty();	
			}
			
			if(valid==0){
				return false;
			}else{
				$.ajax({
					 type: "POST",
					 url:"<?php echo Yii::$app->getUrlManager()->createUrl("provider/userfeedbackmessage");?>", 
					 data:$("#feedback-form").serialize(),
						success: function(res){ 
							 var currentUrl = document.URL; 
							 if(currentUrl!=''){               
								 $('#myFeedbackModal').modal('hide');
								 $('#myModalfeedback').modal();
								 $("#userfeedback-message").val('');
								 window.location.href=currentUrl;
							}
						}
				}); 	
			}            
		});                 	                 
	});
</script>
