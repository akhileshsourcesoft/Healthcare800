<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>Healthcare800</title>
	<link rel="icon" type="image/png" href="<?php echo Yii::$app->getUrlManager()->createUrl('images/favicon.png');?>" />
    <?php $this->head() ?>
	<link href="<?php echo Yii::$app->getUrlManager()->createUrl('css/bootstrap.min.css');?>" rel="stylesheet">
	<link href="<?php echo Yii::$app->getUrlManager()->createUrl('css/style.css');?>" rel="stylesheet" type="text/css">
	<link href="<?php echo Yii::$app->getUrlManager()->createUrl('css/custom.css');?>" rel="stylesheet" type="text/css">
	<link href="<?php echo Yii::$app->getUrlManager()->createUrl('css/font-awesome.min.css');?>" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="<?php echo Yii::$app->getUrlManager()->createUrl('js/jquery-ui-1.10.3.custom.min.js');?>"></script> 
	<script src="<?php echo Yii::$app->getUrlManager()->createUrl('js/custom.js');?>"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.1/jquery.validate.js"></script> 
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyAd21sctUnPuU2g6_r6BGeumGNggrKtKaA"></script>

</head>
<body>
<?php $this->beginBody() ?>
<!--START HEADER-->
<?php include_once('header.php'); ?> 
<!--END HEADER--->
<!--SERVICES CATEGORIES-->
<?php
//$action_name = Yii::$app->controller->action->id;
$controller = Yii::$app->controller->id;
if($controller=='site'){
	include_once('servicesCategory.php'); 
}
?> 
<!--END--->
<!--VIEW BODY-->
<?php echo $content; ?>
<!--END BODY-->
<!--START FOOTER-->
<?php include_once('footer.php'); ?> 
<!--END FOOTER-->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<script>
	$(document).ready(function($){
		$("#user-landline").mask("999-999-9999");
	});
</script>
