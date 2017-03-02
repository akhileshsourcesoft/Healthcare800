<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\FileInput;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;
use yii\bootstrap\Alert;
use common\models\UserTimeslotBooking;
use common\models\User;
use dosamigos\datepicker\DatePicker;
use app\assets\AppAsset;;
use yii\web\Session;
$session = new Session;
/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = "$model->fname  $model->lname";
$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$id = Yii::$app->user->identity->id;
$type = Yii::$app->request->get('type');
if($type=='monthly'){
	if(!empty(Yii::$app->request->get('fromdate'))){
		$fromdate = Yii::$app->request->get('fromdate');
		$date = $fromdate;
	}
	if(!empty(Yii::$app->request->get('enddate'))){
		$end_date = Yii::$app->request->get('enddate');
		$enddate = $end_date;
	}
}else if($type=='day'){
	$fromdate = Yii::$app->request->get('currdate');
	$date = $fromdate;
	$end_date = $date;
}else{
	$date = date( 'Y-m-d', strtotime( 'sunday previous week' ) );
	$end_date = date( 'Y-m-d', strtotime( 'saturday this week' ) );
}

?>
<div class="container">
<div class="row" style="padding:15px 0 30px 0">
<div class="contact-providers-form">
<div class="scheduleTable tabb">
<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data'],'id' => 'providers-appointment-calender']); ?>
<div class="mList">
	<ul>
		<li><a href="javascript:void(0)">
			<?php 
			if($type=='day'){
				echo date("M d", strtotime($date)).' - '.date("M d", strtotime($end_date)).', '.date("Y", strtotime($end_date));
			}else if($type=='week'){
				echo date("M d", strtotime($date)).', '.date("Y", strtotime($date));
			}else{
				echo date("M d", strtotime($date)).' - '.date("M d", strtotime($end_date)).', '.date("Y", strtotime($end_date));
			}
			?>
		</a></li>
		<li><a href="<?php echo Yii::$app->geturlManager()->createUrl('provider/viewappointment?type=day&currdate='.date( 'Y-m-d' ).'');?>" class="<?php echo $type=='day'?'active':'';?>">Day</a></li>
		<li><a href="<?php echo Yii::$app->geturlManager()->createUrl('provider/viewappointment?type=weekly&fromdate='.$date.'&enddate='.$end_date.'');?>" class="<?php if($type=='weekly'){ echo 'active';}else if(empty($type)){ echo 'active';}?>">Week</a></li>
		<li><a href="javascript:void(0)" onclick="getMonthlydata();" class="<?php echo $type=='monthly'?'active':'';?>">Agenda</a></li>
	</ul>
    <div id="monthlydata" <?php if($type=='monthly'){ echo 'style="display:block;"';}else{ echo 'style="display:none;"';}?> class="col-md-6">
			<?= DatePicker::widget([
			'model' => $model,
			'attribute' => 'start_date',
			'template' => '{addon}{input}',
				'clientOptions' => [
					'autoclose' => true,
					'format' => 'dd-mm-yyyy',
				]
			]);?>
			<?= DatePicker::widget([
			'model' => $model,
			'attribute' => 'end_date',
			'template' => '{addon}{input}',
				'clientOptions' => [
					'autoclose' => true,
					'format' => 'dd-mm-yyyy', 
				]
			]);?>
		<div id="searchmsg"></div>
		<button class="btn btn-success" type="button" onclick="searchAppointmentlist();">Search appointment list</button>
    </div>
</div>
<div class="clear"></div>
<div class="viewprovidercalender">
<div class="innn">
<ul class="forTime">
	<li class="firstRow">Time</li>
	<?php 
	$intTime = 15;
	for($i=9; $i<=23; $i++){
		for($j=1; $j<=4; $j++){
			if($j==1){
				$intValue = $i.':'.'00';	
			}else{
				$intValue = $i.':'.($j-1)*$intTime;	
			}
			echo '<li>'.date('G:i A', strtotime($intValue)).'</li>';
		}
	} ?>
