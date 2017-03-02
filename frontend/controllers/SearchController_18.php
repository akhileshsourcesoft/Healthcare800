<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\web\Session;
use common\models\User;
use common\models\Category;
use common\models\Country;
use common\models\Dayname;
use common\models\LoginForm;
use common\models\State;
use common\models\Qualification;
use common\models\ProvidersDayAvailability;
use common\models\ProvidersTimeAvailability;
use common\models\UserTimeslotBooking;
use common\models\UserRole;
use common\models\ClinicBanner;
use common\models\UserFeedback;
use yii\db\Query;

class SearchController extends Controller
{
	
	public function behaviors()
    {
          return [
          
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
	
	public function actionIndex()
	{
		return $this->render('index');
	}
	
   public function actionListing(){
	 $current_date = date("Y-m-d");
	 if(!empty($_GET['category']) && (!empty($_GET['pname']))){
		
		$servicesCat = trim(Yii::$app->request->get('category'));
		$state = trim(Yii::$app->request->get('s'));
		$city = trim(Yii::$app->request->get('c'));
		$specialty = trim(Yii::$app->request->get('sp'));
		$zip_code = trim(Yii::$app->request->get('z'));
		$pname = @explode(" ",trim(Yii::$app->request->get('pname')));
		$firstname = @$pname[0];
		$lastname = @$pname[1];
		$daylist = Dayname::find()->where(['status'=>1])->asArray()->all();
		$userListdata = User::find()->joinWith('servicesCategory')->joinWith('state')->joinWith('qualification')->joinWith('providersDayAvailabilities')->joinWith('providersDayAvailabilities.providersTimeAvailabilities')
						->where('hc_providers_day_availability.slot_date = CURDATE() OR hc_providers_day_availability.slot_date >= CURDATE()')
						->andWhere(['hc_users.user_role_id'=>'4'])
						->andWhere(['hc_users.status'=>'1'])
						->andWhere(['hc_category.category_name'=>$servicesCat])
						->andWhere(['hc_users.fname'=>$firstname])
						->andWhere(['hc_users.lname'=>$lastname])
						->andWhere(['hc_state.name'=>$state])
						->andWhere(['hc_users.city'=>$city])
						->andWhere(['hc_qualification.name'=>$specialty])
						->andWhere(['hc_users.zip_code'=>$zip_code])
						->orderBy([
							'hc_users.updated_date' => SORT_DESC,
							'hc_users.sort_order' => SORT_ASC,
						])
						->asArray()->all();
						
		$locationdata = User::find()->joinWith('servicesCategory')->joinWith('state')->joinWith('qualification')->joinWith('providersDayAvailabilities')->joinWith('providersDayAvailabilities.providersTimeAvailabilities')
						->where('hc_providers_day_availability.slot_date = CURDATE() OR hc_providers_day_availability.slot_date >= CURDATE()')
						->andWhere(['hc_users.status'=>'1'])
						->andWhere(['hc_users.user_role_id'=>'4'])
						->andWhere(['hc_category.category_name'=>$servicesCat])
						->groupBy(['hc_users.city'])
						->asArray()->all();
			return $this->render('listing',['userListdata' => $userListdata, 'locationdata'=>$locationdata, 'daylist'=>$daylist]);

	 }else if(!empty($_GET['category']) && (!empty($_GET['q']))){

			$servicesCat = trim(Yii::$app->request->get('category'));
			$searchText = trim(Yii::$app->request->get('q'));
			$daylist = Dayname::find()->where(['status'=>1])->asArray()->all();
			$userListdata = User::find()->joinWith('servicesCategory')->joinWith('state')->joinWith('qualification')->joinWith('providersDayAvailabilities')->joinWith('providersDayAvailabilities.providersTimeAvailabilities')
							->where('hc_providers_day_availability.slot_date = CURDATE() OR hc_providers_day_availability.slot_date >= CURDATE()')
							->andWhere(['hc_users.user_role_id'=>'4'])
							->andWhere(['hc_users.status'=>'1'])
							->andWhere(['hc_category.category_name'=>$servicesCat])
							->andFilterWhere(['or',
								['like', 'hc_users.fname', $searchText],
								['like', 'hc_users.lname', $searchText],
								['like', 'hc_qualification.name', $searchText],
								['like', 'hc_users.city', $searchText],
								['like', 'hc_state.name', $searchText],
								['like', 'hc_users.zip_code', $searchText],
							])
							->orderBy([
								'hc_users.updated_date' => SORT_DESC,
								'hc_users.sort_order' => SORT_ASC,
							])
							->asArray()->all();
			$locationdata = User::find()->joinWith('servicesCategory')->joinWith('state')->joinWith('qualification')->joinWith('providersDayAvailabilities')->joinWith('providersDayAvailabilities.providersTimeAvailabilities')
						->where('hc_providers_day_availability.slot_date = CURDATE() OR hc_providers_day_availability.slot_date >= CURDATE()')
						->andWhere(['hc_users.status'=>'1'])
						->andWhere(['hc_users.user_role_id'=>'4'])
						->andWhere(['hc_category.category_name'=>$servicesCat])
						->groupBy(['hc_users.city'])
						->asArray()->all();
						
				return $this->render('listing',['userListdata' => $userListdata, 'locationdata'=>$locationdata, 'daylist'=>$daylist]);

	  }else{
			$userState = trim(Yii::$app->request->post('userState'));
			$categoryName = trim(Yii::$app->request->post('categoryName'));
			$userCity = trim(Yii::$app->request->post('userCity'));
			$userSpeciallity = trim(Yii::$app->request->post('userSpeciallity'));
			$userInsurance = trim(Yii::$app->request->post('userInsurance'));
			$daylist = Dayname::find()->where(['status'=>1])->asArray()->all();
			$query = User::find()->joinWith('servicesCategory')->joinWith('state')->joinWith('qualification')->joinWith('providersDayAvailabilities')->joinWith('providersDayAvailabilities.providersTimeAvailabilities')
						->where('hc_providers_day_availability.slot_date = CURDATE() OR hc_providers_day_availability.slot_date >= CURDATE()')
						->andWhere(['hc_users.user_role_id'=>'4'])
						->andWhere(['hc_users.status'=>'1'])
						->andWhere(['hc_category.category_name'=>$categoryName])
						->orderBy([
								'hc_users.updated_date' => SORT_DESC,
								'hc_users.sort_order' => SORT_ASC,
						]);
						if(isset($userState) && (!empty($userState))){
							$query->andFilterWhere(['or',['like', 'hc_state.name', $userState]]);
						}
						if(isset($userSpeciallity) && (!empty($userSpeciallity))){
							$query->andFilterWhere(['or',['like', 'hc_qualification.name', $userSpeciallity]]);
						}
						if(isset($userCity) && (!empty($userCity))){
							$query->andFilterWhere(['or',['like', 'hc_users.city', $userCity]]);
						}
						if(isset($userInsurance) && (!empty($userInsurance))){
							$query->andFilterWhere(['or',['like', 'hc_users.insurance_no', $userInsurance]]);
						}
						
						$userListdata = $query->asArray()->all();
		$locationdata = User::find()->joinWith('servicesCategory')->joinWith('state')->joinWith('qualification')->joinWith('providersDayAvailabilities')->joinWith('providersDayAvailabilities.providersTimeAvailabilities')
				->where('hc_providers_day_availability.slot_date = CURDATE() OR hc_providers_day_availability.slot_date >= CURDATE()')
				->andWhere(['hc_users.status'=>'1'])
				->andWhere(['hc_users.user_role_id'=>'4'])
				->andWhere(['hc_category.category_name'=>$categoryName])
				->groupBy(['hc_users.city'])
				->asArray()->all();
						
			  	return $this->render('listing', ['userListdata' => $userListdata,'userSpeciallity'=>$userSpeciallity,'userCity'=>$userCity, 'userState'=>$userState, 'locationdata'=>$locationdata, 'daylist'=>$daylist]); 
	  }
   }
   
   
   public function actionProviderslisting(){
	    $current_date = date("Y-m-d");  

	    isset($_POST['location']) && $_POST['location']!=''?$location = $_POST['location']:$location = '';  
	    isset($_POST['categoryName']) && $_POST['categoryName']!=''?$categoryName = $_POST['categoryName']:$categoryName = '';  
	    isset($_POST['searchText']) && $_POST['searchText']!=''?$searchText = $_POST['searchText']:$searchText = '';  
	    isset($_POST['daysid']) && $_POST['daysid']!=''?$daysid = $_POST['daysid']:$daysid = '';
	    isset($_POST['minvalue']) && $_POST['minvalue']!=''?$minvalue = $_POST['minvalue']:$minvalue = '';
	    isset($_POST['maxvalue']) && $_POST['maxvalue']!=''?$maxvalue = $_POST['maxvalue']:$maxvalue = '';
	    isset($_POST['minimumtime']) && $_POST['minimumtime']!=''?$minimumtime = date("H:i", strtotime($_POST['minimumtime'])):$minimumtime = '';
	    isset($_POST['maximumtime']) && $_POST['maximumtime']!=''?$maximumtime = date("H:i", strtotime($_POST['maximumtime'])):$maximumtime = '';
	    isset($_POST['searchState']) && $_POST['searchState']!=''?$searchState = $_POST['searchState']:$searchState = '';
	    isset($_POST['searchCity']) && $_POST['searchCity']!=''?$searchCity = $_POST['searchCity']:$searchCity = '';

		$userListdata = User::find()->joinWith('servicesCategory')->joinWith('state')->joinWith('qualification')->joinWith('providersDayAvailabilities')->joinWith('providersDayAvailabilities.providersTimeAvailabilities')
				->where('hc_providers_day_availability.slot_date = CURDATE() OR hc_providers_day_availability.slot_date >= CURDATE()')		
				->andWhere(['hc_category.category_name'=>$categoryName])
				->andWhere(['hc_users.status'=>1,'hc_users.user_role_id'=>4])
				->orderBy([
					'hc_users.updated_date' => SORT_DESC,
					'hc_users.sort_order' => SORT_ASC,
				]);
		
		$comma = ','; 
		if(!empty($location)){
			if(preg_match('/\b' . $comma . '\b/', $location)){
                $location = explode(',', $location);
                $userListdata = $userListdata->andWhere(['IN', 'hc_users.id', $location]);
            }else{
				$userListdata = $userListdata->andWhere(['hc_users.id'=>$location]);
			}
		}
		/*if(empty($location)){
			if(preg_match('/\b' . $comma . '\b/', $location)){
                $location = explode(',', $location);
                $userListdata = $userListdata->andWhere(['IN', 'hc_users.id', $location]);
            }else{
				$userListdata = $userListdata->andWhere(['hc_users.id'=>$location]);
			}
		}*/
		if(!empty($daysid)){
            if(preg_match('/\b' . $comma . '\b/', $daysid)){
                $dayid = explode(',', $daysid);
                $userListdata = $userListdata->andWhere(['IN', 'hc_providers_day_availability.day_id', $dayid]);
            }else if($daysid!=8){
				$userListdata = $userListdata->andWhere(['hc_providers_day_availability.day_id'=>$daysid]);
			}
		}
		if(!empty($minvalue) && $minvalue!=''){
			$userListdata = $userListdata->andWhere(['>=','hc_users.fees', $minvalue]);
		}
		if(!empty($maxvalue) && $maxvalue!=''){
			$userListdata = $userListdata->andWhere(['<=','hc_users.fees', $maxvalue]);
		}
		
		if(!empty($minimumtime) && !empty($maximumtime)){
			$userListdata = $userListdata->andWhere(['between', 'hc_providers_time_availability.start_time', $minimumtime, $maximumtime ]);
		}

		$userListdata = $userListdata->asArray()->all();

	   if(count($userListdata)>0){    
		   
		 if(!empty($searchState) && (!empty($searchCity))){  
			$userlistdata = '<h2>'.$searchCity.', '.$searchState.'</h2>';
		}else{
			$userlistdata = '<h2>Search “'.$searchText.'”</h2>';
		}
		$userlistdata .= '<ul class="fliterResult">';
		foreach($userListdata as $key=>$val){ 
						
		   $feedbackTotal = UserFeedback::find()->where(['status'=>1])->andWhere(['provider_id'=>$val['id']])->count();	
		
           $userlistdata .= '<li>
              <aside class="col-md-7">
                <figure><a href="'.Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."").'">';
				$profile_image = str_replace("users/providers/","",$val['profile_image']);
				if($val['gender']==1){
					$defaultImage = 'male_icon.png';
				}else{
					$defaultImage = 'female_icon.png';
				}
				if(file_exists('uploads/'.$val['profile_image']) && (!empty($profile_image))){ 
					$userlistdata .= '<img src="'.Yii::$app->getUrlManager()->createUrl('uploads/'.$val['profile_image']).'" class="img-responsive">';
				}else{
					$userlistdata .= '<img src="'.Yii::$app->getUrlManager()->createUrl('uploads/users/providers/'.$defaultImage).'" class="img-responsive">';
				} 
				$userlistdata .= '</a></figure>
                <h3><a href="'.Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."").'">Dr. '.$val['fname'].' '.$val['lname'].'</a></h3>
                <h4>'.$val['qualification']['name'].'</h4>
                <p>'.$val['experience'].' years experience</p>
                <p>General Physician</p>
                <div class="feedBack"><a href="'.Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."").'&t=tab2"><img src="'.Yii::$app->getUrlManager()->createUrl('images/feedback.png').'">'.$feedbackTotal.' Feedback</a></div>
              </aside>
              <aside class="col-md-5">
                <div class="appointMent">
                  <p><i class="fa fa-map-marker" aria-hidden="true"></i><a href="javascript:void(0);">'.$val['state']['name'].', '.$val['city'].'</a></p>
                  <p><i aria-hidden="true" class="fa fa-dollar"></i>'.trim($val['fees']).'</p>';
                  $userlistdata .= '<p><i class="fa fa-clock-o" aria-hidden="true"></i>';
                  $dayAvails = '';
                  $currentDate = date("Y-m-d"); 
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
						$userlistdata .= $daylist->day_name;
					}
					if(!empty($currentDay)){
						$daylist = Dayname::find()->where(['id'=>$endDay])->andWhere(['status'=>1])->one();
						if(date("l", strtotime($currentDate))!=$daylist->day_name){
							$userlistdata .= '- '.$daylist->day_name;
						}
					}
                  $userlistdata .= '<span>';
						 if(!empty($slotStartTime)){  $userlistdata .=  $slotStartTime;}
						 if(!empty($slotEndTime)){  $userlistdata .= '- '.$slotEndTime;}
                  $userlistdata .= '</span></p>';
                  $userlistdata .= '<button type="button" class="show_hide" id="bookappointment_'.$val['id'].'" onclick="bookProvidersApp(this.id);">Book Appointment</button>
                </div>
              </aside>
              <div class="slidingDiv schedule-calender" id="timeSlotAppointment_'.$val['id'].'" style="display:none;">
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
                                <tbody>';
                                date_default_timezone_set('Asia/Calcutta');
								$current_time = date("H:i");
								$currentDate = date("Y-m-d"); 
								foreach($val['providersDayAvailabilities'] as $providerVal){
									if($providerVal['slot_date']>=$currentDate){
                                 $userlistdata .= '<tr>
                                    <td><div class="table_date">';
											if($providerVal['slot_date']==$currentDate){  $userlistdata .= '<span class="today">Today</span>';}
											$userlistdata .= '<span>'.strtoupper(date("l", strtotime($providerVal['slot_date']))).'</span>
											<span>'.strtoupper(date("d M", strtotime($providerVal['slot_date']))).'</span>
										</div>
                                    </td>
                                    <td>
                                    	<ul class="table_inside_datat">';
											$mcounter=1;
											foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){
												$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one(); 
												if($timeSlotvalue['start_time'] >= '9:00' || $timeSlotvalue['start_time'] < '10:00'){
													if($timeSlotvalue['start_time']<=$current_time || $providerVal['slot_date']==$currentDate){ 
														$userlistdata .= '<li><span>'.$timeSlotvalue['start_time'].' AM</span></li>';
													}else{ 
													if($timeSlotvalue['id']!=$timeSlotBookings['time_slot_id']){ 
													$userlistdata .= '<li><a href="'.Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."&ts=".$timeSlotvalue['id']."").'">'.$timeSlotvalue['start_time'].' AM</a></li>';
													}else{
													$userlistdata .= '<li><span>'.$timeSlotvalue['start_time'].' AM</span></li>';	
													}
												  }
												} 
										    $mcounter++;}
											
                                         $userlistdata .= '</ul>
                                    </td>
                                    <td>
                                    	<ul class="table_inside_datat">';
											$mcounter=1;
											foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){ 
												$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one(); 
												if($timeSlotvalue['start_time'] >= '10:00' && $timeSlotvalue['start_time'] < '12:00'){ 
													if($timeSlotvalue['start_time']<=$current_time && $providerVal['slot_date']==$currentDate){ 
														 $userlistdata .= '<li><span>'.$timeSlotvalue['start_time'].' AM</span></li>';
													 }else{
														 if($timeSlotvalue['id']!=$timeSlotBookings['time_slot_id']){  
														 $userlistdata .= '<li><a href="'.Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."").'">'.$timeSlotvalue['start_time'].' AM</a></li>';
													 }else{
														$userlistdata .= '<li><span>'.$timeSlotvalue['start_time'].' AM</span></li>';	 
													 }
												   }
												  }
										    $mcounter++;}
											
										$userlistdata .= '</ul>
                                    </td>
                                    <td>
                                       <ul class="table_inside_datat">';
                                        	$acounter=1;
                                        	foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){ 
												$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one();
												if($timeSlotvalue['start_time'] >= '12:00' && $timeSlotvalue['start_time'] <= '13:50'){ 
													 if($timeSlotvalue['start_time']<=$current_time && $providerVal['slot_date']==$currentDate){ 
														$userlistdata .= '<li><span>'.$timeSlotvalue['start_time'].' PM</span></li>';
													 }else{
														if($timeSlotvalue['id']!=$timeSlotBookings['time_slot_id']){  
														$userlistdata .= '<li><a href="'.Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."").'">'.$timeSlotvalue['start_time'].'
															 PM</a></li>';
														}else{
														$userlistdata .= '<li><span>'.$timeSlotvalue['start_time'].' PM</span></li>';	 
													 }
												   }
												 } 
										    	$acounter++;} 
                                       $userlistdata .= '</ul>
                                    </td>
                                    <td>
                                   		 <ul class="table_inside_datat">';
											$acounter=1;
											foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){ 
												$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one();
												if($timeSlotvalue['start_time'] >= '14:00' && $timeSlotvalue['start_time'] < '16:00'){ 
													if($timeSlotvalue['start_time']<=$current_time && $providerVal['slot_date']==$currentDate){ 
														$userlistdata .= '<li><span>'.$timeSlotvalue['start_time'].' PM</span></li>';
													 }else{ 
													  if($timeSlotvalue['id']!=$timeSlotBookings['time_slot_id']){  
														$userlistdata .= '<li><a href="'.Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."").'">'.$timeSlotvalue['start_time'].' PM</a></li>';
													}else{
														$userlistdata .= '<li><span>'.$timeSlotvalue['start_time'].' PM</span></li>';
													}
												  }
												 }
										    $acounter++;}
                                        $userlistdata .= '</ul>
                                    </td>
                                    <td>
                                    <ul class="table_inside_datat">';
                                        	$ecounter=1;
                                        	foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){
												$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one(); 
												if($timeSlotvalue['start_time'] >= '16:00' && $timeSlotvalue['start_time'] <= '17:50'){ 
													if($timeSlotvalue['start_time']<=$current_time && $providerVal['slot_date']==$currentDate){ 
														$userlistdata .= '<li><span>'.$timeSlotvalue['start_time'].' PM</span></li>';
												    }else{ 
													  if($timeSlotvalue['id']!=$timeSlotBookings['time_slot_id']){  
														$userlistdata .= '<li><a href="'.Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."").'">'.$timeSlotvalue['start_time'].' PM</a></li>';
													  }else{
														$userlistdata .= '<li><span>'.$timeSlotvalue['start_time'].' PM</span></li>';
													  }
												    }
												} 
										    	$ecounter++;} 
                                        $userlistdata .= '</ul>
                                    </td>
                                    <td><ul class="table_inside_datat">'; 
                                        	$ecounter=1;
                                        	foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){ 
												$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one(); 
												if($timeSlotvalue['start_time'] >= '18:00' && $timeSlotvalue['start_time'] < '20:00'){ 
													if($timeSlotvalue['start_time']<=$current_time && $providerVal['slot_date']==$currentDate){ 
														$userlistdata .= '<li><span>'.$timeSlotvalue['start_time'].' PM</span></li>';
													 }else{ 
													if($timeSlotvalue['id']!=$timeSlotBookings['time_slot_id']){  
														$userlistdata .= '<li><a href="'.Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."").'">'.$timeSlotvalue['start_time'].' PM</a></li>';
														}else{
														$userlistdata .= '<li><span>'.$timeSlotvalue['start_time'].' PM</span></li>';
													  }
													} 
												} 
										    	$ecounter++;} 
                                        $userlistdata .= '</ul>
                                    </td>
                                    <td><ul class="table_inside_datat">';
                                        	$ncounter=1;
                                        	foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){ 
												$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one();
												if($timeSlotvalue['start_time'] >= '20:00' && $timeSlotvalue['start_time'] <= '21:50'){ 
													if($timeSlotvalue['start_time']<=$current_time && $providerVal['slot_date']==$currentDate){
														$userlistdata .= '<li><span>'.$timeSlotvalue['start_time'].' PM</span></li>';
													 }else{ 
													   if($timeSlotvalue['id']!=$timeSlotBookings['time_slot_id']){  
														$userlistdata .= '<li><a href="'.Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."").'">'.$timeSlotvalue['start_time'].' PM</a></li>';
														}else{
														$userlistdata .= '<li><span>'.$timeSlotvalue['start_time'].' PM</span></li>';
													  }
													 }
												} 
										    	$ncounter++;} 
                                        $userlistdata .= '</ul>
                                    </td>
                                    <td><ul class="table_inside_datat">';
                                        	$ncounter=1;
                                        	foreach($providerVal['providersTimeAvailabilities'] as $key=>$timeSlotvalue){ 
												$timeSlotBookings = UserTimeslotBooking::find()->where(['time_slot_id'=>$timeSlotvalue['id']])->one();
												if($timeSlotvalue['start_time'] >= '22:00' && $timeSlotvalue['start_time'] < '24:00'){ 
													if($timeSlotvalue['start_time']<=$current_time && $providerVal['slot_date']==$currentDate){ 
														$userlistdata .= '<li><span>'.$timeSlotvalue['start_time'].' PM</span></li>';
													 }else{ 
													 if($timeSlotvalue['id']!=$timeSlotBookings['time_slot_id']){  
														$userlistdata .= '<li><a href="'.Yii::$app->getUrlManager()->createUrl("provider/detail?pid=".$val['password_reset_token']."&date=".$providerVal['slot_date']."&btime=".$timeSlotvalue['start_time']."&ts=".$timeSlotvalue['id']."").'">'.$timeSlotvalue['start_time'].' PM</a></li>';
														}else{
														$userlistdata .= '<li><span>'.$timeSlotvalue['start_time'].' PM</span></li>';
													  }
													 } 
												 } 
										    	$ncounter++;} 
                                       $userlistdata .= '</ul>
                                    </td>
                                  </tr>';
                                  } 
								}
                             $userlistdata .= ' </tbody>
                              </table>
						</div>
					</div>
            </li>';
			 } 
			$userlistdata .= '</ul>';
			echo $userlistdata;
		}
	}
	public function beforeAction($action) {
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}
}
