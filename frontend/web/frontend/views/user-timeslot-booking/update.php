<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserTimeslotBooking */

$this->title = 'Update User Timeslot Booking: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Timeslot Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-timeslot-booking-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