</ul>
<?php
while(strtotime($date) <= strtotime($end_date)) {	
?>
<ul class="forWeek">
	<li class="firstRow"><?php echo date("d D", strtotime($date));?></li>
	<?php
	$startitme = '';
	$intTime = 15;
	 for($i=9; $i<=23; $i++){ 
		 for($j=1; $j<=4; $j++){
			if($j==1){
				$intValue = $i.':'.'00';	
			}else{
				$intValue = $i.':'.($j-1)*$intTime;	
			}
		 $endtime = (($i+1) .':00');
		 $startitme = ($i .':00');
	
		 $timeslotbooking = UserTimeslotBooking::find()->select('booking_time, booking_date, id, user_id, fullname')->where(['booking_date'=> $date])->andWhere(['booking_time'=>$intValue])->andWhere(['provider_id'=>$id])->asArray()->all();

		 if(!empty($timeslotbooking)){
			foreach($timeslotbooking as $key=>$val){	
				if($val['booking_date']==$date){
					$patientName = (strlen($val['fullname'])>11)?substr($val['fullname'], 0,11).'..':$val['fullname'];
					echo '<li><a href="javascript:void(0)" id="popover_'.$val['id'].'" class="popupProvider" data-placement="bottom"><i class="fa fa-user" aria-hidden="true"></i> '.$patientName.' <span class="providerstimeslot">'.$val['booking_time'].' - '.date('H:i', (strtotime("+15 minutes", strtotime($val['booking_time'])))).'</span></a></li>';
				}	
			} 
		} else {
			echo "<li>--</li>";
		}
	   }
	 } 
	 ?>
</ul>
<?php $date = date ("Y-m-d", strtotime("+1 day", strtotime($date))); 
}
?>
</div>
</div>
<div class="modal providerviewcalender fade" id="myModalviewcalender" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content signupBox">
        <div class="modal-header">
		  <h4 style="padding:20px; font-weight:normal; color:#da452f;">Appointment List !</h4>
		   <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <div class="scheduleTable tabb">
			<div class="innn">
			<ul class="forTime">
			<li class="firstRow">Time</li>
			<?php
			$id = Yii::$app->user->identity->id;
			$type = Yii::$app->request->get('type');
			if($type=='monthly'){
				if(!empty(Yii::$app->request->get('fromdate'))){
					$fromdate = Yii::$app->request->get('fromdate');
					$date = $fromdate;
				}
				if(!empty(Yii::$app->request->get('enddate'))){
					$end_date = Yii::$app->request->get('enddate');
					$enddate = $end_date;
				}
			}else if($type=='day'){
				$fromdate = Yii::$app->request->get('currdate');
				$date = $fromdate;
				$end_date = $date;
			}else{
				$date = date( 'Y-m-d', strtotime( 'sunday previous week' ) );
				$end_date = date( 'Y-m-d', strtotime( 'saturday this week' ) );
			}
			 $intTime = 15;
				for($i=9; $i<=23; $i++){
					for($j=1; $j<=4; $j++){
						if($j==1){
							$intValue = $i.':'.'00';	
						}else{
							$intValue = $i.':'.($j-1)*$intTime;	
						}
						echo '<li>'.date('G:i A', strtotime($intValue)).'</li>';
					}
				} 
			?>
			</ul>
		  <?php while(strtotime($date) <= strtotime($end_date)){ ?>
			<ul class="forWeek">
				<li class="firstRow">
				<?php echo date("d D", strtotime($date));?>
				</li>
				<?php
				$startitme = '';
				$intTime = 15;
				 for($i=9; $i<=23; $i++){ 
					 for($j=1; $j<=4; $j++){
						if($j==1){
							$intValue = $i.':'.'00';	
						}else{
							$intValue = $i.':'.($j-1)*$intTime;	
						}
					 $endtime = (($i+1) .':00');
					 $startitme = ($i .':00');
					 $timeslotbooking = UserTimeslotBooking::find()->select('booking_time, booking_date, id, user_id, fullname')->where(['booking_date'=> $date])->andWhere(['booking_time'=>$intValue])->andWhere(['provider_id'=>$id])->asArray()->all();

					 if(!empty($timeslotbooking)){
						foreach($timeslotbooking as $key=>$val){
							if($val['booking_date']==$date){
								$patientName = (strlen($val['fullname'])>11)?substr($val['fullname'], 0,11).'..':$val['fullname'];
								echo '<li><a href="javascript:void(0)" id="timeslot_'.$val['id'].'" class="timeslotpopup"><i class="fa fa-user" aria-hidden="true"></i> '.$patientName.' <span class="providerstimeslot">'.$val['booking_time'].' - '.date('H:i', (strtotime("+15 minutes", strtotime($val['booking_time'])))).'</span></a></li>';
							}	
						} 
					} else {
						echo "<li><a href=\"javascript:void(0)\" onclick=\"shiftTimeslotAppoiontment('".date('Y-m-d', strtotime($date))."', '".$intValue."', '".$id."')\" style=\"cursor:pointer;\">--</a></li>";
					}
				   }
				 }
				 ?>
			</ul>
			<?php $date = date ("Y-m-d", strtotime("+1 day", strtotime($date))); 
			} ?>
			<input type="hidden" name="appendedTimeslotid" id="appendedTimeslotid">
        </div>
        </div>
        </div>
  </div>
