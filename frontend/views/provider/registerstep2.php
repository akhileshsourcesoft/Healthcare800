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
$providerfeesModel = ArrayHelper::map($providerfeesModel, 'id', 'fees'); 
?>
<!-- featured Panels -->
	<div class="container">
		<div class="contact-providers-form">
		<ul class="nav nav-tabs" style="padding-top:30px;">
			<li><a href="javascript:void(0);" class="tab1" id="tab1-link">Basic Details</a></li>
			<li class="active"><a data-toggle="tab" href="#tab2" class="tab2" id="tab2-link">Insurance Company</a></li>
			<li><a href="javascript:void(0);" id="tab3-link">Health Facility</a></li>
			<li><a href="javascript:void(0);" id="tab4-link">Upload Contract</a></li>
		</ul>
		<div id="tab2" class="tab-pane fade in active" style="padding-top:20px;">
			  <div class="col-md-12 col-sm-12">	
				<?php $form = ActiveForm::begin([
					'options' => ['enctype'=>'multipart/form-data'],
					'id' => 'user-providers-step2-form'
				]); ?>
				<?php foreach($userpricetypeModel as $userVal){ ?>
				<div class="col-sm-6">	
					<div class="row">
						<div class="col-sm-4">	
							<div id="userpricetype-name">
								<label>
									<?php echo $userVal['name'];?>
								</label>
							</div>
						</div>
						<div class="col-sm-8">	
							<div class="form-group field-provider_user_price">
								<div class="form-group field-userpricetype-id">
									<?php if(!empty($providerfeesModel)){ ?>
									<select id="userpricetype_id_<?php echo $userVal['id'];?>" class="form-control provider_user_price" name="UserPriceType[<?php echo $userVal['id'];?>]">
										<option value="">Select One</option>
										<?php foreach($providerfeesModel as $key=>$priceValue){ ?>
											<option value="<?php echo $key;?>"><?php echo $priceValue;?></option>
										<?php } ?>
									</select>
									<?php } ?>
								</div>
								<span id="provider_user_price_msg_<?php echo $userVal['id'];?>"></span>
							</div>
						</div>
					</div>
				</div>
                
				<?php } ?>
					<h3 class="headingR">Insurance Companies List.</h3>
					<div class="row insComps" id="insurance_companies">
						<?php foreach($insurancecompaniesModel as $keys=>$companyVal){  ?>
							<div class="col-sm-4">	
								<div id="userpricetype-name">
									<label>
										<input type="checkbox" value="<?php echo $companyVal['id'];?>" class="insurance_companies" id="InsuranceCompaniesname_<?php echo $companyVal['id'];?>" name="InsuranceCompanies[name][]">
										<?php echo $companyVal['name'];?>
									</label>
								</div>
							</div>
					   <?php } ?>
					   <div class="clearfix"></div>
					   <span id="insurance_companies_msg"></span>
					</div>
							
						<div class="col-sm-6">
                        <div class="row">
							<div class="form-group">
								<button class="btn btn-success" type="button" onclick="registerStep2();">Save & Continue</button>
								<a class="btn btn-danger" href="<?php echo Yii::$app->homeUrl;?>">Cancel</a>
							</div>
                            </div>
						</div>
				<?php ActiveForm::end(); ?>
			  </div>
			</div>
		</div>
		</div>
   </div>
<script>
function registerStep2(){
	var valid = 1;
	$(".provider_user_price").each(function(index){
		var attrId = $(this).attr("id").split("_");
		var priceValue = $("#userpricetype_id_"+attrId[2]+" option:selected").val();

		if(priceValue==""){
			$("#provider_user_price_msg_"+attrId[2]).text("Please enter price #"+attrId[2]).css('color','red');
			$("#provider_user_price_msg_"+attrId[2]).focus();
			valid = 0;
		}
		if(priceValue!=""){
			if(parseInt(priceValue) > 300){
				$("#provider_user_price_msg_"+attrId[2]).text("Price must not be greater than 300 ($).").css('color','red');
				$("#provider_user_price_msg_"+attrId[2]).focus();
				valid = 0;
			}else{
				$("#provider_user_price_msg_"+attrId[2]).empty();	
			}
		}
	});

	/*
	if($('#insurance_companies').find('input[type=checkbox]:checked').length == 0){
		$("#insurance_companies_msg").text("Please select atleast one Insurance Company.").css('color','red');
		$("#insurance_companies_msg").focus();
		valid = 0;
	}else{
		$("#insurance_companies_msg").empty();	
	}
	*/
	if(valid==0){
		return false;
	}else{
		$("#user-providers-step2-form").submit();
	}	
}
</script>

