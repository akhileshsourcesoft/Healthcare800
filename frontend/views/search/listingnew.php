<?php 
use common\models\User;
use common\models\Dayname;
use common\models\UserFeedback;
use common\models\UserTimeslotBooking;
use kartik\time\TimePicker;
//print_r($userListnewdata);
$currentDate = date("Y-m-d"); 

?>




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
					
					foreach($userListnewdata[0]['providersDayAvailabilities'] as $providerVal){

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
										<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$userListnewdata[0]['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."");?>"><?php echo $timeSlotvalue['start_time'];?> AM</a></li>
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
											<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$userListnewdata[0]['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."");?>"><?php echo $timeSlotvalue['start_time'];?> AM</a></li>
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
											<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$userListnewdata[0]['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."");?>"><?php echo $timeSlotvalue['start_time'];?> PM</a></li>
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
											<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$userListnewdata[0]['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."");?>"><?php echo $timeSlotvalue['start_time'];?> PM</a></li>
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
											<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$userListnewdata[0]['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."");?>"><?php echo $timeSlotvalue['start_time'];?> PM</a></li>
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
											<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$userListnewdata[0]['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."");?>"><?php echo $timeSlotvalue['start_time'];?> PM</a></li>
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
											<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$userListnewdata[0]['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."");?>"><?php echo $timeSlotvalue['start_time'];?> PM</a></li>
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
											<li><a href="<?php echo Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$userListnewdata[0]['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."");?>"><?php echo $timeSlotvalue['start_time'];?> PM</a></li>
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

