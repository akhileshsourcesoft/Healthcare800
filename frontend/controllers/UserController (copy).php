<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use common\models\Category;
use common\models\Country;
use common\models\State;
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
		$model->scenario = 'contactProviders';	
		$servicesCategory = Category::find()->where(['status'=>1])->all();
		$countryModel = Country::find()->where(['status'=>1])->all();
		
		if($model->load(Yii::$app->request->post())){	 	
			$model->gender = Yii::$app->request->post('User')['gender'];					
			$rnd = rand(0,9999);
			// get the uploaded file instance. for multiple file uploads
			// the following data will return an array
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
			if($model->validate()){			   
				 if($model->save(false)){
					
					$categoryName = Category::find()->where(['status'=>1, 'category_id'=>$model->services_category_id])->one();
					$adminEmailid = User::find()->where(['status'=>1, 'id'=>1])->one();
					$countryName = Country::find()->where(['status'=>1, 'country_id'=>$model->country_id])->one();
					$stateName = State::find()->where(['status'=>1, 'state_id'=>$model->state_id])->one();
					
					$messageBody = '<table width="55%" border="0" cellpadding="5" cellspacing="1" align="center">
						<tr><td width="25%">First Name:</td><td>'.$model->fname.'</td></tr>
						<tr><td width="25%">Last Name:</td><td>'.$model->lname.'</td></tr>
						<tr><td width="25%">Email:</td><td>'.$model->email.'</td></tr>
						<tr><td width="25%">Mobile:</td><td>'.$model->mobile.'</td></tr>';
						if(!empty($model->landline)){
							$messageBody .= '<tr><td width="25%">Landline:</td><td>'.$model->landline.'</td></tr>';
						}
						if(!empty($model->gender)){
							if($model->gender==1){ $gender = 'Male';}else{ $gender = 'Female';}
							$messageBody .= '<tr><td width="25%">Gender:</td><td>'.$gender.'</td></tr>';
						}
						if(!empty($model->address)){
							$messageBody .= '<tr><td width="25%">Address:</td><td>'.$model->address.'</td></tr>';
						}
						$messageBody .= '<tr><td width="25%">Category:</td><td>'.$categoryName->category_name.'</td></tr>
						<tr><td width="25%">Country:</td><td>'.$countryName->name.'</td></tr>
						<tr><td width="25%">State:</td><td>'.$stateName->name.'</td></tr>
						<tr><td width="25%">City:</td><td>'.$model->city.'</td></tr>';
						if(!empty($model->profile_image)){
						$messageBody .= '<tr><td width="25%">Profile Image:</td><td><img width="50" height="50" src="' . \Yii::$app->params['SITE_FULL_IMG_URL'] . $model->profile_image . '" alt=""></td></tr>';
						}
						$messageBody .= '<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2"><strong>Thank & Regards,</strong></td></tr>
						<tr><td colspan="2"><strong>Healthcare Team</strong></td></tr>
						<tr><td colspan="2"><strong><a href="http://healthcare.com" target="_blank">Healthcare.com</a></strong></td></tr>
					</table>'; 
					
				
					/*$sendemail = \Yii::$app->mailer->compose()
							->setTo('pravin@sourcesoftsolutions.com')
							->setFrom('ashish.kumar@sourceinfotech.com')
							->setSubject('Healthcare Contact')
							->setHtmlBody('fbggdfh dfgh dfgh fgh')
							->send();*/
							
					$mailBody = 'This is testing.';		
					
					$to = $adminEmailid->email;
					$subject = "Healthcare Contact";
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					$headers .= "From: <ashish.kumar@sourceinfotech.com>" . "\r\n";
					
					$sendemail = mail($to,$subject,$mailBody,$headers);
					
					if($sendemail){
						echo "mail sent"; die;
						return $this->redirect(['thankyou']);
					}
  
					if(!empty($model->file)){
						$model->file->saveAs(Yii::getAlias('@frontend') .'/web/uploads/' . $model->profile_image);
						return $this->redirect(['thankyou']);
					}
				}
			}
	   }
		return $this->render('contact', [
			'model' => $model,
			'servicesCategory' => $servicesCategory,
			'countryModel' => $countryModel,
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
