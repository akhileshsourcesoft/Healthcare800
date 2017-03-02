<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\Session;
use common\models\UserTimeslotBooking;
use common\models\User;
use common\models\State;


/**
 * StateController implements the CRUD actions for State model.
 */
class ThanksController extends Controller
{   
    /**
     * Displays a single Status model.
     * @param integer $id
     * @return mixed
     */
    public function actionThankyou()
    {	
		$session = Yii::$app->session;
		$session->destroy();					
		unset($session);
		
		return $this->render('thankyou');
    }
    
   public function actionPaymentprocess(){
	
	$bookingid = Yii::$app->request->get('id');
	$payMethod = Yii::$app->request->get('pay');
	if($payMethod==1){
        $bookingData = UserTimeslotBooking::find()->joinWith('provider')->where(['hc_user_timeslot_booking.id'=>$bookingid])->andWhere(['hc_user_timeslot_booking.status'=>1])->one();
       $stateData = State::find()->where(['state_id'=>$bookingData->provider['state_id']])->one();

       $admindata = User::find()->where(['user_role_id'=>1])->andWhere(['status'=>1])->one();

	$logos = '<a href="'.Yii::$app->params['HOST_INFO'].'" target="_blank"><img style="background:#cb4343;" src="'.Yii::$app->params['HOST_INFO'].'images/logo.png" alt="" width="125px;"></a>';
			$this->sendmailtopatient($bookingData,$stateData->name,$admindata,$logos);
			$this->sendmailtoprovider($bookingData,$stateData->name,$admindata,$logos);
			
			$html2 = '<div style="width:100%">
			<table width="55%" border="0" cellpadding="8" cellspacing="0" align="center" style="font-size:15px; font-family:arial; background-color:#fff; border:1px solid #ccc;">
				<tr style="background-color:#dedede">
					<td colspan="5">'.$logos.'</td>
				</tr>
				<tr>
					<td style="background:#dc7f7f; font-size:16px; border-right:1px dashed; border-color:#ccc;" colspan="3"><h3 style="text-transform:uppercase;">Thanks for booking your appointment.</h3>';
						$html2 .= 'Your appointment has been confirmed. Your appointment summary is below. Thank you again for your business.</td>
					<td  style="background:#dc7f7f; font-size:14px;"><strong>Order Questions?</strong><br/><br/>
					   <strong>Call Us :</strong> <span style="font-size:12px; font-family:arial;"><a style="color:#000000" href="tel:'.$admindata->landline.'">'.$admindata->landline.'</a></span><br/>
						<strong>Email :</strong> <span style="font-size:12px; font-family:arial;"><a style="color:#000000" href="mailto:'.$admindata->email.'">'.$admindata->email.'</a></span>
					</td>
				</tr>
				<tr>
					<td colspan="5" align="center"><h3 style="color: #f04e4e;font-weight: normal;">Your booking #' .$bookingData->booking_number . ' </h3><span style="font-size:12px;">as on <span style="color:red">'.date("M d, Y", strtotime($bookingData->booking_date)).'</span>  '.$bookingData->booking_time.'</span><br/><br/></td>
				</tr>
				<tr style="background-color:#f1f1f1; text-transform:uppercase;">
					<th colspan="4">Patient/Visitor Details:</th>
				</tr>
				<tr style="background-color:#fff">
					<td colspan="4" style="background-color:#fff width:100%">Name : <span style="color: #f04e4e;text-transform:uppercase; font-weight:bold;">'.$bookingData->fullname.'</span><br/>
					<span align="right">Email : '.$bookingData->email.'</span><br/>
					<span align="right">Phone No. : '.$bookingData->phone_no.'</span><br/>
					</td>
				</tr>

				<tr style="background-color:#f1f1f1; text-transform:uppercase;">
					<th colspan="4">Provider Details:</th>
				</tr>
				<tr style="background-color:#fff">
					<td colspan="4" style="background-color:#fff width:100%">Name : <span style="color: #f04e4e;text-transform:uppercase; font-weight:bold;">'.$bookingData->provider->fname.' '.$bookingData->provider->lname.'</span><br/>
					<span align="right">Email : '.$bookingData->provider->email.'</span><br/>
					<span align="right">Phone No. : '.$bookingData->provider->landline.'</span><br/>';
					if($bookingData->payment_method==2){
					$html2.='<span align="right">Fee : $'.$bookingData->provider->fees.'</span><br/>';
					}
					$html2.='<span align="right">Address : '.$bookingData->provider->address.'</span><br/>
					<span align="right">State : '.$stateData->name.'</span><br/>
					<span align="right">City : '.$bookingData->provider->city.'</span><br/></td>
				</tr>';
			
						$html2.='<tr>
							<td style="background-color:#fff;" valign="top"><br/>
								<span style="color:#f04e4e; text-transform:uppercase; font-size:14px;"><strong>Payment Method:</strong></span><br/><br/>';
								if($bookingData->payment_method==2){
									$html2.='<span style="font-size:13px;">Paypal</span><br/>';
								}else{
									$html2.='<span style="font-size:13px;">Insurance</span><br/>';
								}
							$html2.='</td>
						</tr>
				
					</table>
				</td>
			</tr>
			</table><br/>
			<h3 style="color:#f04e4e; text-transform:uppercase;" align="center">Thank you, Brightseathealth!</h3><br/><br/>
		</div>';

			
			$email = \Yii::$app->mailer->compose()
				->setTo($admindata->email)
				->setFrom([$bookingData->email => $bookingData->fullname])
				->setSubject('Appointment booking from Brightseathealth.')
				->setHtmlBody($html2)
				->send();
			if($email){
				return $this->redirect(["thankyou"]);
			}
		}else{
			return $this->redirect(["thankyou"]);
		}
    } 
    
