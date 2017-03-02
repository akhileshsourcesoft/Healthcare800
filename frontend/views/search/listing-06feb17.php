<?php
use common\models\User;
use common\models\Dayname;
use common\models\UserFeedback;
use common\models\UserTimeslotBooking;
use kartik\time\TimePicker;

	$categoryServicesname = Yii::$app->request->get('category');
	$stateName = '';
	$cityName = '';
	$speciallityName = '';
	$insuranceName = '';
	if(!empty($_GET['s'])){
		 $stateName = Yii::$app->request->get('s');
	}else if(!empty($userState)){
		$stateName = $userState;
	}
	if(!empty($_GET['c'])){
		 $cityName = Yii::$app->request->get('c');
	}else if(!empty($userCity)){
		$cityName = $userCity;
	}
	if(!empty($_GET['sp'])){
		 $speciallityName = Yii::$app->request->get('sp');
	}else if(!empty($userSpeciallity)){
		$speciallityName = $userSpeciallity;
	}
	if(!empty($userInsurance)){
		$insuranceName = $userInsurance;
	}
	$currentDate = date("Y-m-d"); 
?>
<section class="searchBar">
  <div class="container">
    <div class="row">
      <div class="topSearch">
        <form name="listingForm" id="listingForm" method="POST" action="<?php echo Yii::$app->getUrlManager()->createUrl('search/listing?category='.$categoryServicesname.'');?>">
         <div>
            <input name="userState" id="userState" type="text" value="<?php echo $stateName;?>" placeholder="State">
            <input type="hidden" name="categoryName" id="categoryName" value="<?php if(!empty($_GET['category'])){ echo Yii::$app->request->get('category');}?>">
          </div>
          <div>
            <input name="userCity" id="userCity" type="text" value="<?php echo $cityName;?>" placeholder="Location">
          </div>
          <div>
            <input name="userSpeciallity" id="userSpeciallity" type="text" value="<?php echo $speciallityName;?>" placeholder="Speciallity">
          </div>
          <div>
            <input name="userInsurance" id="userInsurance" type="text" value="<?php echo $insuranceName;?>" placeholder="Insurance">
          </div>
          <div>
            <input name="searchListingbtn" id="searchListingbtn" type="submit">
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<!-- Search Bar --> 
<!-- Listing Panel -->
<section class="listingPanel">
  <div class="container">
    <div class="row">
      <aside class="col-md-3 col-sm-4">
        <div class="listingLeft">
        <button type="button" id="catBtn" class="hidden-sm hidden-md hidden-lg">Filters</button>
        <div id="categoRies">
          <div class="panel-group" id="accordion">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Location<i class="indicator fa fa-minus  pull-right"></i> </h4>
              </div>
              <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                  <ul>
				<?php 
                  if(count($userListdata)>0){  
					 $loccount=1;
					 foreach($locationdata as $locationVal){ 
						 $userCitylist = User::find()->select('id')->where(['city'=>$locationVal['city'], 'status'=>1, 'services_category_id'=>$locationVal['services_category_id']])->asArray()->all();
						 $locationsId = '';
						 $lcount=1;
						 foreach($userCitylist as $lvalue){
							 if($lcount==1){
								$locationsId = $lvalue['id'];
							}else{
								$locationsId .=','.$lvalue['id'];
							}
						 $lcount++;}
						 $checked = '';
						 if(isset($_GET['c']) && (!empty($_GET['c'])) || isset($_GET['q']) && (!empty($_GET['q']))){
							if(strtolower($locationVal['city'])==trim(strtolower(@$_GET['c']))){
								$checked = 'checked="checked"';
							}else if(strtolower($locationVal['city'])==trim(strtolower(@$_GET['q']))){
								$checked = 'checked="checked"';
							}else{
								$checked = '';
							}
						 }
					?>
                    <li>
						<label>
							<input type="checkbox" name="location[]" class="locationCheckbox" id="<?php echo $locationsId;?>" value="<?php echo $locationVal['city'];?>" <?php echo $checked;?>><?php echo $locationVal['city'];?>
						</label>
					</li>
                    <?php $loccount++; } 
                     }else{  
						echo '<li><label>No result found.</label></li>';  
					} ?>
                  </ul>
               
                </div>
              </div>
            
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Availability<i class="indicator fa fa-minus  pull-right"></i> </h4>
              </div>
              <div id="collapseTwo" class="panel-collapse collapse in">
                <div class="panel-body">
                <ul class="avaiLability">
						<li><a href="javascript:void(0);" id="dayname_8" class="dayList">Any</a></li>
					<?php foreach($daylist as $day){ ?>
						<li><a href="javascript:void(0);" id="dayname_<?php echo $day['id'];?>" class="dayList"><?php echo substr($day['day_name'],0,1);?></a></li>
					<?php } ?>
                </ul>
                  <ul class="quantity_box_timing">
                    <li>                   
                       <?php
						echo '<label>Start Time</label>';
						echo TimePicker::widget([
							'name' => 'min_time', 
							'id' => 'min_time', 
							'value' => '',
							'convertFormat' => true,
							'pluginOptions' => [
								'todayHighlight' => true
							]
						]);
					  ?>
                    </li>
                    <li>                   
                       <?php
						echo '<label>End Time</label>';
						echo TimePicker::widget([
							'name' => 'max_time', 
							'id' => 'max_time', 
							'value' => '',
							'pluginOptions' => [
								'showSeconds' => false,								
								'defaultTime' => false
							],
							'options' => [
								'class' => 'text_box1',							
							],
							
						]);
					  ?>
                    </li>
                    <li id="timemessage"></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapsethree">Consultation Fee<i class="indicator fa fa-minus  pull-right"></i> </h4>
              </div>
              <div id="collapsethree" class="panel-collapse collapse in">
                <div class="panel-body">
                  <ul class="quantity_box">
                    <li>
                      <input type="text" class="text_box1" name="min_fees" id="min_fees" placeholder="$100">
                    </li>
                    <li>-</li>
                    <li>
                      <input type="text" class="text_box1" name="max_fees" id="max_fees" placeholder="$200">
                    </li>
                    <li id="feemessage"></li>
                  </ul>
                </div>
              </div>
            </div>
            <input id="filterButton" type="button" value="filter">
          </div>
          <div class="nearBy">
            <h3>Nearby Location</h3>
            <ul>
              <li><a href="#">Physician in Elma</a></li>
              <li><a href="#">Physician in Entiat</a></li>
              <li><a href="#">Physician in Grandview</a></li>
              <li><a href="#">Physician in Kalama</a></li>
            </ul>
          </div>
        </div>
        </div>
      </aside>
      <?php
       if(count($userListdata)>0){ ?>
      <aside class="col-md-9 col-sm-8">
        <div class="listingRight" id="searchlisting">
		<?php if(!empty($_GET['c']) && (!empty($_GET['s']))){ ?>
			<h2><?php  echo Yii::$app->request->get('c').', '.Yii::$app->request->get('s');?></h2>
        <?php }else if(!empty($_GET['q'])){ ?>
			<h2>Search “<?php echo Yii::$app->request->get('q');?>”</h2>
		<?php }else if(!empty($cityName)){ ?>
			 <h2>Search “<?php echo $cityName;?>”</h2>
		<?php }else if(!empty($stateName)){ ?>
			 <h2>Search “<?php echo $stateName;?>”</h2>
		<?php }else if(!empty($stateName) &&(!empty($cityName))){ ?>
			 <h2><?php echo $cityName.', '.$stateName;?></h2>
		<?php } ?>
		<div id="providersResult">
          <ul class="fliterResult">
			<?php 
			foreach($userListdata as $key=>$val){ 
				$feedbackTotal = UserFeedback::find()->where(['status'=>1])->andWhere(['provider_id'=>$val['id']])->count();
			?>
            <li>
              <aside class="col-md-7">
                <figure><a href="javascript:void(0);">
				<?php 
				$profile_image = str_replace("users/providers/","",$val['profile_image']);
				if($val['gender']==1){
					$defaultImage = 'male_icon.png';
				}else{
					$defaultImage = 'female_icon.png';
				}
				if(file_exists('uploads/'.$val['profile_image']) && (!empty($profile_image))){ ?>
					<a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."");?>"><img src="<?php echo Yii::$app->getUrlManager()->createUrl('uploads/'.$val['profile_image']);?>" class="img-responsive"></a>
				<?php }else{ ?>
					<a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."");?>"><img src="<?php echo Yii::$app->getUrlManager()->createUrl('uploads/users/providers/'.$defaultImage);?>" class="img-responsive"></a>
				<?php } ?>
				</a></figure>
                <h3><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."");?>">Dr. <?php echo $val['fname'].' '.$val['lname'];?></a></h3>
                <h4><?php echo $val['qualification']['name'];?></h4>
                <p><?php echo $val['experience'];?> years experience</p>
                <p>General Physician</p>
                <div class="feedBack"><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."");?>&t=tab2"><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/feedback.png');?>"><?php echo $feedbackTotal;?> Feedback</a></div>
              </aside>
              <aside class="col-md-5">
                <div class="appointMent">
                  <p><i class="fa fa-map-marker" aria-hidden="true"></i><a href="javascript:void(0);"><?php echo $val['state']['name'].', '.$val['city'];?></a></p>
                  <p><i aria-hidden="true" class="fa fa-dollar"></i>
                  <?php 
                  if(!empty($val['providerUserPrices'][0]['providerFees']['fees'])){
					   echo number_format($val['providerUserPrices'][1]['providerFees']['fees'],2).'&nbsp;&nbsp;-&nbsp;&nbsp;'.number_format($val['providerUserPrices'][0]['providerFees']['fees'],2);
				   }
				  ?>
                  </p>
                  <p><i class="fa fa-clock-o" aria-hidden="true"></i>
                  <?php
                  $dayAvails = '';
					foreach($val['providersDayAvailabilities'] as $dayAvail){
						if($dayAvail['slot_date']>=$currentDate){
							$dayAvails[] = $dayAvail['day_id'];
							$slot=1;
							foreach($dayAvail['providersTimeAvailabilities'] as $timeValue){
								if($slot==1){
									$slotStartTime = $timeValue['start_time'];
								}else{
									$slotEndTime = $timeValue['start_time'];
								}
							$slot++;}
						}
					}
					$uniqueDays = array_unique($dayAvails);
					$currentDay = current($dayAvails);
					$endDay = end($dayAvails);
					if(!empty($currentDay)){
						$daylist = Dayname::find()->where(['id'=>$currentDay])->andWhere(['status'=>1])->one();
						echo $daylist->day_name;
					}
					if(!empty($currentDay)){
						$daylist = Dayname::find()->where(['id'=>$endDay])->andWhere(['status'=>1])->one();
						if(date("l", strtotime($currentDate))!=$daylist->day_name){
							echo '- '.$daylist->day_name;
						}
					}
					?>
					<span>
						<?php if(!empty($slotStartTime)){ echo $slotStartTime;}?>
						<?php if(!empty($slotEndTime)){ echo '- '.$slotEndTime;}?>
					</span>
                  </p>
                  <button type="button" class="show_hide" id="bookappointment_<?php echo $val['id'];?>" onclick="bookProvidersApp(this.id);">Book Appointment</button>
                </div>
              </aside>
              <div class="slidingDiv schedule-calender" id="timeSlotAppointment_<?php echo $val['id'];?>" style="overflow-y:scroll; height:400px; display:none;">
			<div class="prev-day table-responsive">
				<table class="table table-bordered">
					<thead>
					  <tr>
						<th>#</th>
						<th colspan="2">Morning</th>
						<th colspan="2">AFTERNOON</th>
						<th colspan="2">EVENING</th>
						<th colspan="2">NIGHT</th>
					  </tr>
					</thead>
					<tbody>
					<?php
					$timezone = new DateTimeZone("Asia/Kolkata" );
					$date = new DateTime();
					$date->setTimezone($timezone );
					$current_time = $date->format( 'H:i' );
					
					foreach($val['providersDayAvailabilities'] as $providerVal){
						if($providerVal['slot_date']>=$currentDate){
					?>
					  <tr>
						<td><div class="table_date">
								<?php if($providerVal['slot_date']==$currentDate){ echo '<span class="today">Today</span>';}?>
								<span><?php echo strtoupper(date("l", strtotime($providerVal['slot_date'])));?></span>
								<span><?php echo strtoupper(date("d M", strtotime($providerVal['slot_date'])));?></span>
							</div>
						</td>
						<td>
							<ul class="table_inside_datat">
								<?php 
								$mcounter=1;
								foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){ 
									if($timeSlotvalue['start_time'] >= '9:00' || $timeSlotvalue['start_time'] < '10:00'){ 
										if($timeSlotvalue['start_time']<=$current_time || $providerVal['slot_date']==$currentDate){ ?>
										<li><span><?php echo $timeSlotvalue['start_time'];?> AM</span></li>
									<?php }else{
									$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one(); 
										if($timeSlotvalue['id']!=$timeSlotBookings['time_slot_id']){ ?>
										<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."");?>"><?php echo $timeSlotvalue['start_time'];?> AM</a></li>
										<?php }else{ ?>
												<li><span><?php echo $timeSlotvalue['start_time'];?> AM</span></li>	
										<?php } 
										}
									} 
								$mcounter++;}
								?>
							</ul>
						</td>
						<td>
							<ul class="table_inside_datat">
								<?php 
								$mcounter=1;
								foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){ 
									if($timeSlotvalue['start_time'] >= '10:00' && $timeSlotvalue['start_time'] < '12:00'){ 
										if($timeSlotvalue['start_time']<=$current_time && $providerVal['slot_date']==$currentDate){ ?>
											<li><span><?php echo $timeSlotvalue['start_time'];?> AM</span></li>
										<?php }else{
									$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one();  
											if($timeSlotvalue['id']!=$timeSlotBookings['time_slot_id']){ ?>
											<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."");?>"><?php echo $timeSlotvalue['start_time'];?> AM</a></li>
											<?php }else{ ?>
												<li><span><?php echo $timeSlotvalue['start_time'];?> AM</span></li>	
											<?php } 
											}
									  }
								$mcounter++;}
								?>
							</ul>
						</td>
						<td>
						   <ul class="table_inside_datat">
								<?php 
								$acounter=1;
								foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){ 
									if($timeSlotvalue['start_time'] >= '12:00' && $timeSlotvalue['start_time'] <= '13:50'){ 
									 if($timeSlotvalue['start_time']<=$current_time && $providerVal['slot_date']==$currentDate){ ?>
											<li><span><?php echo $timeSlotvalue['start_time'];?> PM</span></li>
										<?php }else{ 
									$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one(); 
											if($timeSlotvalue['id']!=$timeSlotBookings['time_slot_id']){ ?>
											<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."");?>"><?php echo $timeSlotvalue['start_time'];?> PM</a></li>
											<?php }else{ ?>
												<li><span><?php echo $timeSlotvalue['start_time'];?> PM</span></li>	
											<?php } 
											 }
										 } 
									$acounter++;} 
								?>
							</ul>
						</td>
						<td>
							 <ul class="table_inside_datat">
								 <?php 
								$acounter=1;
								foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){
									if($timeSlotvalue['start_time'] >= '14:00' && $timeSlotvalue['start_time'] < '16:00'){ 
										if($timeSlotvalue['start_time']<=$current_time && $providerVal['slot_date']==$currentDate){ ?>
											<li><span><?php echo $timeSlotvalue['start_time'];?> PM</span></li>
										<?php }else{
								$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one();  
											if($timeSlotvalue['id']!=$timeSlotBookings['time_slot_id']){ ?>
											<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."");?>"><?php echo $timeSlotvalue['start_time'];?> PM</a></li>
											<?php }else{ ?>
												<li><span><?php echo $timeSlotvalue['start_time'];?> PM</span></li>	
											<?php } 
										  }
									 }
								$acounter++;}
								?>
							</ul>
						</td>
						<td>
						<ul class="table_inside_datat">
								<?php 
								$ecounter=1;
								foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){
									if($timeSlotvalue['start_time'] >= '16:00' && $timeSlotvalue['start_time'] <= '17:50'){ 
										if($timeSlotvalue['start_time']<=$current_time && $providerVal['slot_date']==$currentDate){ ?>
											<li><span><?php echo $timeSlotvalue['start_time'];?> PM</span></li>
										<?php }else{ 	
									$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one(); 
											if($timeSlotvalue['id']!=$timeSlotBookings['time_slot_id']){ ?>
											<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."");?>"><?php echo $timeSlotvalue['start_time'];?> PM</a></li>
											<?php }else{ ?>
												<li><span><?php echo $timeSlotvalue['start_time'];?> PM</span></li>	
											<?php } 
										   }
										} 
									$ecounter++;} 
								?>
							</ul>
						</td>
						<td><ul class="table_inside_datat">
								 <?php 
								$ecounter=1;
								foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){
									$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one(); 
									if($timeSlotvalue['start_time'] >= '18:00' && $timeSlotvalue['start_time'] < '20:00'){ 
										if($timeSlotvalue['start_time']<=$current_time && $providerVal['slot_date']==$currentDate){ ?>
											<li><span><?php echo $timeSlotvalue['start_time'];?> PM</span></li>
										<?php }else{ 
									$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one(); 
											if($timeSlotvalue['id']!=$timeSlotBookings['time_slot_id']){ ?>
											<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."");?>"><?php echo $timeSlotvalue['start_time'];?> PM</a></li>
											<?php }else{ ?>
												<li><span><?php echo $timeSlotvalue['start_time'];?> PM</span></li>	
											<?php } 
											} 
										  } 
									$ecounter++;} 
								?>
							</ul>
						</td>
						<td><ul class="table_inside_datat">
								<?php 
								$ncounter=1;
								foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){
									if($timeSlotvalue['start_time'] >= '20:00' && $timeSlotvalue['start_time'] <= '21:50'){ 
										if($timeSlotvalue['start_time']<=$current_time && $providerVal['slot_date']==$currentDate){ ?>
											<li><span><?php echo $timeSlotvalue['start_time'];?> PM</span></li>
										<?php }else{ 
										$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one(); 
											if($timeSlotvalue['id']!=$timeSlotBookings['time_slot_id']){ ?>
											<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."");?>"><?php echo $timeSlotvalue['start_time'];?> PM</a></li>
											<?php }else{ ?>
												<li><span><?php echo $timeSlotvalue['start_time'];?> PM</span></li>	
											<?php } 
											}
										 } 
									$ncounter++;} 
								?>
							</ul>
						</td>
						<td><ul class="table_inside_datat">
								<?php 
								
								$ncounter=1;
								foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){ 
									if($timeSlotvalue['start_time'] >= '22:00' && $timeSlotvalue['start_time'] < '24:00'){ 
										if($timeSlotvalue['start_time']<=$current_time && $providerVal['slot_date']==$currentDate){
											?>
											<li><span><?php echo $timeSlotvalue['start_time'];?> PM</span></li>
										<?php }else{ 
									$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one(); 
											if($timeSlotvalue['id']!=$timeSlotBookings['time_slot_id']){ ?>
											<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."");?>"><?php echo $timeSlotvalue['start_time'];?> PM</a></li>
											<?php }else{ ?>
												<li><span><?php echo $timeSlotvalue['start_time'];?> PM</span></li>	
											<?php } 
											} 
										} 
									$ncounter++;} 
								?>
							</ul>
						</td>
					  </tr>
					  <?php }
						}
					  ?>
					</tbody>
				  </table>
		</div>
			</div>
            </li>
			<?php } ?>
          </ul>
        </div>
          <!--<ul class="pagination">
            <li><a href="#"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
          </ul>-->
         
        </div>
      </aside>
      <?php }else{ ?>
		  <aside class="col-md-9 col-sm-8">
			<div class="listingRight">
				No result found.	
			</div>
		</aside>
	 <?php } ?>
    </div>
  </div>
