<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Tabs;
use yii\bootstrap\Alert;
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
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep1')?>">Basic Details</a></li>
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep2')?>" id="tab2-link">Insurance Company</a></li>
			<li class="active"><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep3')?>" id="tab3-link">Health Facility</a></li>
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep4')?>" id="tab4-link">Upload Contract</a></li>
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/providerresetpasswordstep5')?>" id="tab5-link">Change Password</a></li>
		</ul>
		<div class="tab-content" style="padding-top:20px;">
			<div id="tab1" class="tab-pane fade in active">
				<div class="col-md-12">
					<?php $form = ActiveForm::begin([
						'options'=>['enctype'=>'multipart/form-data'],
						'id' => 'user-providers-form-step3'
					]);
					foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
						echo '<div class="alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
					}
					?>
					<div class="col-sm-6">
						<?= $form->field($model, 'health_facility')->textInput()->label('Health Facility Name'); ?>
						<span id="health_facility_msg"></span>
					</div>
					<div class="col-sm-6">&nbsp;</div>
					<div class="clearfix"></div>
					<?php 
					
					if(!empty($healthfacilityModel)){
						$counter =1;
						foreach($healthfacilityModel as $healthModelid){
					 ?>	
						<div class="healthfacilitydata" id="healthfacilitylist_<?php echo $healthModelid['id'];?>">
							<div class="col-sm-6">
								<?= $form->field($healthModelid, 'address')->textarea(['rows' => 2, 'id'=>'healthfacility_address_'.$healthModelid['id'].'', 'name'=>'healthfacility_address_'.$healthModelid['id'].'', 'class'=>'form-control healthfacility_address'])->label('Health Facility Address'); ?>
								<input type="hidden" name="healthfacilityaddress[]" class="hiddenhealthaddress" value="<?php echo $healthModelid['id'];?>"> 	
								<span id="healthfacility_address_msg_<?php echo $healthModelid['id'];?>" class="healthfacility_address_msg"></span>	
							</div>
							<div class="col-md-1 col-sm-1 removeBtn" id="removeBtn_<?php echo $healthModelid['id'];?>" <?php if($counter==1){ echo 'style="display:none;"';} ?> onclick="removeRowsButton(this.id);">
								<img src="<?php echo \Yii::$app->params['HOST_INFO'];?>images/remove.png">
							</div>
							<div class="clearfix"></div>
						</div>
					<?php 
						$counter++;}
					}
					 ?>
					<div class="col-xs-2 col-sm-2 col-md-6">
						<div class="add_more"><a href="javascript:void(0);">Add More +</a></div>
					</div>
					<div class="clearfix"></div>
					<div class="col-sm-6">
						<div class="form-group">
							<button class="btn btn-success" type="button" onclick="providerregisterStep3();">Save & Continue</button>
						</div>
					</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>	
		</div>
    </div>
</div>
<script>
 $(document).ready(function(){
	jQuery(".add_more").on( "click", function(){	
		var $div = $('div[class^="healthfacilitydata"]:last');
		var $klon = $div.clone();
		var $klon_id_arr = $klon.prop("id").split("_");
	    var id = $klon_id_arr[1];
	    var num = parseInt(id) +1;
	    var $klon = $klon.prop("id","healthfacilitylist_"+num);
	    $div.after($klon);
	    <?php if(!empty($pid)){ ?>
			 $("#healthfacilitylist_"+num).find(".hiddenhealthaddress").prop('name','addhealthfacilityaddress[]');  
		<?php } ?>
	    $("#healthfacilitylist_"+num).find(".healthfacility_address").prop('name','healthfacility_address_'+num);   
	    $("#healthfacilitylist_"+num).find(".healthfacility_address").prop('id','healthfacility_address_'+num);   
	    $("#healthfacilitylist_"+num).find(".healthfacility_address_msg").prop('id','healthfacility_address_msg_'+num);   
	    $("#healthfacilitylist_"+num).find(".healthfacility_address_msg").empty();   
	    $("#healthfacilitylist_"+num).find(".removeBtn").prop('id','removeBtn_'+num);   
	    $("#healthfacilitylist_"+num).find(".hiddenhealthaddress").prop('value',num);
	    $("#healthfacilitylist_"+num).find(".healthfacility_address").prop('id','healthfacility_address_'+num).val('');   
	}); 
});
function removeRowsButton(id){
	var rowid = id.split("_");
	$("#healthfacilitylist_"+rowid[1]).remove();
}

function providerregisterStep3(){
	var valid = 1;
	var health_facility = $("#user-health_facility").val();
	if(health_facility==""){
		$("#health_facility_msg").text("Please enter health facility name.").css('color','red');
		$("#health_facility_msg").focus();
		valid = 0;
	}
	$(".healthfacilitydata").each(function(index){
		index = index+1;
		var healthAddid = $(this).attr('id').split("_");
		var healthAddvalue = $("#healthfacility_address_"+healthAddid[1]).val();
		if(healthAddvalue==""){
			$("#healthfacility_address_msg_"+healthAddid[1]).text("Please enter address #"+index).css('color','red');
			$("#healthfacility_address_msg_"+healthAddid[1]).focus();
			valid = 0;
		}
	});
	
	if(valid==0){
		return false;
	}else{
		$("#user-providers-form-step3").submit();
	}
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
