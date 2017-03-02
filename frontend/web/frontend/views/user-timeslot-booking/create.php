<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\UserTimeslotBooking */

$this->title = 'Create User Timeslot Booking';
$this->params['breadcrumbs'][] = ['label' => 'User Timeslot Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-timeslot-booking-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
