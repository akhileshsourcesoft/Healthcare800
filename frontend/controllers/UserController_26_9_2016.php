<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use common\models\Category;
use common\models\Country;
use common\models\Dayname;
use common\models\State;
use common\models\Qualification;
use common\models\ProvidersDayAvailability;
use common\models\ProvidersTimeAvailability;
use common\models\UserRole;
use common\models\ClinicBanner;
use yii\web\UploadedFile;

class UserController extends Controller
{
	public function actionIndex()
	{
		return $this->render('index');
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
		
		if($model->load(Yii::$app->request->post())){	 	
			$model->fname = Yii::$app->request->post('User')['fname'];					
			$model->lname = Yii::$app->request->post('User')['lname'];					
			$model->email = Yii::$app->request->post('User')['email'];					
			$model->mobile = Yii::$app->request->post('User')['mobile'];					
			$model->landline = Yii::$app->request->post('User')['landline'];					
			$model->address = Yii::$app->request->post('User')['address'];					
			$model->dob = date("Y-m-d", strtotime(Yii::$app->request->post('User')['dob']));					
			$model->clinic_name = Yii::$app->request->post('User')['clinic_name'];					
			$model->experience = Yii::$app->request->post('User')['experience'];					
			$model->fees = Yii::$app->request->post('User')['fees'];					
			$model->city = Yii::$app->request->post('User')['city'];					
			$model->qualification_id = Yii::$app->request->post('User')['qualification_id'];					
			$model->services_category_id = Yii::$app->request->post('User')['services_category_id'];					
			$model->state_id = Yii::$app->request->post('User')['state_id'];					
			$model->created_date = date("Y-m-d h:i:s");					
			$model->gender = Yii::$app->request->post('User')['gender'];
			$model->auth_key = Yii::$app->security->generateRandomString();					
			$rnd = rand(0,9999);
			$model->user_role_id = 4;
			$model->authorized = 0;
			$model->confirmation_status = 0;
			$model->created_date = date("Y-m-d h:i:s");
			
			$imageName = $model->profile_image; 
			$model->file = UploadedFile::getInstance($model, 'profile_image');
			if(!empty($model->file)){				
				 $fileName = "users/providers/{$rnd}-{$model->file}";
				 $model->profile_image = $fileName;							 					
			}			   
			 if($model->save()){
				$counter = 1;
				foreach($_POST['providersAvailability'] as $value){
					$dayAvailability = new ProvidersDayAvailability();
					$dayAvailability->day_id = $_POST['providersdayavailability-day_id_'.$value];
					$dayAvailability->provider_id = $model->id;
					$dayAvailability->save(false);
					$timeAvailability = new ProvidersTimeAvailability();
					$timeAvailability->day_availability_id = $dayAvailability->id;	
					$timeAvailability->start_time = date("H:i", strtotime($_POST['providerstimeavailability-start_time_'.$value].':'.$_POST['providerstimeavailability-start_minutes_'.$value].' '.$_POST['providerstimeavailability-time_'.$value]));		
					$timeAvailability->end_time = date("H:i", strtotime($_POST['providerstimeavailability-end_time_'.$value].':'.$_POST['providerstimeavailability-end_minutes_'.$value].' '.$_POST['providerstimeavailability-end_'.$value]));		
					$timeAvailability->save(false);
				$counter++;}

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
						<tr><td width="25%">Mobile:</td><td>'.$model->mobile.'</td></tr>';
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
						<tr><td colspan="2"><strong>Thanks & Regards,<br/>Healthcare Team<br/><a href="http://healthcare.com" target="_blank">Healthcare.com</a></strong></td></tr>
					</table>'; 
			
					$sendemail = \Yii::$app->mailer->compose()
						->setTo($adminEmailid->email)
						->setFrom([$model->email => $model->fname.' '.$model->lname])
						->setSubject('From Healthcare Enquiry')
						->setHtmlBody($messageBody)
						->send();
					
					if($sendemail){
						echo "mail sent";
					}

				if(!empty($model->file)){
					$model->file->saveAs(Yii::getAlias('@frontend') .'/web/uploads/' . $model->profile_image);
					return $this->redirect(['thankyou']);
				}
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
		]);

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
	
	public function actionThankyou(){
		
		return $this->render('thankyou');
		
	}
	
	public function beforeAction($action) {
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}

}
