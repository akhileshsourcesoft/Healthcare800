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
?>
<!-- featured Panels -->
	<div class="container">
		<div class="contact-providers-form">
		<ul class="nav nav-tabs" style="padding-top:30px;">
			<li><a href="javascript:void(0);" class="tab1" id="tab1-link">Basic Details</a></li>
			<li><a href="javascript:void(0);" class="tab2" id="tab2-link">Insurance Company</a></li>
			<li class="active"><a data-toggle="tab" href="#tab3" id="tab3-link">Health Facility</a></li>
			<li><a href="javascript:void(0);" class="tab4" id="tab4-link">Upload Contract</a></li>
		</ul>
		<div class="tab-content step3" style="padding-top:20px;">
			<div id="tab3" class="tab-pane fade in active " style="margin-bottom:30px;">
				<?php $form = ActiveForm::begin([
					'options' => ['enctype'=>'multipart/form-data'],
					'id' => 'user-providers-step3-form'
				]); ?>
				<div class="col-sm-6">
					<?= $form->field($model, 'health_facility')->textInput()->label('Health Facility Name'); ?>
					<span id="health_facility_msg"></span>
				</div>
				<div class="col-sm-6">&nbsp;</div>
				<div class="clearfix"></div>
				<div class="healthfacilitydata" id="healthfacilitylist_1">
					<div class="col-sm-6">
						<?= $form->field($healthfacilityModel, 'address')->textarea(['rows' => 2, 'id'=>'healthfacility_address_1', 'name'=>'healthfacility_address_1', 'class'=>'form-control healthfacility_address'])->label('Health Facility Address'); ?>
						<input type="hidden" name="healthfacilityaddress[]" class="hiddenhealthaddress" value="1"> 	
						<span id="healthfacility_address_msg_1" class="healthfacility_address_msg"></span>	
					</div>
					<div class="col-md-1 col-sm-1 removeBtn" id="removeBtn_1" onclick="removeRowsButton(this.id);">
						<img src="<?php echo Yii::$app->getUrlManager()->createUrl("backend/web/images/remove.png");?>">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="col-xs-2 col-sm-2 col-md-6">
					<div class="add_more"><a href="javascript:void(0);">Add More +</a></div>
				</div>
				<div class="clearfix"></div>
				<div class="col-sm-6">
					<div class="form-group">
						<button class="btn btn-success" type="button" onclick="registerStep3();">Save & Continue</button>
						<a class="btn btn-danger" href="<?php echo Yii::$app->homeUrl;?>">Cancel</a>
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

function registerStep3(){
	var valid = 1;
	var health_facility = $("#user-health_facility").val();
	if(health_facility==""){
		$("#health_facility_msg").text("Please enter health facility name.").css('color','red');
		$("#health_facility_msg").focus();
		valid = 0;
	}
	$(".healthfacilitydata").each(function(index){
		var healthAddid = $(this).attr('id').split("_");
		var healthAddvalue = $("#healthfacility_address_"+healthAddid[1]).val();
		if(healthAddvalue==""){
			$("#healthfacility_address_msg_"+healthAddid[1]).text("Please enter health facility address #"+healthAddid[1]).css('color','red');
			$("#healthfacility_address_msg_"+healthAddid[1]).focus();
			valid = 0;
		}
	});
	
	if(valid==0){
		return false;
	}else{
		$("#user-providers-step3-form").submit();
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