      #--------------SEND MAIL TO PATIENT/VISITOR-----------#
    public function sendmailtopatient($bookingData,$statename,$admindata,$logos){

		$patientMessage = '<div style="width:100%">
			<table width="55%" border="0" cellpadding="8" cellspacing="0" align="center" style="font-size:15px; font-family:arial; background-color:#fff; border:1px solid #ccc;">
				<tr style="background-color:#dedede">
					<td colspan="5">'.$logos.'</td>
				</tr>
				<tr>
					<td style="background:#dc7f7f; font-size:16px; border-right:1px dashed; border-color:#ccc;" colspan="3"><h3 style="text-transform:uppercase;">Thanks for booking your appointment.</h3>';
						$patientMessage .= 'Your appointment has been confirmed. Your appointment summary is below. Thank you again for your business.</td>
					<td  style="background:#dc7f7f; font-size:14px;"><strong>Order Questions?</strong><br/><br/>
					   <strong>Call Us :</strong> <span style="font-size:12px; font-family:arial;"><a style="color:#000000" href="tel:'.$admindata->landline.'">'.$admindata->landline.'</a></span><br/>
						<strong>Email :</strong> <span style="font-size:12px; font-family:arial;"><a style="color:#000000" href="mailto:'.$admindata->email.'">'.$admindata->email.'</a></span>
					</td>
				</tr>
				<tr>
					<td colspan="5" align="center"><h3 style="color: #f04e4e;font-weight: normal;">Your booking #' .$bookingData->booking_number . ' </h3><span style="font-size:12px;">as on <span style="color:red">'.date("M d, Y", strtotime($bookingData->booking_date)).'</span>  '.$bookingData->booking_time.'</span><br/><br/></td>
				</tr>
				<tr style="background-color:#f1f1f1; text-transform:uppercase;">
					<th colspan="4">Patient/Visitor Details:</th>
				</tr>
				<tr style="background-color:#fff">
					<td colspan="4" style="background-color:#fff width:100%">Name : <span style="color: #f04e4e;text-transform:uppercase; font-weight:bold;">'.$bookingData->fullname.'</span><br/>
					<span align="right">Email : '.$bookingData->email.'</span><br/>
					<span align="right">Phone No. : '.$bookingData->phone_no.'</span><br/>
					</td>
				</tr>

				<tr style="background-color:#f1f1f1; text-transform:uppercase;">
					<th colspan="4">Provider Details:</th>
				</tr>
				<tr style="background-color:#fff">
					<td colspan="4" style="background-color:#fff width:100%">Name : <span style="color: #f04e4e;text-transform:uppercase; font-weight:bold;">'.$bookingData->provider->fname.' '.$bookingData->provider->lname.'</span><br/>
					<span align="right">Email : '.$bookingData->provider->email.'</span><br/>
					<span align="right">Phone No. : '.$bookingData->provider->landline.'</span><br/>';
					if($bookingData->payment_method==2){
					$patientMessage.='<span align="right">Fee : $'.$bookingData->provider->fees.'</span><br/>';
					}
					$patientMessage.='<span align="right">Address : '.$bookingData->provider->address.'</span><br/>
					<span align="right">State : '.$statename.'</span><br/>
					<span align="right">City : '.$bookingData->provider->city.'</span><br/></td>
				</tr>';
			
						$patientMessage.='<tr>
							<td style="background-color:#fff;" valign="top"><br/>
								<span style="color:#f04e4e; text-transform:uppercase; font-size:14px;"><strong>Payment Method:</strong></span><br/><br/>';
								if($bookingData->payment_method==2){
									$patientMessage.='<span style="font-size:13px;">Paypal</span><br/>';
								}else{
									$patientMessage.='<span style="font-size:13px;">Insurance</span><br/>';
								}
							$patientMessage.='</td>
						</tr>
				
					</table>
				</td>
			</tr>
			</table><br/>
			<h3 style="color:#f04e4e; text-transform:uppercase;" align="center">Thank you, Brightseathealth!</h3><br/><br/>
		</div>';

		$patientemail = \Yii::$app->mailer->compose()
			->setTo($bookingData->email)
			->setFrom([$admindata->email => 'Brightseathealth'])
			->setSubject('Appointment booking from '.$bookingData->fullname.'')
			->setHtmlBody($patientMessage)
			->send();
    }
    #--------END------#