</section>
<!-- Listing Panel --> 
<script>
function toggleChevron(e) {
    $(e.target)
        .prev('.panel-heading')
        .find("i.indicator")
        .toggleClass('fa fa-minus fa fa-plus');
}
$('#accordion').on('hidden.bs.collapse', toggleChevron);
$('#accordion').on('shown.bs.collapse', toggleChevron);
$('#catBtn').click(function(){
   $('#categoRies').toggle(1000);
});
</script> 
<script type="text/javascript">
	 var checkLocationval;
	 var availbilityActiveval;
	 var checkLocationval='';
	 var availbilityActiveval = '';
	 var dayid = '';
	 var maxvalue;
	 var minvalue;
	 var minimumtime;
	 var maximumtime;
    function customCheckbox(checkboxName){
        var checkBox = $('input[name="'+ checkboxName +'"]');
        $(checkBox).each(function(){
            $(this).wrap( "<span class='custom-checkbox'></span>" );
            if($(this).is(':checked')){
                $(this).parent().addClass("selected");
            }
        });
        $(checkBox).click(function(){
            $(this).parent().toggleClass("selected");     
        });
    }
   
    $(document).ready(function (){
        customCheckbox("location[]");
		$('.locationCheckbox').click(function(){
			  var checkLocation = '';
			  $('.locationCheckbox:checked').each(function(){
				  checkLocation += $(this).attr('id') + ",";
			  });
			  checkLocation = checkLocation.slice(0, -1);
			  checkLocationval = checkLocation;
			  providerslisting();
		});	 
  });
	
