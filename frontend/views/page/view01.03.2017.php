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
                
                <?php if($model['slug'] == 'contactus.html'){ ?>
						<div class="col-md-6 well" style="padding-top:30px; text-align:center;">
							<?php echo $model['description']; ?>
						</div>	
						<div class="col-md-6 panel panel-default well"  style="text-align:center;">
							<h4 style="padding-top:10px;"><strong>Have a query?</strong></h4>
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
					<?php } else { ?>
                
					<div style="padding:20px 0 40px 0; text-align:left;"><?php echo $model['description']; ?>
					<?php } ?>
					</div>
            </aside>
        </div>
    </div>  
