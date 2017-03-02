<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\Session;
use common\models\UserTimeslotBooking;
use common\models\InsuranceCompanies;
use common\models\User;
use common\models\State;

/**
 * StateController implements the CRUD actions for State model.
 */
class CancelController extends Controller
{   
    /**
     * Displays a single Status model.
     * @param integer $id
     * @return mixed
     */
    public function actionIndex()
    {	
		Yii::$app->user->logout();
		$session = Yii::$app->session;
		$session->destroy();					
		unset($session);
		return $this->render('index');
    }

    public function actionOrdercancel(){
		
	    $bookingid = Yii::$app->request->get('bookingid');
        $bookingData = UserTimeslotBooking::find()->joinWith('provider')->joinWith('paymentHistories')->where(['hc_user_timeslot_booking.booking_number'=>$bookingid])->andWhere(['hc_user_timeslot_booking.status'=>1])->one();
        $admindata = User::find()->where(['user_role_id'=>1])->andWhere(['status'=>1])->one();
		$insuranceComp = InsuranceCompanies::find()->where(['id'=>$bookingData->insurance_companies_id])->one();
		if(!empty($insuranceComp->name)){ 
			$insuranceComp = $insuranceComp->name;
		}else{ 
			$insuranceComp = 'Null';
		}

		$bookingpaytype = $bookingData->booking_pay_type=='P'?'Paypal':'Blue pay';
		$card_number = (strlen(base64_decode($bookingData->card_number))>4)?'xxxx-xxxx-xxxx-'.substr(base64_decode($bookingData->card_number),-4):base64_decode($bookingData->card_number);
		!empty($bookingData->paymentHistories[0]->transaction_id)?$transaction_id = $bookingData->paymentHistories[0]->transaction_id:$transaction_id = 'Null';
		!empty($bookingData->paymentHistories[0]->payment_status)?$payment_status = $bookingData->paymentHistories[0]->payment_status:$payment_status = 'Null';
		!empty($bookingData->paymentHistories[0]->payment_date)?$payment_date = $bookingData->paymentHistories[0]->payment_date:$payment_date = 'Null';
		
       	#--UPDATE THE BOOKING STATUS---#
		if(isset($bookingid)){
			$timeslotbooking = UserTimeslotBooking::findOne($bookingData->id);
			$timeslotbooking->booking_status_id = '3';
			$timeslotbooking->save(false);
		}
		#---END---#  

	   $logos = '<a href="'.Yii::$app->params['HOST_INFO'].'" target="_blank"><img style="background:#f1f1f1;" src="'.Yii::$app->params['HOST_INFO'].'images/logo.png" alt="" width=""></a>';
			$this->sendmailtopatient($bookingData,$admindata,$logos,$insuranceComp);
			$this->sendmailtoprovider($bookingData,$admindata,$logos);
			
			$html2 = '<div style="width:100%">
			<table width="100%" border="0" cellpadding="8" cellspacing="0" align="center" style="font-size:15px; font-family:arial; background-color:#fff; border:1px solid #ccc;">
				<tr style="background-color:#f1f1f1">
					<td colspan="5">'.$logos.'</td>
				</tr>
				<tr>
					<td style="background:#dc7f7f; font-size:16px; border-right:1px dashed; border-color:#ccc;" colspan="3"><h3 style="text-transform:uppercase;">Thanks for booking your appointment.</h3>';
						$html2 .= 'Your appointment has been cancelled. Your appointment summary is below.</td>
					<td  style="background:#dc7f7f; font-size:14px;"><strong>Order Questions?</strong><br/><br/>
					   <strong>Call Us :</strong> <span style="font-size:12px; font-family:arial;"><a style="color:#000000" href="tel:'.$admindata->landline.'">'.$admindata->landline.'</a></span><br/>
						<strong>Email :</strong> <span style="font-size:12px; font-family:arial;"><a style="color:#000000" href="mailto:'.$admindata->email.'">'.$admindata->email.'</a></span>
					</td>
				</tr>
				<tr>
					<td colspan="5" align="center"><h3 style="color: #f04e4e;font-weight: normal;">Your booking number #' .$bookingData->booking_number . ' </h3><span style="font-size:12px;">as on <span style="color:red">'.date("M d, Y", strtotime($bookingData->booking_date)).'</span>  '.$bookingData->booking_time.'</span><br/><br/></td>
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
				<tr>
					<td style="background-color:#fff;">
						<span style="color:#f04e4e;text-transform:uppercase;font-size:14px;"><strong>Payment Method:</strong></span><br/>
					</td>';
					$html2.='<td style="background-color:#fff;"><span style="text-transform:uppercase;font-size:14px;">';
					if($bookingData->payment_method==2){
						$html2.='Non Insurance';
					}else{
						$html2.='Insurance';
					}
					$html2.='</span></td>
				</tr>';
				if($bookingData->payment_method==1){ 
					$html2.='<tr style="background-color:#f1f1f1; text-transform:uppercase;">
						<th colspan="4">Insurance Details:</th>
					</tr>
					<tr>
					<th colspan="4">
						<table width="100%" border="0" cellpadding="5" cellspacing="1" align="center" style="font-size:15px; font-family:arial; background-color:#fff;">
							<tr>
								<th align="left">Name of the insurance</th>
								<th align="left">Insurance Card No.</th>
								<th align="left">Address of insurance company</th>
							</tr>
							<tr>
								<td align="left">'.$insuranceComp.'</td>
								<td align="left">'.$bookingData->insuranceid_card.'</td>
								<td align="left">'.$bookingData->insurance_comp_address.'</td>
							</tr>
							<tr>
								<th align="left">Group No. of insurance card</th>
								<th align="left">&nbsp;</th>
								<th align="left">&nbsp;</th>
							</tr>
							<tr>
								<td align="left">'.$bookingData->group_insurance.'</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						</table>
						</th>
					</tr>';
				}else{
					$html2.='<tr style="background-color:#f1f1f1; text-transform:uppercase;">
						<th colspan="4">Payment Details:</th>
					</tr>
					<tr>
					<th colspan="4">
						<table width="100%" border="0" cellpadding="8" cellspacing="0" align="center" style="font-size:15px; font-family:arial; background-color:#fff;">
							<tr>
								<th align="left">Paid By</th>
								<th align="left">Transaction Id</th>
								<th align="left">Payment Status</th>
								<th align="left">Payment Date</th>
							</tr>
							<tr>
								<td align="left">'.$bookingpaytype.'</td>
								<td align="left">'.$transaction_id.'</td>
								<td align="left">'.$payment_status.'</td>
								<td align="left">'.$payment_date.'</td>
							</tr>';
							if($bookingData->booking_pay_type=='B'){
							$html2.='<tr>
								<th align="left">Card Holder Name</th>
								<th align="left">Card Number</th>
								<th align="left">Card Type</th>
								<th align="left">Expiry Date</th>
							</tr>
							<tr>
								<td align="left">'.$bookingData->card_name.'</td>
								<td align="left">'.$card_number.'</td>
								<td align="left">'.ucfirst($bookingData->cc_type).'</td>
								<td align="left">'.$bookingData->expiry_date.'</td>
							</tr>';
							}
						$html2.='<tr>
								<th align="left">Amount</th>
								<th align="left">&nbsp;</th>
								<th align="left">&nbsp;</th>
								<th align="left">&nbsp;</th>
							</tr>
							<tr>
								<td align="left">$'.number_format($bookingData->amount,2).'</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							</table>
						</th>
					</tr>';
				}
				$html2.='<tr style="background-color:#f1f1f1; text-transform:uppercase;">
					<th colspan="4">Provider Details:</th>
				</tr>
				<tr style="background-color:#fff">
					<td colspan="4" style="background-color:#fff width:100%">Name : <span style="color: #f04e4e;text-transform:uppercase; font-weight:bold;">'.$bookingData->provider->fname.' '.$bookingData->provider->lname.'</span><br/>
					<span align="right">Email : '.$bookingData->provider->email.'</span><br/>
					<span align="right">Phone No. : '.$bookingData->provider->landline.'</span><br/>';
					if($bookingData->payment_method==2){ 
						$html2.='<span align="right">Fee : $'.number_format($bookingData->amount,2).'</span><br/>';
					}
					$html2.='<span align="right">City : '.$bookingData->provider->city.'</span><br/>';
					if(!empty($bookingData->provider->state->name)){
						$html2.='<span align="right">State : '.$bookingData->provider->state->name.'</span><br/>';
					}
					if(!empty($bookingData->provider->country->name)){
						$html2.='<span align="right">Country : '.$bookingData->provider->country->name.'</span><br/>';
					}
					$html2.='</td>
					</tr>
					</table>
				</td>
			</tr>
			</table><br/>
			<h3 style="color:#f04e4e; text-transform:uppercase;" align="center">Thank you, HealthCare800!</h3><br/><br/>
		</div>';
			
			$email = \Yii::$app->mailer->compose()
				->setTo($admindata->email)
				->setFrom([$bookingData->email => $bookingData->fullname])
				->setSubject('Appointment booking from HealthCare800.')
				->setHtmlBody($html2)
				->send();
			if($email){
				return $this->redirect(["index"]);
			}
		
    } 
    