</script>
<script type="text/javascript">
$(document).ready(function(){
	$(".show_hide").show();
	$(".slidingDiv").hide();
	$(".avaiLability").on('click', 'li .dayList', function(){	
		var dayid = $(this).attr('id');
		var dayArr = dayid.split("_");
		if(dayArr[1]==8)
		{
			var availbilityActive = '';
			var checkLocation = '';
		   $("#dayname_1, #dayname_2, #dayname_3, #dayname_4, #dayname_5, #dayname_6, #dayname_7").removeClass('availActive');
		   $("#dayname_8").toggleClass('availActive');
		   availbilityActive = dayArr[1] + ",";		
		   availbilityActive = availbilityActive.slice(0, -1);
			availbilityActiveval = availbilityActive;
			var checkLocation = '';
			$('.locationCheckbox:checked').each(function(){
			  checkLocation += $(this).attr('id') + ",";
			});
			checkLocation = checkLocation.slice(0, -1);
			checkLocationval = checkLocation;
		}
		else 
		{
			$("#dayname_8").removeClass('availActive');
			$("#dayname_"+dayArr[1]).toggleClass('availActive');
			var availbilityActive = '';
			var checkLocation = '';
			$(".availActive").each(function(){
				var dayArray = $(this).attr('id').split("_");
					availbilityActive += dayArray[1] + ",";
			});
			availbilityActive = availbilityActive.slice(0, -1);
			availbilityActiveval = availbilityActive;
			var checkLocation = '';
			$('.locationCheckbox:checked').each(function(){
			  checkLocation += $(this).attr('id') + ",";
			});
			checkLocation = checkLocation.slice(0, -1);
			checkLocationval = checkLocation;
	      }
	      providerslisting();
	});
	
	$("#filterButton").click(function(){
		var min_fees = $("#min_fees").val();
		minvalue = min_fees;
		var max_fees = $("#max_fees").val();
		maxvalue = max_fees;
		
		var mintime = $("#min_time").val();
		var maxtime = $("#max_time").val();
		minimumtime = mintime;
		maximumtime = maxtime;
		if(mintime=='' || maxtime==''){
			$("#timemessage").text("start time & end time can not be blank.").css({'font-weight':'400','color':'#e14630'});
			$("#timemessage").show();
			return false;
		}

		if(convertTo24Hour(mintime) > convertTo24Hour(maxtime)){
			$("#timemessage").text("End time must be greater than Start time.").css({'font-weight':'400','color':'#e14630'});
			$("#timemessage").show();
		}else{
			$("#timemessage").hide();
		}
		
		if(parseInt(min_fees) > parseInt(max_fees)){
			$("#feemessage").text("Maximum fee must be greater than Minimum fee.").css({'font-weight':'400','color':'#e14630'});
			$("#feemessage").show();
		}else{
			$("#feemessage").hide();
		}
		
		var checkLocation = '';
		$('.locationCheckbox:checked').each(function(){
		  checkLocation += $(this).attr('id') + ",";
		});
		checkLocation = checkLocation.slice(0, -1);
		checkLocationval = checkLocation;
		
		providerslisting();
	});

});

