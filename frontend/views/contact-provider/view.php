<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

use yii\imagine\Image;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\widgets\Innerheaderlogo;

?>

<div class="container">
        <div class="row">
            <aside class="col-md-12">
                <h2 style="padding-top:30px; text-align:center;"><?php echo $model['page_title']; ?></h2>
                
                <?php if($model['slug'] == 'contactus.html' ){ ?>
						<div class="col-md-6 well" style="padding-top:30px; text-align:center;">
							<?php echo $model['description']; ?>
						</div>	
						<div class="col-md-6 panel panel-default well"  style="text-align:center;">
							<h4 style="padding-top:10px;"><strong>Have a question?</strong></h4>
						
                            
                            		
    				
					<?php   
                            $form = ActiveForm::begin(['id' => 'form-signup']); 
                            ?>
                            <?= $form->field($contactmodel, 'name')->textInput(['maxlength' => true,'placeholder' => 'Enter Your Name'])->label(false); ?>
                            <?= $form->field($contactmodel, 'email')->textInput(['maxlength' => true,'placeholder' => 'Enter Your Email'])->label(false); ?>
                            <?= $form->field($contactmodel, 'phone')->textInput(['maxlength' => true,'placeholder' => 'Enter Your Contact Number'])->label(false); ?>
                            <?= $form->field($contactmodel, 'subject')->textInput(['maxlength' => true,'placeholder' => 'Enter Your Subject'])->label(false); ?>
                            <?= $form->field($contactmodel, 'message')->textarea(['rows' => 6,'placeholder' => 'Enter Your Query'])->label(false); ?>
                            <div class="form-group">
                                <?= Html::submitButton($contactmodel->isNewRecord ? 'Submit' : 'Submit', ['class' => $contactmodel->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                            </div>
                            <?php ActiveForm::end(); ?>
                                        
                    </div>
                  
                      
                            </div>
					<?php } else { ?>
                
					<div style="padding:20px 0 40px 0; text-align:left;"><?php echo $model['description']; ?>
					<?php } ?>
					</div>
					</div>
		 <?php if($model['slug'] == 'ContactProvider.html' ){ ?>
						
						<div class="col-md-6 well" style="padding-top:30px; text-align:center;">
					<p><strong>Email: </strong><br />
					css@healthcare800.com</p>
					
					<p>&nbsp;</p>
					
					<p>&nbsp;</p>
					
					<p><strong>Toll Free:</strong><br />
					888-800-7608 - Customer Service</p>
					
					<p>&nbsp;</p>
					
					<p>&nbsp;</p>
					
					<p><strong>Tel: </strong><br />
					301-423-7808 - Local Customer Service<br />
					301-423-7210 - Administrative<br />
					571-765-6084 - IT Department<br />
					571-765-6083 - Marketing</p>
					
					<p>&nbsp;</p>
					
					<p>&nbsp;</p>
					
					<p>&nbsp;</p>

						</div>	
						<div class="col-md-6 panel panel-default well"  style="text-align:center;">
							<h5 style="padding-top:10px;"><strong>Please fill out the form below.</strong></h5>
                            
                            		<?php   
                            $form = ActiveForm::begin(['id' => 'form-signup']); 
                            ?>
    				<div class="form-group">
						<?= $form->field($contactmodel, 'name')->textInput(['maxlength' => true,'placeholder' => 'Enter Your Name'])->label(false); ?>
					<?= $form->field($contactmodel, 'email')->textInput(['maxlength' => true,'placeholder' => 'Enter Your Email'])->label(false); ?>
					<?= $form->field($contactmodel, 'phone')->textInput(['maxlength' => true,'placeholder' => 'Enter Your Contact Number'])->label(false); ?>
					<?= $form->field($contactmodel, 'provider')->textInput(['maxlength' => true,'placeholder' => 'Enter The Providers Name'])->label(false); ?>
					<?= $form->field($contactmodel, 'subject')->textInput(['maxlength' => true,'placeholder' => 'Enter Your Subject'])->label(false); ?>
                            		<?= $form->field($contactmodel, 'message')->textarea(['rows' => 6,'placeholder' => 'Enter Your Message'])->label(false); ?>
                            <div class="form-group">
                                <?= Html::submitButton($contactmodel->isNewRecord ? 'Submit' : 'Submit', ['class' => $contactmodel->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                   
                            </div>
                            <?php ActiveForm::end(); ?>
					<?php } else { ?>
                
					<div style=" text-align:left;">
					<?php } ?>
					</div>
					
					
		
	           </aside>
        </div>
    </div>  
    
    
 