      #--------------SEND MAIL TO PATIENT/VISITOR-----------#
    public function sendmailtopatient($bookingData,$admindata,$logos,$insuranceComp){
		
		$bookingpaytype = $bookingData->booking_pay_type=='P'?'Paypal':'Blue pay';
		$card_number = (strlen(base64_decode($bookingData->card_number))>4)?'xxxx-xxxx-xxxx-'.substr(base64_decode($bookingData->card_number),-4):base64_decode($bookingData->card_number);
		!empty($bookingData->paymentHistories[0]->transaction_id)?$transaction_id = $bookingData->paymentHistories[0]->transaction_id:$transaction_id = 'Null';
		!empty($bookingData->paymentHistories[0]->payment_status)?$payment_status = $bookingData->paymentHistories[0]->payment_status:$payment_status = 'Null';
		!empty($bookingData->paymentHistories[0]->payment_date)?$payment_date = $bookingData->paymentHistories[0]->payment_date:$payment_date = 'Null';
		$patientMessage = '<div style="width:100%">
			<table width="100%" border="0" cellpadding="8" cellspacing="0" align="center" style="font-size:15px; font-family:arial; background-color:#fff; border:1px solid #ccc;">
				<tr style="background-color:#f1f1f1">
					<td colspan="5">'.$logos.'</td>
				</tr>
				<tr>
					<td style="background:#dc7f7f; font-size:16px; border-right:1px dashed; border-color:#ccc;" colspan="3"><h3 style="text-transform:uppercase;">Thanks for booking your appointment.</h3>';
						$patientMessage .= 'Your appointment has been cancelled. Your appointment summary is below.</td>
					<td  style="background:#dc7f7f; font-size:14px;"><strong>Order Questions?</strong><br/><br/>
					   <strong>Call Us :</strong> <span style="font-size:12px; font-family:arial;"><a style="color:#000000" href="tel:'.$admindata->landline.'">'.$admindata->landline.'</a></span><br/>
						<strong>Email :</strong> <span style="font-size:12px; font-family:arial;"><a style="color:#000000" href="mailto:'.$admindata->email.'">'.$admindata->email.'</a></span>
					</td>
				</tr>
				<tr>
					<td colspan="5" align="center"><h3 style="color: #f04e4e;font-weight: normal;">Your booking number #' .$bookingData->booking_number . ' </h3><span style="font-size:12px;">as on <span style="color:red">'.date("M d, Y", strtotime($bookingData->booking_date)).'</span>  '.$bookingData->booking_time.'</span><br/><br/></td>
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
				<tr>
					<td style="background-color:#fff;">
						<span style="color:#f04e4e;text-transform:uppercase;font-size:14px;"><strong>Payment Method:</strong></span><br/>
					</td>';
					$patientMessage.='<td style="background-color:#fff;"><span style="text-transform:uppercase;font-size:14px;">';
					if($bookingData->payment_method==2){
						$patientMessage.='Non Insurance';
					}else{
						$patientMessage.='Insurance';
					}
					$patientMessage.='</span></td>
				</tr>';
				if($bookingData->payment_method==1){ 
					$patientMessage.='<tr style="background-color:#f1f1f1; text-transform:uppercase;">
						<th colspan="4">Insurance Details:</th>
					</tr>
					<tr>
					<th colspan="4">
						<table width="100%" border="0" cellpadding="5" cellspacing="1" align="center" style="font-size:15px; font-family:arial; background-color:#fff;">
							<tr>
								<th align="left">Name of the insurance</th>
								<th align="left">Insurance Card No.</th>
								<th align="left">Address of insurance company</th>
							</tr>
							<tr>
								<td align="left">'.$insuranceComp.'</td>
								<td align="left">'.$bookingData->insuranceid_card.'</td>
								<td align="left">'.$bookingData->insurance_comp_address.'</td>
							</tr>
							<tr>
								<th align="left">Group No. of insurance card</th>
								<th align="left">&nbsp;</th>
								<th align="left">&nbsp;</th>
							</tr>
							<tr>
								<td align="left">'.$bookingData->group_insurance.'</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						</table>
						</th>
					</tr>';
				}else{
					$patientMessage.='<tr style="background-color:#f1f1f1; text-transform:uppercase;">
						<th colspan="4">Payment Details:</th>
					</tr>
					<tr>
					<th colspan="4">
						<table width="100%" border="0" cellpadding="8" cellspacing="0" align="center" style="font-size:15px; font-family:arial; background-color:#fff;">
							<tr>
								<th align="left">Paid By</th>
								<th align="left">Transaction Id</th>
								<th align="left">Payment Status</th>
								<th align="left">Payment Date</th>
							</tr>
							<tr>
								<td align="left">'.$bookingpaytype.'</td>
								<td align="left">'.$transaction_id.'</td>
								<td align="left">'.$payment_status.'</td>
								<td align="left">'.$payment_date.'</td>
							</tr>';
							if($bookingData->booking_pay_type=='B'){
							$patientMessage.='<tr>
								<th align="left">Card Holder Name</th>
								<th align="left">Card Number</th>
								<th align="left">Card Type</th>
								<th align="left">Expiry Date</th>
							</tr>
							<tr>
								<td align="left">'.$bookingData->card_name.'</td>
								<td align="left">'.$card_number.'</td>
								<td align="left">'.ucfirst($bookingData->cc_type).'</td>
								<td align="left">'.$bookingData->expiry_date.'</td>
							</tr>';
							}
						$patientMessage.='<tr>
								<th align="left">Amount</th>
								<th align="left">&nbsp;</th>
								<th align="left">&nbsp;</th>
								<th align="left">&nbsp;</th>
							</tr>
							<tr>
								<td align="left">$'.number_format($bookingData->amount,2).'</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							</table>
						</th>
					</tr>';
				}
				$patientMessage.='<tr style="background-color:#f1f1f1; text-transform:uppercase;">
					<th colspan="4">Provider Details:</th>
				</tr>
				<tr style="background-color:#fff">
					<td colspan="4" style="background-color:#fff width:100%">Name : <span style="color: #f04e4e;text-transform:uppercase; font-weight:bold;">'.$bookingData->provider->fname.' '.$bookingData->provider->lname.'</span><br/>
					<span align="right">Email : '.$bookingData->provider->email.'</span><br/>
					<span align="right">Phone No. : '.$bookingData->provider->landline.'</span><br/>';
					if($bookingData->payment_method==2){ 
						$patientMessage.='<span align="right">Fee : $'.number_format($bookingData->amount,2).'</span><br/>';
					}
					$patientMessage.='<span align="right">City : '.$bookingData->provider->city.'</span><br/>';
					if(!empty($bookingData->provider->state->name)){
						$patientMessage.='<span align="right">State : '.$bookingData->provider->state->name.'</span><br/>';
					}
					if(!empty($bookingData->provider->country->name)){
						$patientMessage.='<span align="right">Country : '.$bookingData->provider->country->name.'</span><br/>';
					}
					$patientMessage.='</td>
					</tr>
					</table>
				</td>
			</tr>
			</table><br/>
			<h3 style="color:#f04e4e; text-transform:uppercase;" align="center">Thank you, HealthCare800!</h3><br/><br/>
		</div>';

		$patientemail = \Yii::$app->mailer->compose()
			->setTo($bookingData->email)
			->setFrom([$admindata->email => 'HealthCare800'])
			->setSubject('Appointment booking from '.$bookingData->fullname.'')
			->setHtmlBody($patientMessage)
			->send();
    }
    #--------END------#