function convertTo24Hour(time) {

	var hours = Number(time.match(/^(\d+)/)[1]);
	var minutes = Number(time.match(/:(\d+)/)[1]);
	var AMPM = time.match(/\s(.*)$/)[1];
	if(AMPM == "PM" && hours<12) hours = hours+12;
	if(AMPM == "AM" && hours==12) hours = hours-12;
	var sHours = hours.toString();
	var sMinutes = minutes.toString();
	if(hours<10) sHours = "0" + sHours;
	if(minutes<10) sMinutes = "0" + sMinutes;
	var tohours = sHours.toString();
	var tomins = sMinutes.toString();
	return (tohours +':'+ tomins);
}

function providerslisting(){

	if(availbilityActiveval!=undefined  || checkLocationval!=undefined || minvalue!=undefined || maxvalue!=undefined ){
		var availbilityActivevalue = availbilityActiveval;
		var checkLocationvalue = checkLocationval;
		var searchText ='';
		var categoryName = '<?php echo $categoryServicesname;?>';
		<?php
		$searchText = '';
		$searchState = '';
		$searchCity = '';
		if(isset($_GET['q']) && (!empty($_GET['q']))){
			$searchText = $_GET['q'];
		}
		if(isset($_GET['s']) && (!empty($_GET['s']))){
			$searchState = $_GET['s'];
		}
		if(isset($_GET['c']) && (!empty($_GET['c']))){
			$searchCity = $_GET['c'];
		}
		?>
		var searchText = '<?php echo $searchText;?>';
		var searchState = '<?php echo $searchState;?>';
		var searchCity = '<?php echo $searchCity;?>';
		
		var mydata = {
			'location':checkLocationvalue,
			'categoryName':categoryName, 
			'searchText':searchText,
			'daysid':availbilityActivevalue,
			'minvalue':minvalue,
			'maxvalue':maxvalue,
			'minimumtime':minimumtime,
			'maximumtime':maximumtime,
			'searchState':searchState,
			'searchCity':searchCity,
		}
	//$("#searchlisting").html('<img src="'.Yii::$app->getUrlManager()->createUrl('images/progress_image.gif').'">');
	$.ajax({
		url: '<?php echo Yii::$app->getUrlManager()->createUrl('search/providerslisting');?>',
		dataType: 'html',
		type: 'POST',
		data: mydata,
		success:function(result){
			if(result!=''){
				$("#searchlisting").html(result);
				$(".show_hide").show();
			}else{
				$("#searchlisting").html('<aside class="col-md-9 col-sm-8"><div class="listingRight">No result found.</div></aside>');
			}
		}
	});
  }
}