</div>
</div>
<?php ActiveForm::end(); ?>
</div>
</div>
</div>
</div>
<script type="text/javascript">
	var fromdate, enddate;
	<?php
	if(Yii::$app->request->get('type') == 'monthly'){
	 if(!empty(Yii::$app->request->get('fromdate')) && (!empty(Yii::$app->request->get('enddate')))){ ?>
		var fromdate = '<?php echo Yii::$app->request->get('fromdate')?>';
		var enddate = '<?php echo Yii::$app->request->get('enddate')?>';
	<?php } ?>
	$("#user-start_date").val(fromdate);
	$("#user-end_date").val(enddate);
	<?php } ?>
	
	jQuery(".popupProvider").click(function() {
		var popupid = $(this).attr('id').split('_');
		$.ajax({
			type: 'POST',
			url: "<?php echo Yii::$app->getUrlManager()->createUrl("provider/patientdetails");?>",
			data: {bookingid:popupid[1]},
			success: function(res){
				if(res!=null){
					$(".popover-content").css({'height':'200'});
					$("#popover_"+popupid[1]).addClass("showPopup");
					$('.popupProvider').not("#popover_"+popupid[1]).popover('destroy');
					$("#popover_"+popupid[1]).popover({
						html: true,
						animation: true,
						popupDelay: 0,
						title: 'PROVIDER & PATIENT DETAILS.<a class="close" id="popover_'+popupid[1]+'" href="javascript:void(0)">&times;</a>',
						content: $(".popover-content").html(res),
					});
				}
			}
		});	
	});

	jQuery(document).on("click", ".close", function () {
		if($(this).attr('id')!="" && $(this).attr('id')!=undefined){
			var clspopupid = $(this).attr('id').split('_');
			if(clspopupid[1]!=""){
				$("#popover_"+clspopupid[1]).popover('destroy');
			}
		}
	});
	
	function shiftAppointment(statusid, slotid, providerid){
		var order_status = $("#order_status option:selected").text();
		var mydata = {'statusid':statusid, 'slotid': slotid, 'providerid':providerid, 'order_status':order_status}
		var myUrls = '<?php echo Yii::$app->getUrlManager()->createUrl("provider/shiftproviderappointment"); ?>';
		 $.ajax({
			type: "POST",
			url: myUrls,
			data: mydata,
			async: true,
			 success: function(result){
				var resArray = result.split("#");
				if(resArray[0]==4){
					$('#myModalviewcalender').modal();
					$("#appendedTimeslotid").val(slotid);
					$("#appointment_reasonid").show();
					$("#appointment_savebtn").hide();
				}else{
					if(resArray[0]==3){
						$("#appointment_reasonid").show();
						$("#appointment_savebtn").show();
						$("#appointment_reason_id").val('');
					}else if(resArray[0]!=""){
						$("#appointment_reasonid").hide();
						$("#appointment_savebtn").show();
					}
				}
			}
		});
	}
	
	function shiftTimeslotAppoiontment(slotdate, timeslot, providerid){
		var provider_id = providerid;
		var time_slot = timeslot;
		var slot_date = slotdate;
		var timeSlotid = $("#appendedTimeslotid").val();
		var mydata = {'slot_date':slot_date, 'time_slot': time_slot, 'provider_id':provider_id, 'timeSlotid':timeSlotid}
		var myUrls = '<?php echo Yii::$app->getUrlManager()->createUrl("provider/shifttimeslotappointment"); ?>';
		 $.ajax({
			type: "POST",
			url: myUrls,
			data: mydata,
			 success: function(result){
				if(result!=""){
					$("#shiftpatient").html(result);
					$("#myModalviewcalender").modal('hide');
				}
			}
		});
	}
	
	function shiftProvidersappoointment(slot_date, time_slot, providerid, timeslotid){
		var order_status = $("#order_status option:selected").text();
		var appointment_reasonid = $("#appointment_reason_id").val();
		var orderstatusid = $("#order_status").val();

		if(appointment_reasonid==""){
			$("#appointment_reason_id").css({'border':'2px solid red'});
			return false;	
		}else{
			var getFullUrl = '<?php echo Yii::$app->request->getUrl();?>';
			var mydata = {'slotdate':slot_date, 'timeslot': time_slot, 'provider_id':providerid, 'time_slotid':timeslotid, 'appointment_reasonid':appointment_reasonid, 'order_status':order_status, 'orderstatusid':orderstatusid}
			var myUrls = '<?php echo Yii::$app->getUrlManager()->createUrl("provider/shiftappointment"); ?>';
			 $.ajax({
				type: "POST",
				url: myUrls,
				data: mydata,
				 success: function(resdata){
					if(resdata!=""){  
						$(".slottimedate").text(resdata).css({'font-weight':'bold'});
						$("#shiftmsg").text("Appointment Shift Successfully!").css({'color':'red','margin-top':'10px', 'margin-bottom':'10px'});
						$("#appointment_reason_id").val('');
						$("#shiftButton").hide();
						location.href = getFullUrl;
					}
				}
			});
	    }
	}
	
	function getMonthlydata(){
		$("#monthlydata").slideToggle();
	}
	
	function searchAppointmentlist(){
		
		var valid = 1;
		var start_date = $("#user-start_date").val();
		var end_date = $("#user-end_date").val();

		if(start_date==""){
			$("#user-start_date").css({'border':'1px solid red'});
			$("#user-start_date").focus();
			valid = 0;
		}
		if(end_date==""){
			$("#user-end_date").css({'border':'1px solid red'});
			$("#user-end_date").focus();
			valid = 0;
		}
		if(start_date!="" && end_date!=""){
			if(start_date > end_date){
				$("#searchmsg").text("End Date must be greater than Start Date.").css({'color':'red', 'font-weight':'bold'});
				$("#searchmsg").focus();
				valid = 0;
			}
		}
		if(valid==0){
			return false;
		}else{
			var currentUrl = '<?php echo Yii::$app->geturlManager()->createUrl('provider/viewappointment');?>';
			location.href = currentUrl+'?type=monthly&fromdate='+start_date+'&enddate='+end_date;
		}
	}
	
</script>
<style>
.popover {
    min-width: 550px;
    max-width: 550px;
    width: 550px;
}
.popover-content{height:200px;}
.bottomBorder h4{border-bottom:1px dotted #ccc;}
.shiftbuttons {background: #F00 none repeat scroll 0% 0%;padding: 7px 16px;color: #FFF;font-weight: bold;border-radius: 13px; margin-top:10px; margin-bottom:10px;}
#shiftpatient{color:#2D2C2C;}
#monthlydata input {
	border: 1px solid #ccc;
	padding: 2px;
}
#monthlydata div{
	padding: 5px;
}
.mList ul{float:right;}
#monthlydata {
	float: right;
	clear: both;
	width: 100%;
}
</style>
