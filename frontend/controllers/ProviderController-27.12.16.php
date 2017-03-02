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
use common\models\UserRole;
use common\models\ClinicBanner;
use common\models\UserTimeslotBooking;
use common\models\UserFeedback;
use common\models\SubscriberUsers;
use common\models\UserTimeslotBookingSearchprovider;
use common\models\ProviderFees;
use common\models\UserPriceType;
use common\models\InsuranceCompanies;
use common\models\HealthFacility;
use common\models\ProviderUserPrice;
use common\models\ProviderInsuranceCompany;
use common\models\PaymentHistory;
use common\models\ProviderContract;
use yii\db\Query;


class ProviderController extends Controller
{
	
	public function behaviors()
    {
          return [
          'access' => [
                'class' => AccessControl::className(),
                'only' => ['thankyou','register','confirm','dashboard','logout','updateprofilestep1','updateprofilestep2','updateprofilestep3','providerresetpassword','detail','newslettersubscriber','statelist', 'insurancecomplisting','bluepaypayment','providerresetpasswordstep4','filedownloads'],
                'rules' => [
                     [
                        'actions' => ['dashboard','logout','providerresetpassword','updateprofilestep1','updateprofilestep2','updateprofilestep3','updateprofilestep4','providerresetpasswordstep5','detail','insurancecomplisting','bluepaypayment','filedownloads'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['thankyou','register','registerstep2','registerstep3','registerstep4','confirm','detail','newslettersubscriber','statelist', 'insurancecomplisting','bluepaypayment'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],                    
                ],
                
            ],
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
	
	protected function findModel($id)
    {
        if(($model = User::findOne($id)) !== null){
            return $model;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function findProviderUserPriceModel($id, $ptypeid)
    {
	   if(($model = ProviderUserPrice::find()->where(['provider_id'=>$id])->andWhere(['user_price_type_id'=>$ptypeid])->one())!==null){
		 return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function findproviderInsurancecompanyModel($id, $pid)
    {
       if (($model = ProviderInsuranceCompany::find()->where(['insurance_companies_id'=>$id])->andWhere(['provider_id'=>$pid])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function findProviderModel($ptokenid)
    {
        if(($model = User::find()->where(['password_reset_token'=>$ptokenid])->one()) !== null){
            return $model;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function findDayAvailModel($id)
    {	
        if (($model = ProvidersDayAvailability::find()->where(['id'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
       
    protected function findTimeAvailModel($id)
    {	
        if (($model = ProvidersTimeAvailability::find()->where(['id'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function findhealthfacilityupdModel($id)
    {	

       if(($model = HealthFacility::findOne($id))!== null){
		  return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
       
    }
    
    protected function findproviderfeesModel($id)
    {	
        if($model = ProviderUserPrice::find()->where(['provider_id'=>$id])->asArray()->all()){
           return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function findhealthfacilityModel($id)
    {
       if($id){
		  $model = HealthFacility::find()->where(['health_facility_id'=>$id])->all();
		  return $model;
        } else {
						
            throw new NotFoundHttpException('The requested page does not exist.');
        }
       
    }
    protected function findcontractModel($id)
    {
       if($id){
		  $model = ProviderContract::find()->where(['provider_id'=>$id])->all();
		  return $model;
        } else {
						
            throw new NotFoundHttpException('The requested page does not exist.');
        }
       
    }
    
    protected function findpcontractualModel($id){
		if (($model = ProviderContract::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
	}

    protected function findbannerModel($id)
    {
        if (($model = ClinicBanner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
	public function actionRegister()
	{
		$model = new User();	
		$model->scenario = 'providerRegister';
		$servicesCategory = Category::find()->where(['status'=>1])->all();
		$countryModel = Country::find()->where(['status'=>1])->all();
		$statelistModel = array();
		$qualificationlistModel = Qualification::find()->where(['status'=>1])->all();
		$providerFeesModel = ProviderFees::find()->where(['status'=>1])->all();
		$userRoleData = UserRole::find(['status'=>'1'])->andWhere(['!=', 'id', '1'])->all();
		$qualificationModel = new Qualification();

		if($model->load(Yii::$app->request->post())){	
			
			$model->password_hash = Yii::$app->security->generatePasswordHash($_POST['User']['passwordhash']);
			$model->repeatpassword = $model->password_hash;
			$model->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
			$model->auth_key = Yii::$app->security->generateRandomString(); 				
			$rnd = rand(0,9999);
			$model->user_role_id = 4;
			$model->authorized = 0;
			$model->confirmation_status = 0;
			$model->status = 0;
			$model->short_desc = Yii::$app->request->post('User')['short_desc'];
		
			$imageName = $model->profile_image; 
			$model->file = UploadedFile::getInstance($model, 'profile_image');
			if(!empty($model->file)){				
				 $fileName = "users/providers/{$rnd}-{$model->file}";
				 $model->profile_image = $fileName;							 					
			}
		$checkEmailid = User::find()->where(['email'=>$model->email])->count();
		if($checkEmailid==0){	
			
			if(isset(Yii::$app->request->post('Qualification')['other_qname']) && (!empty(Yii::$app->request->post('Qualification')['other_qname']))){
				$qualificationModel->name = Yii::$app->request->post('Qualification')['other_qname'];
				$qualificationModel->status = 1;
				$qualificationModel->save(false);
				$model->qualification_id = $qualificationModel->id;
			}else{
				$model->qualification_id = Yii::$app->request->post('User')['qualification_id'];
			}
					   
			if($model->save(false)){
				if(!empty($model->file)){
					$model->file->saveAs(Yii::getAlias('@frontend') .'/web/uploads/' . $model->profile_image);
					return $this->redirect(['registerstep2?pids='.$model->password_reset_token]);
				}else{
					return $this->redirect(['registerstep2?pids='.$model->password_reset_token]);
				}
			  }
			}else{
				$emailData = "This email already exists. Please use another.";
			return $this->render('register', [
					'model' => $model,
					'userRoleData' => $userRoleData,
					'servicesCategory' => $servicesCategory,
					'statelistModel' => $statelistModel,
					'countryModel' => $countryModel,
					'providerFeesModel' => $providerFeesModel,
					'qualificationlistModel' => $qualificationlistModel,
					'qualificationModel' => $qualificationModel,
					'emailData' => $emailData,
				]);	
			}
	   }
		return $this->render('register', [
				'model' => $model,
				'userRoleData' => $userRoleData,
				'servicesCategory' => $servicesCategory,
				'countryModel' => $countryModel,
				'statelistModel' => $statelistModel,
				'providerFeesModel' => $providerFeesModel,
				'qualificationlistModel' => $qualificationlistModel,
				'qualificationModel' => $qualificationModel,
		]);

	}
	
	public function actionRegisterstep2(){
		$ptokenid = Yii::$app->request->get('pids');	
		$userpricetypeModel = UserPriceType::find()->where(['status'=>1])->all();
		$insurancecompaniesModel = InsuranceCompanies::find()->where(['status'=>1])->all();
		$providerfeesModel = ProviderFees::find()->where(['status'=>1])->all();
		$qualificationModel = new Qualification();
		$healthfacilityModel = new HealthFacility();
		$model = $this->findProviderModel($ptokenid); 

		if(Yii::$app->request->post()!=null){	
			$userPriceType = Yii::$app->request->post('UserPriceType');
			$insuranceCompaniesid = Yii::$app->request->post('InsuranceCompanies')['name'];

			foreach(@$userPriceType as $pricetype=>$provider_fees){
				$puserPriceModel = new ProviderUserPrice();
				$puserPriceModel->provider_id = $model->id;
				$puserPriceModel->user_price_type_id = $pricetype;
				$puserPriceModel->provider_fees_id = $provider_fees;
				$puserPriceModel->save(false);
			}
		if(!empty($insuranceCompaniesid))
		{	
			foreach(@$insuranceCompaniesid as $insurancekey=>$insuranceCompValue){
				$pinsuranceCompModel = new ProviderInsuranceCompany();
				$pinsuranceCompModel->provider_id = $model->id;
				$pinsuranceCompModel->insurance_companies_id = $insuranceCompValue;
				$pinsuranceCompModel->save(false);
				$id = Yii::$app->db->getLastInsertID();
			}
			if(!empty($id)){
				return $this->redirect(['registerstep3?pids='.$ptokenid]);
			} 
		}
		 else
	     {
				return $this->redirect(['registerstep3?pids='.$ptokenid]);
		 }
		
	   }
		
		return $this->render('registerstep2', [
				'insurancecompaniesModel' => $insurancecompaniesModel,
				'userpricetypeModel' => $userpricetypeModel,
				'healthfacilityModel' => $healthfacilityModel,
				'providerfeesModel' => $providerfeesModel,
		]);
	}
	
	public function actionRegisterstep3(){
		$ptokenid = Yii::$app->request->get('pids');	
		$healthfacilityModel = new HealthFacility();
		$model = $this->findProviderModel($ptokenid); 

		if(Yii::$app->request->post()!=''){	
			$model->health_facility = Yii::$app->request->post('User')['health_facility'];
			$model->save(false);
			if(!empty($_POST['healthfacilityaddress'])){
				$healthfacilityaddress = $_POST['healthfacilityaddress'];
				foreach($healthfacilityaddress as $key=>$addressValue){
					$healthfacilityModel = new HealthFacility();
					$healthfacilityModel->health_facility_id = $model->id;
					$healthfacilityModel->address = $_POST['healthfacility_address_'.$addressValue];
					$healthfacilityModel->save(false);
				}
				
				$categoryName = Category::find()->where(['status'=>1, 'category_id'=>$model->services_category_id])->one();
				$adminEmailid = User::find()->where(['status'=>1, 'id'=>1])->one();
				$stateName = State::find()->where(['status'=>1, 'state_id'=>$model->state_id])->one();
				
				$messageBody = '<table width="55%" border="0" cellpadding="5" cellspacing="1" align="center">
					<tr><td colspan="2" align="left"><strong>Hi Admin,</strong></td></tr>
					<tr><td colspan="2" align="left">One user has been registered on our site. Please find the below details.</td></tr>
					<tr><td width="25%">First Name:</td><td>'.$model->fname.'</td></tr>
					<tr><td width="25%">Last Name:</td><td>'.$model->lname.'</td></tr>
					<tr><td width="25%">Email:</td><td>'.$model->email.'</td></tr>
					<tr><td width="25%">Landline / Phone No.:</td><td>'.$model->landline.'</td></tr>';
					if(!empty($model->gender)){
						if($model->gender==1){ $gender = 'Male';}else{ $gender = 'Female';}
						$messageBody .= '<tr><td width="25%">Gender:</td><td>'.$gender.'</td></tr>';
					}
					$messageBody .= '<tr><td width="25%">Category:</td><td>'.$categoryName->category_name.'</td></tr>
					<tr><td width="25%">State:</td><td>'.$stateName->name.'</td></tr>
					<tr><td width="25%">City:</td><td>'.$model->city.'</td></tr>';
					if(!empty($model->profile_image)){
					$messageBody .= '<tr><td width="25%">Profile Image:</td><td><img width="50" height="50" src="' . \Yii::$app->params['SITE_FULL_IMG_URL'] . $model->profile_image . '" alt=""></td></tr>';
					}
					$messageBody .= '<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><strong>Thanks & Regards,<br/>HealthCare800 Team<br/><a href="http://www.healthcare800.com" target="_blank">www.healthcare800.com</a></strong></td></tr>
				</table>'; 

				$providersmail = \Yii::$app->mailer->compose()
					->setTo($adminEmailid->email)
					->setFrom([$model->email => $model->fname.' '.$model->lname])
					->setSubject('HealthCare800 Provider enquiry.')
					->setHtmlBody($messageBody)
					->send();	
					
				$id = $model->password_reset_token;
				$url='Hi <b>'. $model->fname .' '.$model->lname .',</b><p>You have been registered as “Provider” with <b>HealthCare800.</b></p>
				<p>You will get the activation link in your registered email address soon.</p><p><b>Thanks & Regards,</b><br>HealthCare800 Team,<br><a href="http://www.healthcare800.com" target="_blank">www.healthcare800.com</a></p>';
				$messageuser="$url";

				$sendemail1 = \Yii::$app->mailer->compose()
					->setTo($model->email)
					->setFrom([$adminEmailid->email => 'HealthCare800'])
					->setSubject('Your account has been created successfully.')
					->setHtmlBody($messageuser)
					->send();

				if($sendemail1){
					return $this->redirect(['registerstep4?pids='.$ptokenid]);
				}
				#--end user mail --#
			}
			
		}
		return $this->render('registerstep3', [
				'model' => $model,
				'healthfacilityModel' => $healthfacilityModel,
		]);
		
	}
	
	public function actionRegisterstep4(){
		$contractModel = new ProviderContract();
		$ptokenid = Yii::$app->request->get('pids');	
		$model = $this->findProviderModel($ptokenid); 
		if(Yii::$app->request->post()!=null){
			$hiddenaddpcontract = array();
			$hiddenaddpcontract = $_POST['hiddenaddpcontract'];
			$model->contract_title = $_POST['providercontract_title'];
			$model->save(false);
			foreach($hiddenaddpcontract as $key=>$value){
				$contractModel = new ProviderContract();
				$data = $_FILES['providercontract_images_'.$value]['name'];
				$rnd = rand(0,9999);
				$filterArr = array(" ", "@", ",", "-");
				$imageName = $_FILES['providercontract_images_'.$value]['name'];
				$imagesName = str_replace($filterArr,"-",strtolower($imageName));
				$ext = explode(".",$imagesName);						
				$tmpFilePath = $_FILES['providercontract_images_'.$value]['tmp_name'];
				if($tmpFilePath != ""){
					//Setup our new file path
					$fileName = "contactualimages/{$rnd}-{$imagesName}";
					$newFilePath = Yii::getAlias('@frontend') .'/web/uploads/contactualimages/'.$rnd.'-'.$imagesName;
					//Upload the file into the temp dir
					if(move_uploaded_file($tmpFilePath, $newFilePath)){
						$contractModel->provider_id = $model->id;
						$contractModel->images = $fileName;											
						$contractModel->save(false); 
					} else {
						print_r($modelClinicBanner->getErrors());
					} 
				}
			}
			return $this->redirect(['thankyou']);
		}
		return $this->render('registerstep4', [
				'contractModel' => $contractModel,
				'model' => $model,
		]);
	}
	
	public function actionDetail(){
		
		if(Yii::$app->request->post('UserTimeslotBooking')['ajaxtslotbook']=='ajaxTimeslotbook'){
			$providerid = Yii::$app->request->post('UserTimeslotBooking')['prtokenid'];
			$timeSlotid = Yii::$app->request->post('UserTimeslotBooking')['timeSlot'];  
		}else{
			$providerid = Yii::$app->request->get('pid');
			$timeSlotid = Yii::$app->request->get('ts');
		}
		$bookingDate = Yii::$app->request->get('date');
		$bookingTime = Yii::$app->request->get('btime');
		$modeltimeslotbooking = new UserTimeslotBooking();

 		if($modeltimeslotbooking->load(Yii::$app->request->post())){

				$fullname = Yii::$app->request->post('UserTimeslotBooking')['fullname'];
				$userlist = User::find()->where(['password_reset_token'=>$providerid, 'status'=>1, 'user_role_id'=>4])->one();
				
				$modeltimeslotbooking->fullname  = $fullname;
				$modeltimeslotbooking->email = Yii::$app->request->post('UserTimeslotBooking')['email'];
				$modeltimeslotbooking->phone_no = Yii::$app->request->post('UserTimeslotBooking')['phone_no'];
				$modeltimeslotbooking->payment_method = Yii::$app->request->post('UserTimeslotBooking')['payment_method'];
				$modeltimeslotbooking->booking_date  = Yii::$app->request->post('UserTimeslotBooking')['bookingdate'];
				$modeltimeslotbooking->booking_time  = Yii::$app->request->post('UserTimeslotBooking')['bookingtime'];
				$modeltimeslotbooking->provider_id = $userlist->id;
				$modeltimeslotbooking->user_id = Yii::$app->user->id;
				$modeltimeslotbooking->booking_number = mt_rand(1000000000, 9999999999);
				$modeltimeslotbooking->booking_status_id = 2;
				$modeltimeslotbooking->time_slot_id = $timeSlotid; 
				$modeltimeslotbooking->user_type_id  = Yii::$app->request->post('UserTimeslotBooking')['user_type_id'];
				$modeltimeslotbooking->insuranceid_card  = Yii::$app->request->post('UserTimeslotBooking')['insuranceid_card'];
				$modeltimeslotbooking->insurance_comp_address  = Yii::$app->request->post('UserTimeslotBooking')['insurance_comp_address'];
				$modeltimeslotbooking->group_insurance  = Yii::$app->request->post('UserTimeslotBooking')['group_insurance'];

				if(Yii::$app->request->post('UserTimeslotBooking')['payment_method']==1){
					$insuranceCompid = InsuranceCompanies::find()->where(['status'=>1])->andWhere(['name'=>Yii::$app->request->post('UserTimeslotBooking')['insurance_companies_id']])->one();
					$modeltimeslotbooking->insurance_companies_id  = $insuranceCompid->id;
					$modeltimeslotbooking->deductibles = Yii::$app->request->post('UserTimeslotBooking')['deductibles'];
					$modeltimeslotbooking->co_payment = Yii::$app->request->post('UserTimeslotBooking')['co_payment'];
				}else{
					$modeltimeslotbooking->amount = Yii::$app->request->post('UserTimeslotBooking')['amount'];
					$modeltimeslotbooking->booking_pay_type  = Yii::$app->request->post('UserTimeslotBooking')['booking_pay_type'];
					if($modeltimeslotbooking->booking_pay_type=='B'){
						$cardNumberArr = array();
						$cardNumber = Yii::$app->request->post('UserTimeslotBooking')['card_number'];
						$cardNumberArr = explode("-",$cardNumber);
						$bluepaycardnumber = $cardNumberArr[0].$cardNumberArr[1].$cardNumberArr[2].$cardNumberArr[3];
						$modeltimeslotbooking->card_number  = base64_encode($bluepaycardnumber);
						$modeltimeslotbooking->card_name  = Yii::$app->request->post('UserTimeslotBooking')['card_name'];
						$card_number = $bluepaycardnumber;
						$expiry_month = Yii::$app->request->post('UserTimeslotBooking')['expiry_month'];
						$expiry_year = Yii::$app->request->post('UserTimeslotBooking')['expiry_year'];
						$modeltimeslotbooking->expiry_date  = $expiry_month.'|'.$expiry_year;
						$expiry_date  = $expiry_month.$expiry_year;
						$modeltimeslotbooking->cvv = Yii::$app->request->post('UserTimeslotBooking')['cvv'];
						$modeltimeslotbooking->cc_type  = Yii::$app->request->post('UserTimeslotBooking')['cc_type'];
						$modeltimeslotbooking->booking_status_id = 2;
					}
				}
			    if($modeltimeslotbooking->save(false)){
				  if($modeltimeslotbooking->booking_pay_type=='P'){
					$this->redirect(\Yii::$app->urlManager->createUrl("provider/paypalpayment?bookingid=".$modeltimeslotbooking->booking_number.""));
				  }else if($modeltimeslotbooking->booking_pay_type=='B'){
					 $this->redirect(\Yii::$app->urlManager->createUrl("provider/bluepaypayment?bookingid=".$modeltimeslotbooking->booking_number.""));
				  }else{
					$this->redirect(\Yii::$app->urlManager->createUrl("thanks/paymentprocess?bookingid=".$modeltimeslotbooking->booking_number."&pay=N"));
				 }			  
			  }
		}

		if(isset($providerid) && (!empty($providerid))){
			$providerlist = User::find()->joinWith('servicesCategory')->joinWith('state')->joinWith('qualification')->joinWith('providersDayAvailabilities')->joinWith('providersDayAvailabilities.providersTimeAvailabilities')->joinWith('providerUserPrices')->joinWith('providerUserPrices.providerFees')
				->where(['password_reset_token'=>$providerid])
				->andWhere(['hc_users.status'=>1,'hc_users.user_role_id'=>4])->one();
			$providerData = User::find()->where(['password_reset_token'=>$providerid])->one();	
			$providerfeedback = UserFeedback::find()->joinWith('provider')->where(['hc_user_feedback.status'=>1])->andWhere(['provider_id'=>$providerData->id])->asArray()->all();	

		}
		return $this->render('detail',[
			'providerlist'=>$providerlist,
			'bookingDate'=>$bookingDate,
			'bookingTime'=>$bookingTime,
			'modeltimeslotbooking'=>$modeltimeslotbooking,
			'providerfeedback'=>$providerfeedback,
		]);
		
	}
	
	public function actionPaypalpayment(){
		
		$tslotbookingid = Yii::$app->request->get('bookingid'); 
		$timeslotbookingdata = UserTimeslotBooking::find()->where(['booking_number'=>$tslotbookingid])->one();
		$business_emailid = Yii::$app->params['BUSINESS_EMAILID'];
		$paypalmode = Yii::$app->params['PAYPAL_MODE'];
		$success_url = SITE_FULL_URL."thanks/paymentprocess?bookingid=".$tslotbookingid."&pay=P";			
		$cancel_url = SITE_FULL_URL."cancel/ordercancel?bookingid=".$tslotbookingid."&pay=P";
		$quantity = 1;
		$notificationIpnUrl = SITE_FULL_URL."paypalipn/paypal_ipn.php";						
		define( 'SSL_URL', 'https://www.paypal.com/cgi-bin/webscr' );
		define( 'SSL_SAND_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr' );
		$action = '';
		$action = ($paypalmode=='live') ? SSL_URL : SSL_SAND_URL;
		$form = '';
		$form .= '<html><title>www.healthcare800.com</title><head></head><body><div style="text-align:center; margin-top:30px; font-size:25px;"><p>You are being redirected to the paypal. Please don`t refresh the page.</p><img src="'.Yii::$app->homeUrl.'frontend/web/images/loader.gif"></div><form name="payment_method_form" id="payment_method_form" action=" '.$action.' " method="post">';
		$form .= '<input type="hidden" name="business" value="' . $business_emailid . '" />';
		$form .= '<input type="hidden" value="_xclick" name="cmd"/>';
		$form .= '<input type="hidden" name="charset" value="utf-8" />';
		$form .= '<input type="hidden" name="lc" value="US" />';
		$form .= '<input type="hidden" name="bn" value="Business_BuyNow_WPS_SE" />';
		$form .= '<input type="hidden" name="item_name" value="Appointment booking at '.date("Y M d",strtotime($timeslotbookingdata->booking_date)).'  '.$timeslotbookingdata->booking_time.'" />';
		$form .= '<input type="hidden" name="custom" value=" '.$timeslotbookingdata->id.' " />';
		$form .= '<input type="hidden" name="first_name" value="' . $timeslotbookingdata->fullname . '" />';
		$form .= '<input type="hidden" name="payer_email" value="' . $timeslotbookingdata->email . '" />';
		$form .= '<input type="hidden" name="currency_code" value="USD" />';
		$form .= '<input type="hidden" name="amount" value="' . $timeslotbookingdata->amount . '" />';
		$form .= '<input type="hidden" name="notify_url" value="' . $notificationIpnUrl . '" />';
		$form .= '<input type="hidden" name="cancel_return" value="' . $cancel_url . '" />';
		$form .= '<input type="hidden" name="return" value="' . $success_url . '" />';
		$form .= '</form></body></html>';

		echo $form;

		echo '<script type="text/javascript">
			function formSubmit(){
				  document.getElementById("payment_method_form").submit();
			}
			window.onload=function(){ 
				  window.setTimeout(formSubmit, 3000);
			};
		</script>';
		exit(0);

	}
	
	public function actionBluepaypayment(){
		$expiry_date = array();
		$tslotbookingid = Yii::$app->request->get('bookingid'); 
		$timeslotbookingdata = UserTimeslotBooking::find()->joinWith('user')->joinWith('user.state')->joinWith('user.country')->where(['booking_number'=>$tslotbookingid])->andWhere(['hc_users.status'=>'1'])->andWhere(['hc_users.confirmation_status'=>'1'])->andWhere(['hc_users.authorized'=>'1'])->one();
		$country_name = '';
		$state_name = '';
		if(!empty($timeslotbookingdata->user->country->iso_code_3)){
			$country_name = $timeslotbookingdata->user->country->iso_code_3;
		}
		if(!empty($timeslotbookingdata->user->state->name)){
			$state_name = $timeslotbookingdata->user->state->name;
		}
		$timeslotbookingdata->card_name;
		$card_number = base64_decode($timeslotbookingdata->card_number);
		$expiry_date = explode("|",$timeslotbookingdata->expiry_date);
		$expirydate = $expiry_date[0].$expiry_date[1];
		$cc_cvvno = $timeslotbookingdata->cvv;
		$zipcode = $timeslotbookingdata->user->zip_code;
		$city = $timeslotbookingdata->user->city;
		$phoneno = $timeslotbookingdata->user->landline;
		$emailid = $timeslotbookingdata->user->email;
		
		$accID = Yii::$app->params['ACCOUNTID'];
		$secretKey = Yii::$app->params['SECRETKEY'];
		$mode = Yii::$app->params['BLUEPAYMODE'];
		$setCCInformation = array(
			'cardNumber' => $card_number, 
			'cardExpire' => $expirydate, 
			'cvv2' => $cc_cvvno
		);
		$setCustomerInformation = array(
			'firstName' => $timeslotbookingdata->user->fname, 
			'lastName' => $timeslotbookingdata->user->lname, 
			'addr1' => 'NULL', 
			'addr2' => 'NULL', 
			'city' => $city, 
			'state' => $state_name, 
			'zip' => $zipcode, 
			'country' => $country_name, 
			'phone' => $phoneno, 
			'email' => $emailid
		);

		$payment_bluepay = Yii::$app->bluepay->BluePay($accID, $secretKey, $mode);
		$payment_bluepay = Yii::$app->bluepay->setCustomerInformation($setCustomerInformation);
		$payment_bluepay = Yii::$app->bluepay->setCCInformation($setCCInformation);
		$payment_bluepay = Yii::$app->bluepay->auth($timeslotbookingdata->amount); 
		$payment_process = Yii::$app->bluepay->process(); 

		if(Yii::$app->bluepay->isSuccessfulResponse()){
			# Read response from BluePay
			if(Yii::$app->bluepay->getStatus()=='APPROVED' && Yii::$app->bluepay->getMessage()!="DUPLICATE"){
				$paymenthistorymodel = new PaymentHistory();
				$paymenthistorymodel->booking_id = $timeslotbookingdata->id;
				$paymenthistorymodel->transaction_id = Yii::$app->bluepay->getTransID();
				$paymenthistorymodel->payment_status = Yii::$app->bluepay->getStatus();
				$paymenthistorymodel->payment_date = date("Y-m-d  H:i:s", time());
				if($paymenthistorymodel->save(false)){
					$this->redirect(\Yii::$app->urlManager->createUrl("thanks/paymentprocess?bookingid=".$tslotbookingid."&pay=".$timeslotbookingdata->booking_pay_type.""));
				}
			}
	   }else{
			$paymenthistorymodel = new PaymentHistory();
			$paymenthistorymodel->booking_id = $timeslotbookingdata->id;
			$paymenthistorymodel->transaction_id = Yii::$app->bluepay->getTransID();
			$paymenthistorymodel->payment_status = Yii::$app->bluepay->getStatus();
			$paymenthistorymodel->payment_date = date("Y-m-d  H:i:s", time());
			if($paymenthistorymodel->save(false)){
				$this->redirect(\Yii::$app->urlManager->createUrl("cancel/ordercancel?bookingid=".$tslotbookingid."&pay=".$timeslotbookingdata->booking_pay_type.""));
			}
		}	
	}
	
	public function actionConfirm(){
			
		$password_token_key = Yii::$app->request->get('id');
		$userData = User::find()->where(['password_reset_token'=>$password_token_key])->one();
			if(count($userData)>0){
				$model = $this->findModel($userData->id);
				if($model->confirmation_status==0){
					$model->confirmation_status = 1;
					if($model->save(false)){
						Yii::$app->getSession()->setFlash('success','Your account has been activated.');			
						return $this->redirect(['confirm']);
					}
				}else if($model->status==1){
					$data = 'Your account has been already activated.';			
					return $this->render('confirm', ['data'=>$data]);
				}	    
			}else{
				$data = 'Your details does not exist in our records.';			
				return $this->render('confirm', ['data'=>$data]);
			}
	}
	
	 public function actionDashboard(){ 

			$id = Yii::$app->user->identity->id;
			$model = $this->findModel($id);
			$usermodel = new User();
			
			$userData = $model;
			$daylistModel = Dayname::find()->where(['status'=>1])->all();
			$statelistModel = State::find()->where(['status'=>1])->all();
			$qualificationlistModel = Qualification::find()->where(['status'=>1])->all();

			$dayModel = new Dayname();
			$dayAvailability = new ProvidersDayAvailability();
			$timeAvailability = new ProvidersTimeAvailability();
			$clinicBannerModel = new ClinicBanner();
			$qualificationModel = new Qualification();
			$searchModel = new UserTimeslotBookingSearchprovider();	
			$dataProvider = $searchModel->searchprovider(Yii::$app->request->queryParams, $id);
			
			if(Yii::$app->request->post()!=null){
					if(count(@$_POST['updateAvailability'])>0){
						$updateSlotAvailability = $_POST['updateAvailability'];
						foreach($updateSlotAvailability as $slotdate=> $slotAvailid){
							$dayAvailability = ProvidersDayAvailability::find()->where(['slot_date'=>$slotdate])->one();
							$timeAvailId = ProvidersTimeAvailability::find()->where(['day_availability_id'=>$dayAvailability->id])->all();
							foreach($timeAvailId as $value){
								if(!@in_array($value->id, $slotAvailid)){
									$this->findTimeAvailModel($value->id)->delete();		
								}
							}
							$dayAvaildata = ProvidersDayAvailability::find()->where(['slot_date'=>$slotdate])->andWhere(['provider_id'=>$model->id])->count();
							if($dayAvaildata==0){
								$this->findDayAvailModel($value->id)->delete();
							}
						} 
					}
					

					if(isset($_POST['slotTime']) && (!empty($_POST['slotTime']))){
					  foreach($_POST['slotTime'] as $key=>$val){
						 $dayAvaildata = ProvidersDayAvailability::find()->where(['slot_date'=>$key])->andWhere(['provider_id'=>$model->id])->count();
						 if($dayAvaildata>0){
							$dayAvaildata = ProvidersDayAvailability::find()->where(['slot_date'=>$key])->andWhere(['provider_id'=>$model->id])->one();
							foreach($val as $k=>$avail){
								$timeAvailability = new ProvidersTimeAvailability();
								$timeAvailability->day_availability_id = $dayAvaildata->id;		 
								$timeAvailability->start_time =	$avail;
								$timeAvailability->slotTime = $_POST['slots'][$k];
								$timeAvailability->save(false);
							}
						 }else{
							$dayAvailability = new ProvidersDayAvailability();
							$dayAvailability->day_id =  $_POST['dayId_'.$key];
							$dayAvailability->slot_date = $key;
							$dayAvailability->provider_id = $model->id;
							$dayAvailability->save(false);

							foreach($val as $k=>$avail){
								$timeAvailability = new ProvidersTimeAvailability();
								$timeAvailability->day_availability_id = $dayAvailability->id;		 
								$timeAvailability->start_time =	$avail;
								$timeAvailability->slotTime = $_POST['slots'][$k];
								$timeAvailability->save(false);
							}
						} 
				 	 }				
				  }
				
				#==============Clinic Banner=================#

				if(count(@$_POST['ClinicBanner']['hiddenid'])>0){
					$bannerHiddenid = array();
					$bannerHiddenid = @$_POST['ClinicBanner']['hiddenid'];

					$clinicBanner = ClinicBanner::find()->where(['provider_id'=>$model->id])->all();
					foreach(@$clinicBanner as $bannerId){
						if(!in_array($bannerId->id, $bannerHiddenid)){
							$this->findbannerModel($bannerId->id)->delete();		
						}
					}

					foreach($bannerHiddenid as $keys=>$bannerValue){
						if(!empty($_POST['ClinicBanner']['img_title'])){
							$modelClinicBanner = $this->findbannerModel($bannerValue);
							$modelClinicBanner->provider_id = $modelClinicBanner->provider_id;
							$modelClinicBanner->images = $modelClinicBanner->images;
							$modelClinicBanner->img_title = $_POST['ClinicBanner']['img_title'][$keys];
							$modelClinicBanner->url = $_POST['ClinicBanner']['url'][$keys];
							$modelClinicBanner->sort_order = $_POST['ClinicBanner']['sort_order'][$keys];
							$modelClinicBanner->updated_date = date("Y-m-d h:i:s");
							$modelClinicBanner->save(false);
						}
					 }

 					if(!empty($_FILES['ClinicBanner']['name']['images']) && (!empty($_POST['ClinicBanner']['image_title']))){
						foreach(@$_FILES['ClinicBanner']['name']['images'] as $ikey=>$imgVal){
							$modelClinicBanner = new ClinicBanner();
							$data = Yii::$app->request->post('ClinicBanner');

							$rnd = rand(0,9999);
							$imageName = $_FILES['ClinicBanner']['name']['images'][$ikey]; 
							$ext = explode(".",$imageName);						
							$tmpFilePath = $_FILES['ClinicBanner']['tmp_name']['images'][$ikey];

							//Make sure we have a filepath
							if($tmpFilePath != ""){
							//Setup our new file path
							$fileName = "users/providers_banner/{$rnd}-{$imageName}";
							$newFilePath = Yii::getAlias('@frontend') .'/web/uploads/users/providers_banner/'.$rnd.'-'.$imageName;
							//Upload the file into the temp dir
								if(move_uploaded_file($tmpFilePath, $newFilePath)){
									$modelClinicBanner->provider_id = $model->id;
									$modelClinicBanner->images = $fileName; 							
										if($data['sort_orders'][$ikey] == ''){
											$modelClinicBanner->sort_order = 0;
										} else {
											$modelClinicBanner->sort_order = $data['sort_orders'][$ikey];
										}		
										$modelClinicBanner->img_title = $data['image_title'][$ikey];
										$modelClinicBanner->url = $data['urls'][$ikey];						
										$modelClinicBanner->save(false); 
								} else {
									print_r($modelClinicBanner->getErrors());
								} 
							}												 
						}  
					}   	
				}
					
				if(!empty($_FILES['ClinicBanner']['name']['images']) && (!empty($_POST['ClinicBanner']['img_title']))){
					foreach(@$_FILES['ClinicBanner']['name']['images'] as $ikey=>$imgVal){
						$modelClinicBanner = new ClinicBanner();
						$data = Yii::$app->request->post('ClinicBanner');

						$rnd = rand(0,9999);
						$imageName = $_FILES['ClinicBanner']['name']['images'][$ikey]; 
						$ext = explode(".",$imageName);						
						$tmpFilePath = $_FILES['ClinicBanner']['tmp_name']['images'][$ikey];

						//Make sure we have a filepath
						if($tmpFilePath != ""){
						//Setup our new file path
						$fileName = "users/providers_banner/{$rnd}-{$imageName}";
						$newFilePath = Yii::getAlias('@frontend') .'/web/uploads/users/providers_banner/'.$rnd.'-'.$imageName;
						//Upload the file into the temp dir
							if(move_uploaded_file($tmpFilePath, $newFilePath)){
								$modelClinicBanner->provider_id = $model->id;
								$modelClinicBanner->images = $fileName; 							
									if($data['sort_order'][$ikey] == ''){
										$modelClinicBanner->sort_order = 0;
									} else {
										$modelClinicBanner->sort_order = $data['sort_order'][$ikey];
									}		
									$modelClinicBanner->img_title = $data['img_title'][$ikey];
									$modelClinicBanner->url = $data['url'][$ikey];						
									$modelClinicBanner->save(false); 
							} else {
								print_r($modelClinicBanner->getErrors());
							} 
						}												 
					}  
				}  
				
				
				#==============Clinic Banner=================#
				if($model->save(false)){
					Yii::$app->getSession()->setFlash('success', 'Record updated successfully');
					$usermodel->tabname = Yii::$app->request->post('User')['tabname']; 
					return $this->render('dashboard', [
						'model' => $model,
						'userData' => $userData,
						'dayModel' => $dayModel,
						'daylistModel' => $daylistModel,
						'dayAvailability' => $dayAvailability,
						'timeAvailability' => $timeAvailability,
						'statelistModel' => $statelistModel,
						'qualificationlistModel' => $qualificationlistModel,
						'clinicBannerModel' => $clinicBannerModel,
						'qualificationModel' => $qualificationModel,
						'usermodel' => $usermodel,
						'dataProvider' => $dataProvider,
						'searchModel' => $searchModel,

					]);
				}	
			}
			return $this->render('dashboard', [
				'model' => $model,
				'userData' => $userData,
				'dayModel' => $dayModel,
				'daylistModel' => $daylistModel,
				'dayAvailability' => $dayAvailability,
				'timeAvailability' => $timeAvailability,
				'statelistModel' => $statelistModel,
				'qualificationlistModel' => $qualificationlistModel,
				'clinicBannerModel' => $clinicBannerModel,
				'qualificationModel' => $qualificationModel,
				'usermodel' => $usermodel,
				'dataProvider' => $dataProvider,
				'searchModel' => $searchModel,

			]);
	 }
   
      public function actionUpdateprofilestep1(){

			$id = Yii::$app->user->identity->id;
			$model = $this->findModel($id);
			$servicesCategory = Category::find()->where(['status'=>1])->all();
			$countryModel = Country::find()->where(['status'=>1])->all();
			$statelistModel = State::find()->where(['status'=>1])->all();
			$qualificationlistModel = Qualification::find()->where(['status'=>1])->all();
			$qualificationModel = new Qualification();

			$oldIcon = $model->profile_image;
			$oldpassword = $model->password_hash;
		    $model->scenario = 'updateProviderDashboardprofile';
			if($model->load(Yii::$app->request->post())){								
				$model->short_desc = Yii::$app->request->post('User')['short_desc'];
				$rnd = rand(0,9999);
				$imageName = $model->profile_image; 
				$model->file = UploadedFile::getInstance($model, 'profile_image');
				if($model->file !=''){				
					$fileName="users/providers/{$rnd}-{$model->file}";				
					$model->profile_image = $fileName;
				}else{	
					$model->profile_image =$oldIcon;
				}
				
				$checkEmailid = User::find()->where(['email'=>$model->email])->andwhere(['<>', 'id', $id])->all();
				if(count($checkEmailid)==0){
				
				if(isset(Yii::$app->request->post('Qualification')['other_qname']) && (!empty(Yii::$app->request->post('Qualification')['other_qname']))){
					$qualificationModel->name = Yii::$app->request->post('Qualification')['other_qname'];
					$qualificationModel->status = 1;
					$qualificationModel->save(false);
					$model->qualification_id = $qualificationModel->id;
				}else{
					$model->qualification_id = Yii::$app->request->post('User')['qualification_id'];
				}
  
				if($_POST['User']['passwordhash'] != 'password') {	 
					$model->password_hash = Yii::$app->security->generatePasswordHash($_POST['User']['passwordhash']);	
					$model->repassword= $model->password_hash;	
				}else{
					$model->password_hash=$oldpassword;
					$model->repassword=$oldpassword;	
				}
				if($model->file != ''){ 
					$model->file->saveAs(Yii::getAlias('@frontend') .'/web/uploads/' . $model->profile_image);
					if($oldIcon != '' && file_exists(Yii::getAlias('@frontend') .'/web/uploads/'.$oldIcon)){		
						$oldfile =  Yii::getAlias('@frontend') .'/web/uploads/'.$oldIcon;
						 unlink($oldfile); 
					}
				}else{
					 $model->profile_image =$oldIcon;
				}
				$model->updated_date = date("Y-m-d  H:i:s", time());
				if($model->save(false)){
						Yii::$app->getSession()->setFlash('success', 'Record updated successfully');
						return $this->redirect(['updateprofilestep1']);
				}
			  }else{
				Yii::$app->getSession()->setFlash('success', 'This email already exists. Please use another.');
				return $this->render('updateprofilestep1', [
					'model' => $model,
					'countryModel' => $countryModel,
					'statelistModel' => $statelistModel,
					'qualificationlistModel' => $qualificationlistModel,
					'qualificationModel' => $qualificationModel,
					'servicesCategory' => $servicesCategory,
				]);	
			 }
		   }
			return $this->render('updateprofilestep1', [
				'model' => $model,
				'countryModel' => $countryModel,
				'statelistModel' => $statelistModel,
				'qualificationlistModel' => $qualificationlistModel,
				'qualificationModel' => $qualificationModel,
				'servicesCategory' => $servicesCategory,
		  ]);
    }
    
       
    public function actionUpdateprofilestep2()
    {
		$id = Yii::$app->user->identity->id;
		$model = $this->findModel($id);
		$userpricetypeModel = UserPriceType::find()->where(['status'=>1])->all();
		$insurancecompaniesModel = InsuranceCompanies::find()->where(['status'=>1])->all();
		$providerfeesModel = ProviderFees::find()->where(['status'=>1])->all();
		$providerUserPriceModel = new ProviderUserPrice();

		if(Yii::$app->request->post()!=null){
			$providerUserPrice = array();
			$providerUserPrice = Yii::$app->request->post('ProviderUserPrice')['provider_fees_id'];
			$insuranceCompaniesid = Yii::$app->request->post('InsuranceCompanies')['name'];
			$insuranceCompanieslist = ProviderInsuranceCompany::find()->select('insurance_companies_id')->where(['provider_id'=>$model->id])->andWhere(['status'=>1])->all();	
			$insuranceCompaniesidArr = array();
			foreach($insuranceCompanieslist as $key=>$companyValue){
				$insuranceCompaniesidArr[] = $companyValue['insurance_companies_id'];
				if(@!in_array($companyValue['insurance_companies_id'], $insuranceCompaniesid)){
					$this->findproviderInsurancecompanyModel($companyValue['insurance_companies_id'], $model->id)->delete();
				}
			} 
			
			#--UPDATE THE PROVIDER PRICE---#
			if(!empty($providerUserPrice)){
				foreach($providerUserPrice as $pricetype=>$provider_fees){
					$modeluserprice = ProviderUserPrice::find()->where(['provider_id'=>$model->id])->andWhere(['user_price_type_id'=>$pricetype])->count();
					if($modeluserprice>0){
						$puserPriceModel = $this->findProviderUserPriceModel($model->id, $pricetype);
						$puserPriceModel->provider_id = $model->id;
						$puserPriceModel->user_price_type_id = $pricetype;
						$puserPriceModel->provider_fees_id = $provider_fees;
						$puserPriceModel->save(false);
					}else{
						$puserPriceModel = new ProviderUserPrice();
						$puserPriceModel->provider_id = $model->id;
						$puserPriceModel->user_price_type_id = $pricetype;
						$puserPriceModel->provider_fees_id = $provider_fees;
						$puserPriceModel->save(false);
					}
				}  
			}
			#---END---#
	
			#---UPDATE & INSERT PROVIDER COMPANIES---#
			if(!empty($insuranceCompaniesid)){
				foreach($insuranceCompaniesid as $insurancekey=>$insuranceCompValue){
					if(in_array($insuranceCompValue, $insuranceCompaniesidArr)){
						$insuranceCompupdateModel = $this->findproviderInsurancecompanyModel($insuranceCompValue, $model->id);
						$insuranceCompupdateModel->provider_id = $model->id;
						$insuranceCompupdateModel->insurance_companies_id = $insuranceCompValue;
						$insuranceCompupdateModel->save(false);
					}else{
						$pinsuranceCompModel = new ProviderInsuranceCompany();
						$pinsuranceCompModel->provider_id = $model->id;
						$pinsuranceCompModel->insurance_companies_id = $insuranceCompValue;
						$pinsuranceCompModel->save(false);
					}
				}  
			}
			Yii::$app->getSession()->setFlash('success', 'Record updated successfully');
			return $this->redirect(['updateprofilestep2']);
			#----END----#
	   }
	   
		return $this->render('updateprofilestep2', [
			'model' => $model,
			'insurancecompaniesModel' => $insurancecompaniesModel,
			'userpricetypeModel' => $userpricetypeModel,
			'providerfeesModel' => $providerfeesModel,
			'providerUserPriceModel' => $providerUserPriceModel,
		]);
	}
	
	public function actionUpdateprofilestep3(){
		$id = Yii::$app->user->identity->id;
		$model = $this->findModel($id);
		$healthfacilityModel = $this->findhealthfacilityModel($id);
		if(Yii::$app->request->post()!=null){	
			$model->health_facility = Yii::$app->request->post('User')['health_facility'];
			$model->save(false);
			#---DELETE RECORDS---#
			$healthfacilitylist = HealthFacility::find()->select('id')->where(['health_facility_id'=>$model->id])->andWhere(['status'=>1])->all();	
			$healthfacilityArr = array();
			foreach($healthfacilitylist as $key=>$healthfacilityValue){
				$healthfacilityaddress = $_POST['healthfacilityaddress'];
				if(!in_array($healthfacilityValue['id'], $healthfacilityaddress)){
					$this->findhealthfacilityupdModel($healthfacilityValue['id'])->delete();
				}
			}  
			#--END----#
			#---INSERT THE FACILTY ADDRESS--#
			if(!empty($_POST['addhealthfacilityaddress'])){
				$healthfacilityaddress = $_POST['addhealthfacilityaddress'];
				foreach($healthfacilityaddress as $key=>$addressValue){
					$healthfacilityModel = new HealthFacility();
					$healthfacilityModel->health_facility_id = $model->id;
					$healthfacilityModel->address = $_POST['healthfacility_address_'.$addressValue];
					$healthfacilityModel->save(false);
				}
			} 
			#---END---#
			#---UPDATE THE FACILTY ADDRESS--#
			if(!empty($_POST['healthfacilityaddress'])){
				$healthfacilityaddress = $_POST['healthfacilityaddress'];
				foreach($healthfacilityaddress as $key=>$addressValue){
					$hfacilityupdModel = $this->findhealthfacilityupdModel($addressValue);				
					$hfacilityupdModel->health_facility_id = $model->id;
					$hfacilityupdModel->address = $_POST['healthfacility_address_'.$addressValue];
					$hfacilityupdModel->save(false);
				}
				Yii::$app->getSession()->setFlash('success', 'Record updated successfully');				
				return $this->redirect(['updateprofilestep3']);	
			}
			#---END---#
		}
		return $this->render('updateprofilestep3', [
			'model' => $model,
			'healthfacilityModel' => $healthfacilityModel,
		]);
	}
	
	public function actionUpdateprofilestep4(){
		$id = Yii::$app->user->identity->id;
		$model = $this->findModel($id);
		$pcontractModel = $this->findcontractModel($id);
		$contractModel = new ProviderContract();
		if(Yii::$app->request->post()!=null){
			if(!empty($_POST['hiddenupdatecontract'])){
				$hiddenupdatecontract = $_POST['hiddenupdatecontract'];
			}
			if(!empty($_POST['hiddenaddpcontract'])){
				$hiddenaddpcontract = $_POST['hiddenaddpcontract'];
			}
			$model->contract_title = $_POST['providercontract_title'];
			$model->save(false);
			#---DELETE RECORDS---#
			$providerContractlist = ProviderContract::find()->select('id')->where(['provider_id'=>$model->id])->andWhere(['status'=>1])->all();	
			foreach($providerContractlist as $key=>$providerContractValue){
				$hiddenupdatecontract = $_POST['hiddenupdatecontract'];
				if(!in_array($providerContractValue['id'], $hiddenupdatecontract)){
					$this->findpcontractualModel($providerContractValue['id'])->delete();
				}
			}  
			#--END----#
			
			#---UPDATE & ADD NEW INAGES---#
			if(!empty($hiddenupdatecontract)){
				foreach($hiddenupdatecontract as $key=>$contractid){
					$pcontractualModel = $this->findpcontractualModel($contractid);
					$oldImages = $pcontractualModel->images;
					if(!empty($_FILES['providercontract_images_'.$contractid]['name']) && (!empty($oldImages))){
						$rnd = rand(0,9999);
						$filterArr = array(" ", "@", ",", "-");
						$imageName = $_FILES['providercontract_images_'.$contractid]['name'];
						$imagesName = str_replace($filterArr,"-",strtolower($imageName));
						$ext = explode(".",$imagesName);			
						$tmpFilePath = $_FILES['providercontract_images_'.$contractid]['tmp_name'];
						if($tmpFilePath != ""){
							//Setup our new file path
							$fileName = "contactualimages/{$rnd}-{$imagesName}";
							$newFilePath = Yii::getAlias('@frontend') .'/web/uploads/contactualimages/'.$rnd.'-'.$imagesName;
							//Upload the file into the temp dir
							if(move_uploaded_file($tmpFilePath, $newFilePath)){
								$pcontractualModel->provider_id = $model->id;
								$pcontractualModel->images = $fileName;				
								$pcontractualModel->save(false); 
							}
						}
					}else{
						$pcontractualModel->provider_id = $model->id;
						$pcontractualModel->images = $oldImages;
						$pcontractualModel->save(false); 
					}
				}
			}
			#---END OF THE UPDATE & ADD NEW INAGES---#
			#---ADD THE NEW INAGES---#
			if(!empty($hiddenaddpcontract)){
				foreach($hiddenaddpcontract as $keys=>$cntid){
					if(!empty($_FILES['providercontract_images_'.$cntid]['name'])){
						$rnd = rand(0,9999);
						$filterArr = array(" ", "@", ",", "-");
						$imageName = $_FILES['providercontract_images_'.$cntid]['name'];
						$imagesName = str_replace($filterArr,"-",strtolower($imageName));
						$ext = explode(".",$imagesName);			
						$tmpFilePath = $_FILES['providercontract_images_'.$cntid]['tmp_name'];
						if($tmpFilePath != ""){
							//Setup our new file path
							$fileName = "contactualimages/{$rnd}-{$imagesName}";
							$newFilePath = Yii::getAlias('@frontend') .'/web/uploads/contactualimages/'.$rnd.'-'.$imagesName;
							//Upload the file into the temp dir
							if(move_uploaded_file($tmpFilePath, $newFilePath)){
								$contractaddModel = new ProviderContract();
								$contractaddModel->provider_id = $model->id;
								$contractaddModel->images = $fileName;			
								$contractaddModel->save(false); 
							}
						}
					}
				} 
			}
			#---END OF THE ADD NEW INAGES---#
			Yii::$app->getSession()->setFlash('success', 'Record updated successfully');
			return $this->redirect(['updateprofilestep4']);
		}
		return $this->render('updateprofilestep4', [
			'pcontractModel' => $pcontractModel,
			'model' => $model,
			'contractModel' => $contractModel,
		]);
	}
    
	
	public function actionProviderresetpasswordstep5(){
		$id = Yii::$app->user->identity->id;
		$model = $this->findModel($id);
		if($model->load(Yii::$app->request->post())){
		   $model->password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('User')['password_hash']);  
			if($model->save(false)){
				Yii::$app->getSession()->setFlash('success', 'Password updated successfully');				
				return $this->redirect(['providerresetpasswordstep5']);	
			} 
		}
		return $this->render('providerresetpasswordstep5', [
			'model' => $model,
		]);	
	}
	
    public function actionProviderresetpassword(){
		
		$id = Yii::$app->user->identity->id;
		$model = $this->findModel($id);
		if($model->load(Yii::$app->request->post())){
		   $model->password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('User')['password_hash']);  
			if($model->save(false)){
				echo '<div class="alert alert-success">Your password changed successfully<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
			} 
		}	
	}
	
	public function actionUserfeedbackmessage(){
		
		$model = new UserFeedback();
		$model->message = Yii::$app->request->post('UserFeedback')['message'];					
		$model->user_id = Yii::$app->request->post('UserFeedback')['user_id'];					
		$provider_id = Yii::$app->request->post('UserFeedback')['provider_id'];	
		$providerData = User::find()->where(['password_reset_token'=>$provider_id])->one();	
		$model->provider_id = $providerData->id;	
		$model->created_date =  date("Y-m-d  H:i:s", time());					
		if($model->save(false)){
			echo "1";
		}
         
	}
	
	public function actionNewslettersubscriber(){
	
		$model = new SubscriberUsers();
		$subscriberemails = $_POST['subscriberEmail'];
		$model->email = $subscriberemails;		
		$model->created_date =  date("Y-m-d  H:i:s", time());
		$subscriberList = SubscriberUsers::find()->where(['email'=>$subscriberemails])->count();
		$adminEmailid = User::find()->where(['status'=>1, 'id'=>1])->one();
		if($subscriberList==0){
			if($model->save(false)){
				#-- send mail --#
				$url='Dear Subscriber,<p>You have been subscribed successfully with <b>HealthCare800.</b></p>
				<p><b>Thanks & Regards,</b><br>HealthCare800 Team,<br><a href="http://www.healthcare800.com" target="_blank">www.healthcare800.com</a></p>';
				$messageBody="$url";

				$sendemail1 = \Yii::$app->mailer->compose()
					->setTo($model->email)
					->setFrom([$adminEmailid->email => 'HealthCare800'])
					->setSubject('You have been subscribed successfully.')
					->setHtmlBody($messageBody)
					->send();
				if($sendemail1){
					echo "0";
				}
			}
		}else{
			echo "1";
		}
		
	}
	
	public function actionFiledownloads($cid){
		$id = base64_decode($cid);
		$model = ProviderContract::findOne($id);
		// required for IE, otherwise Content-disposition is ignored
		if(ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression', 'Off');
		}

		$filepath = Yii::getAlias('@frontend').'/web/uploads/'.$model->images;
		$file_extension = strtolower(substr(strrchr($model->images,"."),1));
		$filename = $model->images;
		switch( $file_extension )
		{
		  case "pdf": $ctype="application/pdf"; break;
		  case "txt": $ctype="application/txt"; break;
		  case "exe": $ctype="application/octet-stream"; break;
		  case "zip": $ctype="application/zip"; break;
		  case "doc": $ctype="application/msword"; break;
		  case "xls": $ctype="application/vnd.ms-excel"; break;
		  case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
		  case "gif": $ctype="image/gif"; break;
		  case "png": $ctype="image/png"; break;
		  case "jpg": $ctype="image/jpg"; break;
		  default: $ctype="application/force-download";
		}
		header("Pragma: public"); // required
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); // required for certain browsers 
		header("Content-Type: $ctype");
		header("Content-Disposition: attachment; filename=\"".$filename."\";" );
		header("Content-Transfer-Encoding: binary");
		readfile("$filepath");
		exit();
	} 
	
	#------DOWNLOAD THE FILES----#
	
	public function actionInsurancecomplisting(){

		$insuranceComplist = InsuranceCompanies::find()->where(['status'=>'1'])->asArray()->all();
		foreach($insuranceComplist as $key=>$insuranceVal){
			$jsonData[] = $insuranceVal['name'];
		}
		echo json_encode($jsonData);
	}
	
	
	public function actionLogout() {
        Yii::$app->user->logout();
        $session = Yii::$app->session;
        $session->destroy();
        return $this->goHome();
    }
	
	public function actionThankyou(){
		
		return $this->render('thankyou');
		
	}
	
	public function actionStatelist($id){
		
		$countStates = State::find()->where(['country_id' => $id, 'status' =>1])->count();
		if($countStates>0){
			$statesData = State::find()->where(['country_id' => $id, 'status' =>1])->orderBy('name')->all();
			 echo "<option value=''>Select State</option>";
			 foreach($statesData as $states){
				echo "<option value='".$states->state_id."'>".$states->name."</option>";
			 }
		 }else{
			echo "<option value=''>Select State</option>";
		 }
	}
	
	
	public function beforeAction($action) {
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}
	
		    
}