function bookProvidersApp(appId){
	var appId = appId.split("_");
	$("#timeSlotAppointment_"+appId[1]).slideToggle();
}
jQuery('#userState').autocomplete({
	source: function( request, response ) {
		jQuery.ajax({
			url: '<?php echo Yii::$app->getUrlManager()->createUrl("user/statelisting");?>',
			dataType: "json",
			data: {key: request.term,},
			 success: function( data ) {
				 response( jQuery.map( data, function( item ) {
					return {
						label: item,
						value: item
					}
				}));
			}
		});
	},
	autoFocus: true,
	minLength: 1
});
jQuery('#userCity').autocomplete({
	source: function( request, response ) {
		jQuery.ajax({
			url: '<?php echo Yii::$app->getUrlManager()->createUrl("user/citylisting");?>',
			dataType: "json",
			data: {citykey: request.term,},
			 success: function( data ) {
				 response( jQuery.map( data, function( item ) {
					return {
						label: item,
						value: item
					}
				}));
			}
		});
	},
	autoFocus: true,
	minLength: 1
});
jQuery('#userSpeciallity').autocomplete({
	source: function( request, response ) {
		jQuery.ajax({
			url: '<?php echo Yii::$app->getUrlManager()->createUrl("user/speciallity");?>',
			dataType: "json",
			data: {spaciallitykey: request.term, categoryName:'<?php echo $categoryServicesname;?>'},
			 success: function( data ) {
				 response( jQuery.map( data, function( item ) {
					return {
						label: item,
						value: item
					}
				}));
			}
		});
	},
	autoFocus: true,
	minLength: 1
});
jQuery('#userInsurance').autocomplete({
	source: function( request, response ) {
		jQuery.ajax({
			url: '<?php echo Yii::$app->getUrlManager()->createUrl("user/insurancecompany");?>',
			dataType: "json",
			data: {insurancekey: request.term, categoryName:'<?php echo $categoryServicesname;?>'},
			 success: function( data ) {
				 response( jQuery.map( data, function( item ) {
					return {
						label: item,
						value: item
					}
				}));
			}
		});
	},
	autoFocus: true,
	minLength: 1
});


</script>
</body>
</html>