      #--------------SEND MAIL TO PROVIDER-----------#
    public function sendmailtoprovider($bookingData,$statename,$admindata,$logos){

		$providerMessage = '<div style="width:100%">
			<table width="55%" border="0" cellpadding="8" cellspacing="0" align="center" style="font-size:15px; font-family:arial; background-color:#fff; border:1px solid #ccc;">
				<tr style="background-color:#dedede">
					<td colspan="5">'.$logos.'</td>
				</tr>
				<tr>
					<td style="background:#dc7f7f; font-size:16px; border-right:1px dashed; border-color:#ccc;" colspan="3"><h3 style="text-transform:uppercase;">Thanks for booking your appointment.</h3>';
						$providerMessage .= 'Your appointment has been confirmed. Your appointment summary is below. Thank you again for your business.</td>
					<td  style="background:#dc7f7f; font-size:14px;"><strong>Order Questions?</strong><br/><br/>
					   <strong>Call Us :</strong> <span style="font-size:12px; font-family:arial;"><a style="color:#000000" href="tel:'.$admindata->landline.'">'.$admindata->landline.'</a></span><br/>
						<strong>Email :</strong> <span style="font-size:12px; font-family:arial;"><a style="color:#000000" href="mailto:'.$admindata->email.'">'.$admindata->email.'</a></span>
					</td>
				</tr>
				<tr>
					<td colspan="5" align="center"><h3 style="color: #f04e4e;font-weight: normal;">Your booking #' .$bookingData->booking_number . ' </h3><span style="font-size:12px;">as on <span style="color:red">'.date("M d, Y", strtotime($bookingData->booking_date)).'</span>  '.$bookingData->booking_time.'</span><br/><br/></td>
				</tr>
				<tr style="background-color:#f1f1f1; text-transform:uppercase;">
					<th colspan="4">Patient/Visitor Details:</th>
				</tr>
				<tr style="background-color:#fff">
					<td colspan="4" style="background-color:#fff width:100%">Name : <span style="color: #f04e4e;text-transform:uppercase; font-weight:bold;">'.$bookingData->fullname.'</span><br/>
					<span align="right">Email : '.$bookingData->email.'</span><br/>
					<span align="right">Phone No. : '.$bookingData->phone_no.'</span><br/>
					</td>
				</tr>';

				$providerMessage.='<tr>
					<td style="background-color:#fff;" valign="top"><br/>
						<span style="color:#f04e4e; text-transform:uppercase; font-size:14px;"><strong>Payment Method:</strong></span><br/><br/>';
						if($bookingData->payment_method==2){
							$providerMessage.='<span style="font-size:13px;">Paypal</span><br/>';
						}else{
							$providerMessage.='<span style="font-size:13px;">Insurance</span><br/>';
						}
					$providerMessage.='</td>
				</tr>
		
				</table>
			</td>
			</tr>
			</table><br/>
			<h3 style="color:#f04e4e; text-transform:uppercase;" align="center">Thank you, Brightseathealth!</h3><br/><br/>
		</div>';

		$provideremail = \Yii::$app->mailer->compose()
			->setTo($bookingData->provider->email)
			->setFrom([$admindata->email => 'Brightseathealth'])
			->setSubject('Appointment booking from '.$bookingData->fullname.'')
			->setHtmlBody($providerMessage)
			->send();
    }
    #--------END------#
}