      #--------------SEND MAIL TO PROVIDER-----------#
    public function sendmailtoprovider($bookingData,$admindata,$logos){

		$providerMessage = '<div style="width:100%">
			<table width="55%" border="0" cellpadding="8" cellspacing="0" align="center" style="font-size:15px; font-family:arial; background-color:#fff; border:1px solid #ccc;">
				<tr style="background-color:#f1f1f1">
					<td colspan="5">'.$logos.'</td>
				</tr>
				<tr>
					<td style="background:#dc7f7f; font-size:16px; border-right:1px dashed; border-color:#ccc;" colspan="3"><h3 style="text-transform:uppercase;">Thanks for booking your appointment.</h3>';
						$providerMessage .= 'Your appointment has been cancelled. Your appointment summary is below.</td>
					<td  style="background:#dc7f7f; font-size:14px;"><strong>Order Questions?</strong><br/><br/>
					   <strong>Call Us :</strong> <span style="font-size:12px; font-family:arial;"><a style="color:#000000" href="tel:'.$admindata->landline.'">'.$admindata->landline.'</a></span><br/>
						<strong>Email :</strong> <span style="font-size:12px; font-family:arial;"><a style="color:#000000" href="mailto:'.$admindata->email.'">'.$admindata->email.'</a></span>
					</td>
				</tr>
				<tr>
					<td colspan="5" align="center"><h3 style="color: #f04e4e;font-weight: normal;">Your booking number #' .$bookingData->booking_number . ' </h3><span style="font-size:12px;">as on <span style="color:red">'.date("M d, Y", strtotime($bookingData->booking_date)).'</span>  '.$bookingData->booking_time.'</span><br/><br/></td>
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
					<td style="background-color:#fff;">
						<span style="color:#f04e4e;text-transform:uppercase;font-size:14px;"><strong>Payment Method:</strong></span><br/>
					</td>';
					$providerMessage.='<td style="background-color:#fff;"><span style="text-transform:uppercase;font-size:14px;">';
					if($bookingData->payment_method==2){
						$providerMessage.='Non Insurance';
					}else{
						$providerMessage.='Insurance';
					}
					$providerMessage.='</span></td>
				</tr>
		
				</table>
			</td>
			</tr>
			</table><br/>
			<h3 style="color:#f04e4e; text-transform:uppercase;" align="center">Thank you, HealthCare800!</h3><br/><br/>
		</div>';

		$provideremail = \Yii::$app->mailer->compose()
			->setTo($bookingData->provider->email)
			->setFrom([$admindata->email => 'HealthCare800'])
			->setSubject('Appointment booking from '.$bookingData->fullname.'')
			->setHtmlBody($providerMessage)
			->send();
    }
    #--------END------#
}
