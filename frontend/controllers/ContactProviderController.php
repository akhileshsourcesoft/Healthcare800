<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\behaviors\SluggableBehavior;
use common\models\ContactProvider;
use common\models\ContactPoviderSearch;
use yii\web\Session;
use common\models\User;
use common\models\CmsPage;

/**
 * StateController implements the CRUD actions for State model.
 */
class ContactProviderController extends Controller
{
    public function behaviors()
{
    return [
        [
            'class' => SluggableBehavior::className(),
            'attribute' => 'page_title',
           // 'immutable' => true,
           // 'ensureUnique'=>true,
          //  'slugAttribute' => 'alias',
        ],
    ];
}

    /**
     * Displays a single Status model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
     
    /**
     * Displays a single Status model.
     * @param string $slug
     * @return mixed
     */
    public function actionSlugnew($view)
    { 
		
      $formmodel = new Forms();
	  $contactmodel = new Contact();
	 
	  
      $model = CmsPage::find()->where(['bs_cms_page.slug'=>$view])->andWhere(['bs_cms_page.status'=>'1'])->asArray()->one();
      
      
      
      if (!is_null($model)) {
		  
		  if(isset($_POST) && !empty($_POST)){ 
			   $contactmodel->attributes = $_POST['Contact']; 
			  $userdata = Yii::$app->request->post('Contact');		  
			  if($contactmodel->save()){
			
	                $formmodel->form_type ='0';
			        $formmodel->name = $userdata['name'];
                    $formmodel->email = $userdata['email'];
                    $formmodel->comment  = $userdata['message'];
                    $formmodel->date_added = date("Y-m-d  H:i:s", time());
                    $formmodel->address ='';
                    $formmodel->remail ='';
				    if($formmodel->save()) {
						
							 return $this->redirect(['/thanks/thankyou?msg=12']);
					} else {
						print_r($formmodel->getErrors()); die;
					}
					 
					 
				} else {
					print_r($contactmodel->getErrors()); die;
				}
		  }
		  
		  
		  
		  
		  
          return $this->render('view', [
              'model' => $model, 'contactmodel'=>$contactmodel
          ]);      
      } else {
        return $this->redirect(['/site/index']);
      }
    }
    

  public function actionSlug($slug)
    { 
		
				
	  //$formmodel = new Forms();
	  $contactmodel = new ContactProvider();
			
      $model = CmsPage::find()->where(['slug'=>$slug])->one();
     
      if (!is_null($model)) { 
		if(isset($_POST) && !empty($_POST)){
			   $contactmodel->attributes = $_POST['Contact']; 
			   $userdata = Yii::$app->request->post('Contact');	
			  
			$logos = '<a href="'.Yii::$app->params['HOST_INFO'].'" target="_blank"><img style="background:#f1f1f1;" src="'.Yii::$app->params['HOST_INFO'].'images/logo.png" alt="" width=""></a>';
			  
			$name=$userdata['name'];
			$email=$userdata['email'];
			$phone=$userdata['phone'];
			$provider=$userdata['provider'];
			$subject=$userdata['subject'];
			$message=$userdata['message'];
			    
			 $html = '<div style="width:100%">
			<table width="100%" border="0" cellpadding="8" cellspacing="0" align="center" style="font-size:15px; font-family:arial; background-color:#fff; border:1px solid #ccc;">
			<tr style="background-color:#f1f1f1">
			<td colspan="5">'.$logos.'</td>
			</tr>	
			<tr>
			<td valign="top">
			<label for="first_name">Name</label>
			</td>
			<td valign="top">'.$name.'
			</td>
			</tr>
			<tr>
			<td valign="top"">
			<label for="last_name">Email Address</label>
			</td>
			<td valign="top">
			'.$email.'
			</td>
			</tr>
			<tr>
			<td valign="top">
			<label for="email">Phone Number</label>
			</td>
			<td valign="top">
			'.$phone.'
			</td>
			</tr>
			<tr>
			<td valign="top">
			<label for="telephone">Subject</label>
			</td>
			<td valign="top">
			'.$provider.'
			</td>
			</tr>
			<tr>
			<td valign="top">
			<label for="telephone">Subject</label>
			</td>
			<td valign="top">
			'.$subject.'
			</td>
			</tr>
			<tr>
			<td valign="top">
			<label for="comments">Message</label>
			</td>
			<td valign="top">
			'.$message.'
			</td>
			</tr>
			</table><br/>
			<h3 style="color:#f04e4e; text-transform:uppercase;" align="center">Thank you, Healthcare800</h3><br/><br/>
		</div>';
			     
			     $admindata = User::find()->where(['user_role_id'=>1])->andWhere(['status'=>1])->one();
			     
			     $admindata->email;
			     
			     $emaildata=array($admindata->email,$email);
			     		     
			       		  
				foreach($emaildata as $emailsend){
					
				 $contactemail = \Yii::$app->mailer->compose()
				->setTo($emailsend)
				->setFrom($emaildata[0])
				->setSubject('Contact us from HealthCare800')
				->setHtmlBody($html)
				->send();
               
				}

				if($email){
				$contactmodel->save();
				return $this->redirect(['/thanks/contactyou?msg=12']);
				}

					 
			else {
					print_r($contactmodel->getErrors()); die;
				}
			  	  
			
		  }
	
           return $this->render('view', [
              'model' => $model, 'contactmodel'=>$contactmodel
          ]); 
      
               
      } else {
        return $this->redirect(['/site/index']);
      }
    } 
    
    
}
