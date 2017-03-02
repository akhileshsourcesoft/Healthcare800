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
use yii\db\Query;

class ProviderController extends Controller
{
	
	public function behaviors()
    {
          return [
          'access' => [
                'class' => AccessControl::className(),
                'only' => ['contact','thankyou','register','confirm','dashboard','logout','updateprofile','providerresetpassword'],
                'rules' => [
                     [
                        'actions' => ['dashboard','logout','providerresetpassword','updateprofile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['contact','thankyou','register','confirm'],
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
       
    protected function findTimeAvailModel($id)
    {	
        if (($model = ProvidersTimeAvailability::find()->where(['id'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionContact()
	{
		$model = new User();	
		$servicesCategory = Category::find()->where(['status'=>1])->all();
		$countryModel = Country::find()->where(['status'=>1])->all();
		$daylistModel = Dayname::find()->where(['status'=>1])->all();
		$statelistModel = State::find()->where(['status'=>1])->all();
		$qualificationlistModel = Qualification::find()->where(['status'=>1])->all();
		$userRoleData = UserRole::find(['status'=>'1'])->andWhere(['!=', 'id', '1'])->all();
	
		$dayAvailability = new ProvidersDayAvailability();
		$timeAvailability = new ProvidersTimeAvailability();
		$clinicBannerModel = new ClinicBanner();
		$qualificationModel = new Qualification();
	
		if($model->load(Yii::$app->request->post())){	 	
			$model->fname = Yii::$app->request->post('User')['fname'];					
			$model->lname = Yii::$app->request->post('User')['lname'];					
			$model->email = Yii::$app->request->post('User')['email'];										
			$model->landline = Yii::$app->request->post('User')['landline'];					
			$model->address = Yii::$app->request->post('User')['address'];										
			$model->clinic_name = Yii::$app->request->post('User')['clinic_name'];					
			$model->experience = Yii::$app->request->post('User')['experience'];					
			$model->fees = Yii::$app->request->post('User')['fees'];					
			$model->city = Yii::$app->request->post('User')['city'];										
			$model->services_category_id = Yii::$app->request->post('User')['services_category_id'];					
			$model->state_id = Yii::$app->request->post('User')['state_id'];									
			$model->gender = Yii::$app->request->post('User')['gender'];
			$model->short_desc = Yii::$app->request->post('User')['short_desc'];
			$model->auth_key = Yii::$app->security->generateRandomString();	
			$model->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();				
			$rnd = rand(0,9999);
			$model->user_role_id = 4;
			$model->authorized = 0;
			$model->confirmation_status = 0;
		
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
				if(isset($_POST['slotTime']) && (!empty($_POST['slotTime']))){
					foreach($_POST['slotTime'] as $key=>$val){
							$dayAvailability = new ProvidersDayAvailability();
							$dayAvailability->day_id = $_POST['dayId_'.$key];
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
			
					#==============Clinic Banner=================#
					if(isset($_FILES['ClinicBanner']['name']['images'])){
						for($i=0; $i < count(array_filter($_FILES['ClinicBanner']['name']['images'])); $i++){
						$modelClinicBanner = new ClinicBanner();
						$data = Yii::$app->request->post('ClinicBanner');

						$rnd = rand(0,9999);
						$imageName = $_FILES['ClinicBanner']['name']['images'][$i]; 
						$ext = explode(".",$imageName);						
						$tmpFilePath = $_FILES['ClinicBanner']['tmp_name']['images'][$i];

							//Make sure we have a filepath
							if($tmpFilePath != ""){
							//Setup our new file path
							$fileName = "users/providers_banner/{$rnd}-{$imageName}";
							$newFilePath = Yii::getAlias('@frontend') .'/web/uploads/users/providers_banner/'.$rnd.'-'.$imageName;

							//Upload the file into the temp dir
								if(move_uploaded_file($tmpFilePath, $newFilePath)) {
									$modelClinicBanner->provider_id = $model->id;
									$modelClinicBanner->images = $fileName; 							
										if($data['sort_order'][$i] == ''){
											$modelClinicBanner->sort_order = 0;
										} else {
											$modelClinicBanner->sort_order = $data['sort_order'][$i];
										}		
										$modelClinicBanner->img_title = $data['img_title'][$i];
										$modelClinicBanner->url = $data['url'][$i];						
										$modelClinicBanner->save(false); 
								} else {
									print_r($modelClinicBanner->getErrors());
								} 
							}												 
						}
					}  
					#==============Clinic Banner=================#
				 
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
						if(!empty($model->address)){
							$messageBody .= '<tr><td width="25%">Address:</td><td>'.$model->address.'</td></tr>';
						}
						$messageBody .= '<tr><td width="25%">Category:</td><td>'.$categoryName->category_name.'</td></tr>
						<tr><td width="25%">State:</td><td>'.$stateName->name.'</td></tr>
						<tr><td width="25%">City:</td><td>'.$model->city.'</td></tr>';
						if(!empty($model->profile_image)){
						$messageBody .= '<tr><td width="25%">Profile Image:</td><td><img width="50" height="50" src="' . \Yii::$app->params['SITE_FULL_IMG_URL'] . $model->profile_image . '" alt=""></td></tr>';
						}
						$messageBody .= '<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2"><strong>Thanks & Regards,<br/>Brightseathealth Team<br/><a href="http://www.brightseathealth.com" target="_blank">www.brightseathealth.com</a></strong></td></tr>
					</table>'; 
			
					$providersmail = \Yii::$app->mailer->compose()
						->setTo($adminEmailid->email)
						->setFrom([$model->email => $model->fname.' '.$model->lname])
						->setSubject('Brightseathealth Provider enquiry.')
						->setHtmlBody($messageBody)
						->send();	
					

				if(!empty($model->file)){
					$model->file->saveAs(Yii::getAlias('@frontend') .'/web/uploads/' . $model->profile_image);
					return $this->redirect(['thankyou']);
				}else{
					return $this->redirect(['thankyou']);
				}
			  }
			}else{
				$emailData = "This email already exists. Please use another.";
				return $this->render('contact', [
					'model' => $model,
					'userRoleData' => $userRoleData,
					'servicesCategory' => $servicesCategory,
					'countryModel' => $countryModel,
					'daylistModel' => $daylistModel,
					'dayAvailability' => $dayAvailability,
					'timeAvailability' => $timeAvailability,
					'statelistModel' => $statelistModel,
					'qualificationlistModel' => $qualificationlistModel,
					'clinicBannerModel' => $clinicBannerModel,
					'qualificationModel' => $qualificationModel,
					'emailData' => $emailData,
				]);	
			}
	   }
		return $this->render('contact', [
				'model' => $model,
				'userRoleData' => $userRoleData,
				'servicesCategory' => $servicesCategory,
				'countryModel' => $countryModel,
				'daylistModel' => $daylistModel,
				'dayAvailability' => $dayAvailability,
				'timeAvailability' => $timeAvailability,
				'statelistModel' => $statelistModel,
				'qualificationlistModel' => $qualificationlistModel,
				'clinicBannerModel' => $clinicBannerModel,
				'qualificationModel' => $qualificationModel,
		]);

	}
	
	
	public function actionRegister()
	{
		$model = new User();	
		$model->scenario = 'providerRegister';
		$servicesCategory = Category::find()->where(['status'=>1])->all();
		$daylistModel = Dayname::find()->where(['status'=>1])->all();
		$statelistModel = State::find()->where(['status'=>1])->all();
		$qualificationlistModel = Qualification::find()->where(['status'=>1])->all();
		$userRoleData = UserRole::find(['status'=>'1'])->andWhere(['!=', 'id', '1'])->all();
		$qualificationModel = new Qualification();
	
		if($model->load(Yii::$app->request->post())){	
			
			$model->auth_key = Yii::$app->security->generateRandomString();	
			$model->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
			$model->password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('User')['password_hash']);
			$model->repassword = $model->password_hash;				
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
						if(!empty($model->address)){
							$messageBody .= '<tr><td width="25%">Address:</td><td>'.$model->address.'</td></tr>';
						}
						$messageBody .= '<tr><td width="25%">Category:</td><td>'.$categoryName->category_name.'</td></tr>
						<tr><td width="25%">State:</td><td>'.$stateName->name.'</td></tr>
						<tr><td width="25%">City:</td><td>'.$model->city.'</td></tr>';
						if(!empty($model->profile_image)){
						$messageBody .= '<tr><td width="25%">Profile Image:</td><td><img width="50" height="50" src="' . \Yii::$app->params['SITE_FULL_IMG_URL'] . $model->profile_image . '" alt=""></td></tr>';
						}
						$messageBody .= '<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2"><strong>Thanks & Regards,<br/>Brightseathealth Team<br/><a href="http://www.brightseathealth.com" target="_blank">www.brightseathealth.com</a></strong></td></tr>
					</table>'; 
			
					$providersmail = \Yii::$app->mailer->compose()
						->setTo($adminEmailid->email)
						->setFrom([$model->email => $model->fname.' '.$model->lname])
						->setSubject('Brightseathealth Provider enquiry.')
						->setHtmlBody($messageBody)
						->send();	
						
					$id = $model->password_reset_token;
					$url='Hi <b>'. $model->fname .' '.$model->lname .',</b><p>You have been registered as “Provider” with <b>Brightseathealth.</b></p>
					<p>You will get the activation link in your registered email address soon.</p><p><b>Thanks & Regards,</b><br>Brightseathealth Team,<br><a href="http://www.brightseathealth.com" target="_blank">www.brightseathealth.com</a></p>';
					$messageuser="$url";

					$sendemail1 = \Yii::$app->mailer->compose()
						->setTo($model->email)
						->setFrom([$adminEmailid->email => 'Bright Seat Health'])
						->setSubject('Your account has been created successfully.')
						->setHtmlBody($messageuser)
						->send();
					
					#--end user mail --#
					

				if(!empty($model->file)){
					$model->file->saveAs(Yii::getAlias('@frontend') .'/web/uploads/' . $model->profile_image);
					return $this->redirect(['thankyou']);
				}else{
					return $this->redirect(['thankyou']);
				}
			  }
			}else{
				$emailData = "This email already exists. Please use another.";
			return $this->render('register', [
					'model' => $model,
					'userRoleData' => $userRoleData,
					'servicesCategory' => $servicesCategory,
					'statelistModel' => $statelistModel,
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
				'statelistModel' => $statelistModel,
				'qualificationlistModel' => $qualificationlistModel,
				'qualificationModel' => $qualificationModel,
		]);

	}
	
	public function actionDetail(){
		
		$providerid = Yii::$app->request->get('pid');
		if(isset($providerid) && (!empty($providerid))){
			
			$providerlist = User::find()->joinWith('servicesCategory')->joinWith('state')->joinWith('qualification')->joinWith('providersDayAvailabilities')->joinWith('providersDayAvailabilities.providersTimeAvailabilities')
				->where(['password_reset_token'=>$providerid])
				->andWhere(['hc_users.status'=>1,'hc_users.user_role_id'=>4])->one();
		}
	
		return $this->render('detail', ['providerlist'=>$providerlist]);
		
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
			
			$userData = $model;
			$daylistModel = Dayname::find()->where(['status'=>1])->all();
			$statelistModel = State::find()->where(['status'=>1])->all();
			$qualificationlistModel = Qualification::find()->where(['status'=>1])->all();

			$dayModel = new Dayname();
			$dayAvailability = new ProvidersDayAvailability();
			$timeAvailability = new ProvidersTimeAvailability();
			$clinicBannerModel = new ClinicBanner();
			$qualificationModel = new Qualification();

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
					if(isset($_FILES['ClinicBanner']['name']['images'])){
						for($i=0; $i < count(array_filter($_FILES['ClinicBanner']['name']['images'])); $i++){
							$modelClinicBanner = new ClinicBanner();
							$data = Yii::$app->request->post('ClinicBanner');

							$rnd = rand(0,9999);
							$imageName = $_FILES['ClinicBanner']['name']['images'][$i]; 
							$ext = explode(".",$imageName);						
							$tmpFilePath = $_FILES['ClinicBanner']['tmp_name']['images'][$i];

							//Make sure we have a filepath
							if($tmpFilePath != ""){
							//Setup our new file path
							$fileName = "users/providers_banner/{$rnd}-{$imageName}";
							$newFilePath = Yii::getAlias('@frontend') .'/web/uploads/users/providers_banner/'.$rnd.'-'.$imageName;

							//Upload the file into the temp dir
								if(move_uploaded_file($tmpFilePath, $newFilePath)){
									$modelClinicBanner->provider_id = $model->id;
									$modelClinicBanner->images = $fileName; 							
										if($data['sort_order'][$i] == ''){
											$modelClinicBanner->sort_order = 0;
										} else {
											$modelClinicBanner->sort_order = $data['sort_order'][$i];
										}		
										$modelClinicBanner->img_title = $data['img_title'][$i];
										$modelClinicBanner->url = $data['url'][$i];						
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
					return $this->redirect(['dashboard']);
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

			]);
   }
   
       public function actionUpdateprofile(){

			$id = Yii::$app->user->identity->id;
			
			$servicesCategory = Category::find()->where(['status'=>1])->all();
			$countryModel = Country::find()->where(['status'=>1])->all();
			$daylistModel = Dayname::find()->where(['status'=>1])->all();
			$statelistModel = State::find()->where(['status'=>1])->all();
			$qualificationlistModel = Qualification::find()->where(['status'=>1])->all();
			$userRoleData = UserRole::find(['status'=>'1'])->andWhere(['!=', 'id', '1'])->all();
	
			$model = $this->findModel($id);
			$qualificationModel = new Qualification();
			$model->scenario = 'updateProviderDashboardprofile';
			$oldIcon = $model->profile_image;
			$oldpassword = $model->password_hash;
			
			if($model->load(Yii::$app->request->post())){
				$model->authorized = 1;
				$model->confirmation_status = 1;
				$rnd = rand(0,9999);
				$model->updated_date = date("Y-m-d  H:i:s", time());
				$imageName = $model->profile_image; 
				$model->file = UploadedFile::getInstance($model, 'profile_image');
				$model->address = Yii::$app->request->post('User')['address'];
				$model->short_desc = Yii::$app->request->post('User')['short_desc'];
				
				
				if(isset(Yii::$app->request->post('Qualification')['other_qname']) && (!empty(Yii::$app->request->post('Qualification')['other_qname']))){
					$qualificationModel->name = Yii::$app->request->post('Qualification')['other_qname'];
					$qualificationModel->status = 1;
					$qualificationModel->save(false);
					$model->qualification_id = $qualificationModel->id;
				}else{
					$model->qualification_id = Yii::$app->request->post('User')['qualification_id'];
				}

				
				if($model->file !=''){				
					$fileName="users/providers/{$rnd}-{$model->file}";				
					$model->profile_image = $fileName;
				}else{	
					$model->profile_image =$oldIcon;
				}
			
			  if($model->validate()){
				$checkEmailid = User::find()->where(['email'=>$model->email])->andwhere(['<>', 'id', $id])->all();
				if(count($checkEmailid)==0){
				if($model->file != '') {

					 $model->password_hash=$oldpassword;
					 $model->repassword=$oldpassword;
						if($model->save(false)){ 
							$model->file->saveAs(Yii::getAlias('@frontend') .'/web/uploads/' . $model->profile_image);
									if($oldIcon != '' && file_exists(Yii::getAlias('@frontend') .'/web/uploads/'.$oldIcon))
									{		
										$oldfile =  Yii::getAlias('@frontend') .'/web/uploads/'.$oldIcon;
										 unlink($oldfile); 
									}
							Yii::$app->getSession()->setFlash('success', 'Record updated successfully');
							return $this->redirect(['dashboard']);
							}	
						}
				else if($_POST['User']['password_hash'] != 'password' && $model->file != '')
						{		
						
							$model->password_hash = Yii::$app->security->generatePasswordHash($model->password_hash);	
							$model->repassword= $model->password_hash;			 										
									if($model->save(false)) { 
											$model->file->saveAs(Yii::getAlias('@frontend') .'/web/uploads/' . $model->profile_image);
												if($oldIcon != '' && file_exists(Yii::getAlias('@frontend') .'/web/uploads/'.$oldIcon))
													{		
														$oldfile =  Yii::getAlias('@frontend') .'/web/uploads/'.$oldIcon;
														unlink($oldfile); 
													}
									Yii::$app->getSession()->setFlash('success', 'Record updated successfully');
									return $this->redirect(['dashboard']);
								}
						}
				 else if($_POST['User']['password_hash'] != 'password') {	
						  
						$model->password_hash = Yii::$app->security->generatePasswordHash($_POST['User']['password_hash']);	
						$model->repassword= $model->password_hash;	
						$model->profile_image =$oldIcon;				
						if($model->save(false)) { 								
								Yii::$app->getSession()->setFlash('success', 'Record updated successfully');
								return $this->redirect(['dashboard']);
						}	
					}				
				else {
					 $model->password_hash=$oldpassword;
					 $model->repassword=$oldpassword;					
					 $model->profile_image =$oldIcon;
					 if($model->save(false)) {
						Yii::$app->getSession()->setFlash('success', 'Record updated successfully');
						return $this->redirect(['dashboard']);
					 }
					}
			   }else{
					Yii::$app->getSession()->setFlash('success', 'This email already exists. Please use another.');
					return $this->redirect(['dashboard']);
				}
			}
		  }
			return $this->render('updateprofile', [
				'model' => $model, 
				'userRoleData' => $userRoleData,
				'servicesCategory' => $servicesCategory,
				'statelistModel' => $statelistModel,
				'qualificationlistModel' => $qualificationlistModel,
				'qualificationModel' => $qualificationModel,
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
	
	public function actionLogout() {
        Yii::$app->user->logout();
        $session = Yii::$app->session;
        $session->destroy();
        return $this->goHome();
    }
	
	public function actionThankyou(){
		
		return $this->render('thankyou');
		
	}
	
	
	public function beforeAction($action) {
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}
	
		    
}
