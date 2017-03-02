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
		<div class="contact-providers-form">
		<ul class="nav nav-tabs" style="padding-top:30px;">
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep1')?>">Step 1</a></li>
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep2')?>" id="tab2-link">Step 2</a></li>
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep3')?>" id="tab3-link">Step 3</a></li>
			<li class="active"><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/updateprofilestep4')?>" id="tab4-link">Step 4</a></li>
			<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/providerresetpasswordstep5')?>" id="tab5-link">Change Password</a></li>
		</ul>
		<div class="tab-content step4" style="padding-top:20px;">
			<div id="tab4" class="tab-pane fade in active " style="margin-bottom:30px;">
				<?php $form = ActiveForm::begin([
					'options' => ['enctype'=>'multipart/form-data'],
					'id' => 'user-providers-step4-form'
				]);
				foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
					echo '<div class="alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
				}
				?>	
				<?php
				if(count($pcontractModel)>0){
					$counter = 1;
					foreach($pcontractModel as $key=>$contractModel){
						$actualpImages = '';
						$actualImages = explode("/",$contractModel['images']);
						if(!empty($actualImages[1])){
							$actualpImages = $actualImages[1];
						}
					?>	
					<div class="contactimagesdata" id="contactimageslist_<?php echo $contractModel['id'];?>">
						<div class="col-sm-6">
							<?= $form->field($contractModel, 'title')->textInput(['name'=>'providercontract_title_'.$contractModel['id'].'', 'id'=>'providercontract_title_'.$contractModel['id'].'','class'=>'form-control providercontract_title'])->label('Contract Title');?>
							<input type="hidden" name="hiddenaddpcontract[]" class="hiddenprovidercontract" id="hiddenaddpcontract_<?php echo $contractModel['id'];?>" value="<?php echo $contractModel['id'];?>">
							<span id="pcontract_title_msg_<?php echo $contractModel['id'];?>" class="pcontract_title_msg"></span>	
						</div>
						<div class="clearfix"></div>
						<div class="col-sm-6">
							<?= $form->field($contractModel, 'images')->fileInput(['name'=>'providercontract_images_'.$contractModel['id'].'', 'id'=>'providercontract_images_'.$contractModel['id'].'','class'=>'form-control providercontract_images'])->label('Upload Contract Image');?>
							<span id="pcontract_images_msg_<?php echo $contractModel['id'];?>" class="pcontract_images_msg"></span>
							<div class="clearfix"></div>
							<img src="<?php echo Yii::$app->getUrlManager()->createUrl("uploads/".$contractModel['images']."");?>" class="contractImages" width="50" height="">
							<input type="hidden" name="contracthiddenimages[]" class="contracthiddenimages" id="contracthiddenimages_<?php echo $contractModel['id'];?>" value="<?php echo $actualpImages;?>">
							<input type="hidden" name="hiddenupdatecontract[]" class="hiddenupdatecontract" value="<?php echo $contractModel['id'];?>">
						</div>
						<div class="col-md-1 col-sm-1 removeBtn" id="removeBtn_<?php echo $contractModel['id'];?>" <?php if($counter==1){ echo 'style="display:none"';}?>onclick="removeRowsButton(this.id);">
							<img src="<?php echo Yii::$app->getUrlManager()->createUrl("images/remove.png");?>">
						</div>
						<div class="clearfix"></div>
					</div>
				<?php $counter++;} ?>
					<div class="col-xs-2 col-sm-2 col-md-6">
						<div class="add_more"><a href="javascript:void(0);">Add More Contract Images+</a></div>
					</div>
					<div class="clearfix"></div>
				<?php }else{ ?>	
				<div class="contactimagesdata" id="contactimageslist_1">
					<div class="col-sm-6">
						<?= $form->field($contractModel, 'title')->textInput(['name'=>'providercontract_title_1', 'id'=>'providercontract_title_1','class'=>'form-control providercontract_title'])->label('Contract Title');?>
						<input type="hidden" name="hiddenaddpcontract[]" class="hiddenprovidercontract" id="" value="1">
						<span id="pcontract_title_msg_1" class="pcontract_title_msg"></span>	
					</div>
					<div class="clearfix"></div>
					<div class="col-sm-6">
						<?= $form->field($contractModel, 'images')->fileInput(['name'=>'providercontract_images_1', 'id'=>'providercontract_images_1','class'=>'form-control providercontract_images'])->label('Upload Contract Image');?>
						<span id="pcontract_images_msg_1" class="pcontract_images_msg"></span>	
					</div>
					<div class="col-md-1 col-sm-1 removeBtn" id="removeBtn_1" onclick="removeRowsButton(this.id);">
						<img src="<?php echo Yii::$app->getUrlManager()->createUrl("images/remove.png");?>">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="col-xs-2 col-sm-2 col-md-6">
					<div class="add_more"><a href="javascript:void(0);">Add More Contract Images+</a></div>
				</div>
				<div class="clearfix"></div>
				<?php } ?>
				<div class="col-sm-6">
					<div class="form-group">
						<button class="btn btn-success" type="button" onclick="providerregisterStep4();">Save & Close</button>
						<a class="btn btn-danger" href="<?php echo Yii::$app->getUrlManager()->createUrl('provider/dashboard');?>">Cancel</a>
					</div>
				</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
		</div>
   </div>
