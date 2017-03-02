<div class="container">
  <div class="row">
	  <div class="col-md-6 col-sm-6 contact-forms col-md-offset-3">
		  <h3>Thank you,</h3>
		  <div class="thanks-form">
				<h4>Your request has been sent successfully. We will contact you soon.</h4>
		 </div>
	  </div>
	</div>
</div>
<?php 
$session = Yii::$app->session;
$session->remove('userpassword');
unset($_SESSION['userpassword']);
 ?>

