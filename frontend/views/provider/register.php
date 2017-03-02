<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\FileInput;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$servicesCategory = ArrayHelper::map($servicesCategory, 'category_id', 'category_name'); 
$statelistModel = ArrayHelper::map($statelistModel, 'state_id', 'name'); 
$countryModel = ArrayHelper::map($countryModel, 'country_id', 'name'); 
$qualificationlistModel = ArrayHelper::map($qualificationlistModel, 'id', 'name'); 
$qualificationlistModel['Other'] = 'Others';
?>
<!-- featured Panels -->
	<div class="container">
		<div class="contact-providers-form">
		<ul class="nav nav-tabs" style="padding-top:30px;">
			<li class="active"><a data-toggle="tab" href="#tab1" class="tab1" id="tab1-link">Basic Details</a></li>
			<li><a href="javascript:void(0);" class="tab2" id="tab2-link">Insurance Company</a></li>
			<li><a href="javascript:void(0);" id="tab3-link">Health Facility</a></li>
			<li><a href="javascript:void(0);" id="tab4-link">Upload Contract</a></li>
		</ul>
		<div class="tab-content" style="padding-top:20px;">
			<div id="tab1" class="tab-pane fade  in active">
				<?php $form = ActiveForm::begin([
					'options' => ['enctype'=>'multipart/form-data'],
					'id' => 'user-providers-form'
				]); ?>	
				<div class="col-sm-6">
				<?= $form->field($model, 'fname')->textInput(['maxlength' => true])->label('First Name');?>
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
				<?= $form->field($model, 'passwordhash')->passwordInput(['maxlength' => 255,'value'=>'password','onfocus'=>"if(this.value=='password') this.value='';",  'onblur'=>"if (this.value == '') {this.value = 'password';}"]) ?>
				<?php }  ?>
				</div>
				<div class="col-sm-6">
				<?php if($model->isNewRecord) { ?>
				<?= $form->field($model, 'repeatpassword')->passwordInput(['autocomplete'=>'off'])->label("Confirm Password"); ?>
				<?php  } else { ?>
				<?= $form->field($model,'repeatpassword')->passwordInput(['maxlength' => 255,'value'=>'password','onfocus'=>"if(this.value=='password') this.value='';",  'onblur'=>"if (this.value == '') {this.value = 'password';}"]) ?>
				<?php }  ?>
				</div>		
				<div class="col-sm-6">
				<?= $form->field($model, 'landline')->textInput(['class'=>'form-control provider_phone'])->label('Contact Number') ?>
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
				echo $form->field($model, 'profile_image')->fileInput();
				?>
				<input type="hidden" name="step" value="2">
				</div>
				<div class="col-sm-6">
				<div class="form-group">
				<?= Html::submitButton($model->isNewRecord ? 'Register' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				<?= Html::a('Cancel', ['/'], ['class'=>'btn btn-danger']) ?>
				</div>
				</div>
				<?php ActiveForm::end(); ?>
			</div>		
		</div>
		</div>
   </div>
<script>
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
	
	jQuery(".add_more").on( "click", function(){	
		var $div = $('div[class^="healthfacilitydata"]:last');
		var $klon = $div.clone();
		var $klon_id_arr = $klon.prop("id").split("_");
	    var id = $klon_id_arr[1];
	    var num = parseInt(id) +1;
	    var $klon = $klon.prop("id","healthfacilitylist_"+num);
	    $div.after($klon);
	    
	    $("#healthfacilitylist_"+num).find(".healthfacility_address").prop('name','healthfacility_address_'+num);   
	    $("#healthfacilitylist_"+num).find(".healthfacility_address").prop('id','healthfacility_address_'+num);   
	    $("#healthfacilitylist_"+num).find(".removeBtn").prop('id','removeBtn_'+num);   
	    $("#healthfacilitylist_"+num).find(".hiddenhealthaddress").prop('value',num);
	}); 
});
function removeRowsButton(id){
	var rowid = id.split("_");
	$("#healthfacilitylist_"+rowid[1]).remove();
}
</script>
<style>
.removeBtn > img{
	margin-top: 31px;
	width: 25px;
	cursor:pointer;
}
.add_more{
	width: 100%;
	text-align: right;
	float: right;
	font-weight: 700;
	text-transform: uppercase;
	cursor: pointer;
}
#removeBtn_1{display:none}
</style>