<script>
$(document).ready(function(){
	jQuery(".add_more").on( "click", function(){	
		var $div = $('div[class^="contactimagesdata"]:last');
		var $klon = $div.clone();
		var $klon_id_arr = $klon.prop("id").split("_");
	    var id = $klon_id_arr[1];
	    var num = parseInt(id) +1;
	    var $klon = $klon.prop("id","contactimageslist_"+num);
	    $div.after($klon);
	    
	    $("#contactimageslist_"+num).find(".providercontract_title").prop('name','providercontract_title_'+num);   
	    $("#contactimageslist_"+num).find(".providercontract_title").prop('id','providercontract_title_'+num);   
	    $("#contactimageslist_"+num).find(".pcontract_title_msg").prop('id','pcontract_title_msg_'+num); 
	    
	    $("#contactimageslist_"+num).find(".providercontract_images").prop('name','providercontract_images_'+num);   
	    $("#contactimageslist_"+num).find(".providercontract_images").prop('id','providercontract_images_'+num);   
	    $("#contactimageslist_"+num).find(".pcontract_images_msg").prop('id','pcontract_images_msg_'+num);   
	      
	    $("#contactimageslist_"+num).find(".removeBtn").prop('id','removeBtn_'+num);   
	    $("#contactimageslist_"+num).find(".pcontract_title_msg").empty();   
	    $("#contactimageslist_"+num).find(".pcontract_images_msg").empty();   
	    $("#contactimageslist_"+num).find(".contractImages").remove();  
	    $("#contactimageslist_"+num).find(".contracthiddenimages").prop('value',''); 
	    $("#contactimageslist_"+num).find(".contracthiddenimages").prop('id','contracthiddenimages_'+num);
	    $("#contactimageslist_"+num).find(".hiddenupdatecontract").prop('name','hiddenaddpcontract[]');   
	    $("#contactimageslist_"+num).find(".hiddenupdatecontract").prop('value',num);   
	    $("#contactimageslist_"+num).find(".hiddenprovidercontract").prop('value',num);
	    $("#contactimageslist_"+num).find(".providercontract_title").prop('id','providercontract_title_'+num).val('');   
	    $("#contactimageslist_"+num).find(".providercontract_images").prop('id','providercontract_images_'+num).val('');   
	}); 
});
function removeRowsButton(id){
	var rowid = id.split("_");
	$("#contactimageslist_"+rowid[1]).remove();
}

function providerregisterStep4(){
	var valid = 1;
	
	$(".providercontract_title").each(function(index){
		counter = index+1;
		var contracttitleArr =  $(this).attr('id').split("_");
		var contactTitleval = $("#providercontract_title_"+contracttitleArr[2]).val();
		if(contactTitleval==''){
			$("#pcontract_title_msg_"+contracttitleArr[2]).text("Please enter contract title #"+counter).css({'color':'red','font-size':'14px'});
			$("#providercontract_title_"+contracttitleArr[2]).focus();
			valid = 0;
		}
	});
	
	$(".providercontract_images").each(function(index){
		counter = index+1;
		var contractimagesArr =  $(this).attr('id').split("_");
		var getFilename = $("#providercontract_images_"+contractimagesArr[2]).val();
		var hiddenGetfilename = $("#contracthiddenimages_"+contractimagesArr[2]).val();
		if(getFilename=='' && hiddenGetfilename==""){
			$("#pcontract_images_msg_"+contractimagesArr[2]).text("Please upload contract image #"+counter).css({'color':'red','font-size':'14px'});
			$("#providercontract_images_"+contractimagesArr[2]).focus();
			valid = 0;
		}
		if(getFilename!='' && getFilename!=undefined){
			var valid_extensions = /(\.jpg|\.jpeg|\.gif|\.png)$/i;   
			if(!valid_extensions.test(getFilename)){ 
				$("#pcontract_images_msg_"+contractimagesArr[2]).text("This file type is not allowed. The file types accepts only:- jpg, jpeg, png and gif.").css({'color':'red', 'font-size':'14px'});
				valid = 0;
			}
		}
	});

	if(valid==0){
		return false;
	}else{
		confirm("Are you sure want to save!");
		$("#user-providers-step4-form").submit();
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
