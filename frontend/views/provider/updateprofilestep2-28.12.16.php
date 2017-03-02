<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\FileInput;
use yii\bootstrap\Tabs;
use yii\bootstrap\Alert;
use common\models\User;
use common\models\ProviderInsuranceCompany;
use common\models\ProviderUserPrice;
use app\assets\AppAsset;;
use yii\web\Session;
$session = new Session;
$providerfeesModels = ArrayHelper::map($providerfeesModel, 'id', 'fees'); 
/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="container">
	<div class="row" style="padding:15px 0 30px 0">
		<ul class="nav nav-tabs" style="padding-top:30px;">
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep1')?>">Step 1</a></li>
			<li class="active"><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep2')?>" id="tab2-link">Step 2</a></li>
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep3')?>" id="tab3-link">Step 3</a></li>
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep4')?>" id="tab4-link">Step 4</a></li>
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/providerresetpasswordstep5')?>" id="tab5-link">Change Password</a></li>
		</ul>
		<div class="tab-content" style="padding-top:20px;">
		<div id="tab2" class="tab-pane fade in active" style="padding-top:20px;">
			<?php $form = ActiveForm::begin([
				'options' => ['enctype'=>'multipart/form-data'],
				'id' => 'user-providers-form-step2'
			]); 
			foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
				echo '<div class="alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
			}
			?>
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
					<?php 
					if(!empty(Yii::$app->user->identity->id)){
						$providerpricelist = ProviderUserPrice::find()->where(['provider_id'=>Yii::$app->user->identity->id])->andWhere(['user_price_type_id'=>$userVal['id']])->one();
						if(count($providerpricelist)>0){
							$providerUserPriceModel->provider_fees_id = $providerpricelist->provider_fees_id;
						}
					}
						echo $form->field($providerUserPriceModel, 'provider_fees_id', ['inputOptions' => [
				'class' => 'form-control provider_user_price','name' =>'ProviderUserPrice[provider_fees_id]['.$userVal['id'].']', 'id'=>'provideruserprice-provider_fees_id_'.$userVal['id'].'']])->dropDownList($providerfeesModels,['prompt'=>'Select One'])->label(false); ?>
						<span id="provider_user_price_msg_<?php echo $userVal['id'];?>"></span>
					</div>
				</div>
			  </div>
			<?php } ?>
			<h3 class="headingR">Insurance Companies List.</h3>
			<div class="row insComps" id="insurance_companies">
				<?php foreach($insurancecompaniesModel as $keys=>$companyVal){  
						$companylist = ProviderInsuranceCompany::find()->where(['provider_id'=>Yii::$app->user->identity->id])->andWhere(['insurance_companies_id'=>$companyVal['id']])->andWhere(['status'=>1])->one();
						$checked = '';
						if($companyVal['id']==$companylist['insurance_companies_id']){
							$checked = 'checked="checked"';
						}
				?>
					<div class="col-sm-4">	
						<div id="userpricetype-name">
							<label>
								<input type="checkbox" value="<?php echo $companyVal['id'];?>" <?php echo $checked;?> class="insurance_companies" id="InsuranceCompaniesname_<?php echo $companyVal['id'];?>" name="InsuranceCompanies[name][]">
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
						<button class="btn btn-success" type="button" onclick="providerregisterStep2();">Save & Continue</button>
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
function providerregisterStep2(){
	var valid = 1;
	$(".provider_user_price").each(function(index){
		var attrId = $(this).attr("id").split("_");
		var priceValue = $("#provideruserprice-provider_fees_id_"+attrId[3]+" option:selected").val();

		if(priceValue==""){
			$("#provider_user_price_msg_"+attrId[3]).text("Please enter price #"+attrId[3]).css('color','red');
			$("#provideruserprice-provider_fees_id_"+attrId[3]).focus();
			valid = 0;
		}
		if(priceValue!=""){
			if(parseInt(priceValue) > 300){
				$("#provider_user_price_msg_"+attrId[3]).text("Price must not be greater than 300 ($).").css('color','red');
				$("#provideruserprice-provider_fees_id_"+attrId[3]).focus();
				valid = 0;
			}else{
				$("#provider_user_price_msg_"+attrId[3]).empty();	
			}
		}
	});

	if($('#insurance_companies').find('input[type=checkbox]:checked').length == 0){
		$("#insurance_companies_msg").text("Please select atleast one Insurance Company.").css('color','red');
		$("#insurance_companies_msg").focus();
		valid = 0;
	}else{
		$("#insurance_companies_msg").empty();	
	}
	if(valid==0){
		return false;
	}else{
		$("#user-providers-form-step2").submit();
	}	
}
</script>
