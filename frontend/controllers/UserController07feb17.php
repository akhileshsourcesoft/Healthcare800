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
use common\models\UserTimeslotBookingSearch;
use common\models\BookingStatus;
use common\models\InsuranceCompanies;
use yii\db\Query;

class UserController extends Controller
{
	
	public function behaviors()
    {
          return [
          'access' => [
                'class' => AccessControl::className(),
                'only' => ['dashboard','logout','userlogin','userresetpassword','userconfirm','usersignup','updateprofile','listing'],
                'rules' => [
                     [
                        'actions' => ['dashboard','logout','userresetpassword','updateprofile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['userlogin','userconfirm','usersignup','listing'],
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
	
    public function actionUserslogin()
    { 
		
        if (!\Yii::$app->user->isGuest) { 
            return $this->goHome();
        }
        $model = new LoginForm();
        $modelUser = new User();
        $session = Yii::$app->session;

        if($model->load(Yii::$app->request->post())){   
           if($model->validate()){
			   if(isset(Yii::$app->request->post('LoginForm')['bookTslot'])){
					$bookTslot = Yii::$app->request->post('LoginForm')['bookTslot'];  
			   }else{
				   $bookTslot = '';
			   }
			   $email = Yii::$app->request->post('LoginForm')['email'];
			   $data = $modelUser->findByUsername($email);
			   $model->loginAdmin();		              
			   $rollid = $modelUser->getRollId();
			   $rollaction = $modelUser->getRollAction($rollid);
			   Yii::$app->session['roleaction'] = $rollaction;
			   Yii::$app->session['usersid'] = $data->id;
			   $userdata = array(
					'id'=>$data->id,
					'username'=>$data->username,                 
					'emailaddress'=>$data->email,
					'userRole'=>$data->user_role_id,
					'userFullname'=>$data->fname.' '.$data->lname,
                );
				Yii::$app->session['userdata'] = $userdata;
                if(isset($data) && !empty($data)){
					if(isset($data->user_role_id) && (!empty($data->user_role_id))){
						$checktrue  = json_encode(array('login'=>$data->user_role_id, 'ajaxtslotlogin'=>$bookTslot));
					}
                    return $checktrue;
				}else{               
                     $errors = $model->getErrors();  
                     if(empty($errors))
                     {
                        return json_encode(array('errordisplay'=>"Sorry, user are not authorised to login with these credentials!"));
                     }
                     else 
                     {
                        return json_encode(array('errordisplay'=>$errors['password'][0]));
                     }
                }
           } else {
			    $errors = $model->getErrors();  
			    return json_encode(array('errordisplay'=>$errors['password'][0]));	 
		   }
        }
    }
		
	protected function findModel($id)
    {
        if(($model = User::findOne($id)) !== null){
            return $model;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function findTimeslotModel($id)
    {
        if(($model = UserTimeslotBooking::find()->where(['user_id'=>$id])->asArray()->all()) !== null){
            return $model;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function findQualification($id)
    {
        if(($model = Qualification::findOne($id)) !== null){
            return $model;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

   public function actionUsersignup(){
	   
      $model = new User();
        if($model->load(Yii::$app->request->post())){ 
			$model->fname = Yii::$app->request->post('User')['fname'];					
			$model->lname = Yii::$app->request->post('User')['lname'];					
			$model->email = Yii::$app->request->post('User')['email'];					
			$model->landline = Yii::$app->request->post('User')['landline'];
			$model->user_role_id = 3;
			$model->status = 0;
			$model->created_date = date("Y-m-d h:i:s");
			$model->auth_key = Yii::$app->security->generateRandomString();
			$model->password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('User')['password_hash']);
			$model->repassword = $model->password_hash;
			$model->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
			$checkEmailid = User::find()->where(['email'=>$model->email])->all();
			if(count($checkEmailid)==0){
				
					$id = $model->password_reset_token;
					$url='Hi <b>'. $model->fname .' '.$model->lname .',</b><p>You have been registered with <b>Healthcare800.</b></p>
					<p>Kindly confirm your email.</p><p><a href="'.Yii::$app->request->hostInfo."/user/userconfirm?id=".$id.'">'.Yii::$app->request->hostInfo."/user/userconfirm?id=".$id.'</a></p><p><b>Thanks & Regards,</b><br>Healthcare800 Team,<br><a href="http://www.healthcare800.us" target="_blank">www.healthcare800.us</a></p>';
					$messageuser="$url";
					
					$adminEmailid = User::find()->where(['status'=>1, 'id'=>1])->one();
					$sendemail1 = \Yii::$app->mailer->compose()
						->setTo($model->email)
						->setFrom([$adminEmailid->email => 'Healthcare800'])
						->setSubject('Your account has been created successfully.')
						->setHtmlBody($messageuser)
						->send();
					
					#--end user mail --#
					
					#--send mail to admin --#
					
					$urls='Hi Admin,<p>One user has been registered with email address <b>'.$model->email.'</b></p>';
					$message="$urls"; 

					$sendemail2 = \Yii::$app->mailer->compose()
						->setTo($adminEmailid->email)
						->setFrom([$model->email => $model->fname.' '.$model->lname])
						->setSubject('User registration mail.')
						->setHtmlBody($message)
						->send();	
				
				if($model->save(false)){
					echo "Account Created.";
				}
			}else{
				echo "This email already exists. Please use another.";
			}	
							
         }
	   
   }
	
   public function actionDashboard(){ 
		
		$id = Yii::$app->user->identity->id;
		$model = new User();
		$userData = $this->findModel($id);
		$searchModel = new UserTimeslotBookingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
        $model->tabname = Yii::$app->request->post('User')['tabname']; 

		return $this->render('dashboard', ['userData'=>$userData, 'model'=>$model, 'searchModel'=>$searchModel, 'dataProvider'=>$dataProvider, ]);
   }
   
    public function actionUpdateprofile(){

			$id = Yii::$app->user->identity->id;
	
			$model = $this->findModel($id);
			$model->scenario = 'updateDashboardprofile';
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
				
				if($model->file !=''){				
					$fileName="users/user_profile/{$rnd}-{$model->file}";				
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
		  ]);
		
    }
    
    public function actionUserresetpassword(){
		
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
	
	
	public function actionUserconfirm(){
			
		$password_token_key = Yii::$app->request->get('id');
		$userData = User::find()->where(['password_reset_token'=>$password_token_key])->one();
			if(count($userData)>0){
				$model = $this->findModel($userData->id);
				if($model->status==0){   
					$model->status = 1;
					$model->confirmation_status = 1;
					$model->authorized = 1;
					if($model->save(false)){
						Yii::$app->getSession()->setFlash('success','Your account has been activated.');			
						return $this->redirect(['userconfirm']);
					}
				}else if($model->status==1){
					$data = 'Your account has been already activated.';			
					return $this->render('userconfirm', ['data'=>$data]);
				}	    
			}else{
				$data = 'Your details does not exist in our records.';			
				return $this->render('userconfirm', ['data'=>$data]);
			}
	}
	
	public function actionSearchtext(){
		$searchtxt = $_POST['searchtxt'];
		$category_id = $_POST['category_id'];
		$query = User::find()->joinWith('servicesCategory')->joinWith('state')->joinWith('qualification')->joinWith('providersDayAvailabilities')->joinWith('providersDayAvailabilities.providersTimeAvailabilities')
				->where('hc_providers_day_availability.slot_date = CURDATE() OR hc_providers_day_availability.slot_date >= CURDATE()')
				->andWhere(['hc_users.services_category_id' => $category_id])
				->andWhere(['hc_users.user_role_id'=> 4])
				->andWhere(['hc_users.status' => 1])
				->andFilterWhere(['or',
						['like', 'hc_users.fname', $searchtxt],
						['like', 'hc_users.lname', $searchtxt],
						['like', 'hc_qualification.name', $searchtxt],
						['like', 'hc_users.city', $searchtxt],
						['like', 'hc_state.name', $searchtxt],
						['like', 'hc_users.zip_code', $searchtxt],
				])
				->orderBy([
						'hc_users.amount' => SORT_DESC,
						'hc_users.updated_date' => SORT_DESC,
				])
				->asArray()->all();
		
                 		
		if(count($query)>0){
			foreach($query as $val){
				//echo '<li onclick="set_item(\''.str_replace("'", "\'", $val['servicesCategory']['category_name'].', '.$val['fname'].' '.$val['lname'].', '.$val['state']['name']).', '.$val['city'].', '.$val['qualification']['name'].', '.$val['zip_code'].'\')">'.$val['fname'].' '.$val['lname'].', '.$val['qualification']['name'].', '.$val['state']['name'].', '.$val['city'].', '.$val['zip_code'].'</li>';

echo '<li onclick="set_item(\''.str_replace("'", "\'", $val['servicesCategory']['category_name'].': '.$val['fname'].':'.$val['lname'].': '.$val['state']['name']).': '.$val['city'].': '.$val['qualification']['name'].': '.$val['zip_code'].'\')">'.$val['fname'].' '.$val['lname'].', '.$val['qualification']['name'].', '.$val['state']['name'].', '.$val['city'].', '.$val['zip_code'].'</li>';
			}
		}else{
			echo '<li>No Record found.</li>'; 
		}
	}
   
   public function actionStatelisting(){
	 $countryName = $_GET['key'];
	 $jsonData[] = '';
	 $stateList = State::find()->select('name')->where(['status'=>'1'])->andFilterWhere(['like', 'name', $countryName])->asArray()->all();
		foreach($stateList as $key=>$stateVal){
			$jsonData[] = $stateVal['name'];	
		}
		echo json_encode($jsonData);
   }
   
   public function actionCitylisting(){
	 $citykey = $_GET['citykey'];
	 $jsoncityData[] = '';
	 $cityList = User::find()->select('city')->where(['status'=>'1'])->andFilterWhere(['like', 'city', $citykey])->groupBy(['city'])->asArray()->all();
		foreach($cityList as $key=>$cityValue){
			$jsoncityData[] = $cityValue['city'];	
		}
		echo json_encode($jsoncityData);
   }
   
   public function actionSpeciallity(){
	 $spaciallitykey = $_GET['spaciallitykey'];
	 $categoryServices = $_GET['categoryName'];
	 $jsonspaciallityData[] = '';
	 $query = User::find()->joinWith('servicesCategory')->joinWith('qualification')
				->where(['hc_category.category_name' => $categoryServices])
				->andWhere(['hc_users.user_role_id'=> 4])
				->andWhere(['hc_users.status' => 1])
				->andFilterWhere(['like', 'hc_qualification.name', $spaciallitykey])
				->groupBy(['hc_qualification.name'])->asArray()->all();

		foreach($query as $key=>$specValue){
			$jsonspaciallityData[] = $specValue['qualification']['name'];	
		}
		echo json_encode($jsonspaciallityData);   
   }
   
	public function actionInsurancecompany(){
		 $insurancekey = $_GET['insurancekey'];
		 $categoryServices = $_GET['categoryName'];
		 $jsonspaciallityData[] = '';
		 $query = InsuranceCompanies::find()->select('name')->where(['status'=>'1'])->andFilterWhere(['like', 'name', $insurancekey])->asArray()->all();
			foreach($query as $key=>$insuranceValue){
				$jsoninsuranceData[] = $insuranceValue['name'];	
			}
			echo json_encode($jsoninsuranceData);   
	}
   
	public function beforeAction($action) {
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}
 
}
