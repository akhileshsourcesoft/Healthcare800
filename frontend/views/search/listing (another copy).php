<!-- Search Bar -->
<?php 
	$categoryServicesname = Yii::$app->request->get('category');
	$stateName = '';
	$cityName = '';
	$speciallityName = '';
	if(!empty(Yii::$app->request->get('s'))){
		 $stateName = Yii::$app->request->get('s');
	}else if(!empty($userState)){
		$stateName = $userState;
	}
	if(!empty(Yii::$app->request->get('c'))){
		 $cityName = Yii::$app->request->get('c');
	}else if(!empty($userCity)){
		$cityName = $userCity;
	}
	if(!empty(Yii::$app->request->get('sp'))){
		 $speciallityName = Yii::$app->request->get('sp');
	}else if(!empty($userSpeciallity)){
		$speciallityName = $userSpeciallity;
	}

?>
<section class="searchBar">
  <div class="container">
    <div class="row">
      <div class="topSearch">
        <form name="listingForm" id="listingForm" method="POST" action="<?php echo Yii::$app->getUrlManager()->createUrl('search/listing?category='.$categoryServicesname.'');?>">
         <div>
            <input name="userState" id="userState" type="text" value="<?php echo $stateName;?>" placeholder="State">
            <input type="hidden" name="categoryName" id="categoryName" value="<?php if(!empty(Yii::$app->request->get('category'))){ echo Yii::$app->request->get('category');}?>">
          </div>
          <div>
            <input name="userCity" id="userCity" type="text" value="<?php echo $cityName;?>" placeholder="Location">
          </div>
          <div>
            <input name="userSpeciallity" id="userSpeciallity" type="text" value="<?php echo $speciallityName;?>" placeholder="Speciallity">
          </div>
          <div>
            <input name="userInsurance" id="userInsurance" type="text" placeholder="Insurance">
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
                    <li>
                      <label>
                        <input type="checkbox" name="location[]" value="">
                        Aberdeen</label>
                    </li>
                    <li>
                      <label>
                        <input type="checkbox" name="location[]" value="">
                        Airway Heights</label>
                    </li>
                    <li>
                      <label>
                        <input type="checkbox" name="location[]" value="">
                        Algona</label>
                    </li>
                    <li>
                      <label>
                        <input type="checkbox" name="location[]" value="">
                        Brier</label>
                    </li>
                    <li>
                      <label>
                        <input type="checkbox" name="location[]" value="">
                        Camas</label>
                    </li>
                    <li>
                      <label>
                        <input type="checkbox" name="location[]" value="">
                        Clarkston</label>
                    </li>
                    <li>
                      <label>
                        <input type="checkbox" name="location[]" value="">
                        Electric City</label>
                    </li>
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
                <li><a href="#">Any</a></li>
                <li><a href="#">M</a></li>
                <li><a href="#" class="active">T</a></li>
                <li><a href="#">W</a></li>
                <li><a href="#">T</a></li>
                <li><a href="#">F</a></li>
                <li><a href="#">S</a></li>
                <li><a href="#">S</a></li>
                </ul>
                  <ul class="quantity_box">
                    <li>
                      <input type="text" class="text_box1" name="lname" placeholder="11:00 AM">
                    </li>
                    <li>-</li>
                    <li>
                      <input type="text" class="text_box1" name="lname" placeholder="11:30 PM">
                    </li>
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
                      <input type="text" class="text_box1" name="lname" placeholder="$100">
                    </li>
                    <li>-</li>
                    <li>
                      <input type="text" class="text_box1" name="lname" placeholder="$200">
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <input name="" type="submit" value="filter">
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
      <?php if(count($userListdata)>0){ ?>
      <aside class="col-md-9 col-sm-8">
        <div class="listingRight">
		<?php if(!empty(Yii::$app->request->get('c')) && (!empty(Yii::$app->request->get('s')))){ ?>
			<h2><?php  echo Yii::$app->request->get('c').', '.Yii::$app->request->get('s');?></h2>
        <?php }else if(!empty(Yii::$app->request->get('q'))){ ?>
			<h2>Search “<?php echo Yii::$app->request->get('q');?>”</h2>
		<?php }else if(!empty($cityName)){ ?>
			 <h2>Search “<?php echo $cityName;?>”</h2>
		<?php }else if(!empty($stateName)){ ?>
			 <h2>Search “<?php echo $stateName;?>”</h2>
		<?php }else if(!empty($stateName) &&(!empty($cityName))){ ?>
			 <h2><?php echo $cityName.', '.$stateName;?></h2>
		<?php } ?>
          <ul class="fliterResult">
			<?php foreach($userListdata as $key=>$val){ ?>
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
					<img src="<?php echo Yii::$app->getUrlManager()->createUrl('uploads/'.$val['profile_image']);?>" class="img-responsive">
				<?php }else{ ?>
					<img src="<?php echo Yii::$app->getUrlManager()->createUrl('uploads/users/providers/'.$defaultImage);?>" class="img-responsive">
				<?php } ?>
				</a></figure>
                <h3><a href="javascript:void(0);">Dr. <?php echo $val['fname'].' '.$val['lname'];?></a></h3>
                <h4><?php echo $val['qualification']['name'];?></h4>
                <p><?php echo $val['experience'];?> years experience</p>
                <p>General Physician</p>
                <div class="feedBack"><a href="#"><img src="<?php echo Yii::$app->getUrlManager()->createUrl('images/feedback.png');?>">12 Feedback</a></div>
              </aside>
              <aside class="col-md-5">
                <div class="appointMent">
                  <p><i class="fa fa-map-marker" aria-hidden="true"></i><a href="javascript:void(0);"><?php echo $val['state']['name'].', '.$val['city'];?></a></p>
                  <p><i aria-hidden="true" class="fa fa-dollar"></i><?php echo '$'.trim($val['fees']);?></p>
                  <p><i class="fa fa-clock-o" aria-hidden="true"></i>Mon - Sat<span>11:00AM- 8:30 PM</span></p>
                  <button type="button" class="show_hide" id="bookappointment_<?php echo $val['id'];?>">Book Appointment</button>
                </div>
              </aside>
              <div class="slidingDiv schedule-calender" id="timeSlotAppointment_<?php echo $val['id'];?>">
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
								$currentDate = date("Y-m-d"); 
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
												if($timeSlotvalue['start_time'] >= '09:00' && $timeSlotvalue['start_time'] <= '10:20'){ 
											
												?>
												<li><span><?php echo date("g:i A", strtotime($timeSlotvalue['start_time']));?> </span></li>
											<?php }
											 
										    $mcounter++;}
											?>
                                        </ul>
                                    </td>
                                    <td>
                                    	<ul class="table_inside_datat">
											<?php 
											$mcounter=1;
											foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){ 
												if($timeSlotvalue['start_time'] > '10:20' && $timeSlotvalue['start_time'] < '12:00'){ 
											
												?>
												<li><span><?php echo date("g:i A", strtotime($timeSlotvalue['start_time']));?> </span></li>
											<?php }
											 
										    $mcounter++;}
											?>
										</ul>
                                    </td>
                                    <td>
                                       <ul class="table_inside_datat">
                                        	<?php 
                                        	$acounter=1;
                                        	foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){ 
												if($timeSlotvalue['start_time'] >= '12:00' && $timeSlotvalue['start_time'] <= '13:50'){ ?>
												<li><span><?php echo date("g:i A", strtotime($timeSlotvalue['start_time']));?></span></li>
											<?php } 
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
												?>
												<li><span><?php echo date("g:i A", strtotime($timeSlotvalue['start_time']));?> </span></li>
											<?php }
											 
										    $acounter++;}
											?>
                                        </ul>
                                    </td>
                                    <td>
                                    <ul class="table_inside_datat">
                                        	<?php 
                                        	$ecounter=1;
                                        	foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){ 
												if($timeSlotvalue['start_time'] >= '16:00' && $timeSlotvalue['start_time'] < '20:00'){ ?>
												<li><span><?php echo date("g:i A", strtotime($timeSlotvalue['start_time']));?></span></li>
											<?php } 
										    	$ecounter++;} 
											?>
                                        </ul>
                                    </td>
                                    <td><ul class="table_inside_datat">
                                        	 <li style="width:50px;">&nbsp;</li>
                                        </ul>
                                    </td>
                                    <td><ul class="table_inside_datat">
                                        	<?php 
                                        	$ncounter=1;
                                        	foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){ 
												if($timeSlotvalue['start_time'] >= '20:00' && $timeSlotvalue['start_time'] < '24:00'){ ?>
												<li><span><?php echo date("g:i A", strtotime($timeSlotvalue['start_time']));?></span></li>
											<?php } 
										    	$ncounter++;} 
											?>
                                        </ul>
                                    </td>
                                    <td><ul class="table_inside_datat">
                                        	 <li style="width:50px;">&nbsp;</li>
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
				No Result found.	
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
    })
	
</script>
<script type="text/javascript">
$(document).ready(function(){
	$(".show_hide").show();
	$(".slidingDiv").hide();
	$('.show_hide').click(function(){
		var appId = $(this).attr('id').split("_");
		$("#timeSlotAppointment_"+appId[1]).slideToggle();
	});
});
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

</script>
</body>
</html>
