<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserTimeslotBookingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Timeslot Bookings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-timeslot-booking-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User Timeslot Booking', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'provider_id',
            'user_id',
            'fullname',
            'email:email',
            // 'phone_no',
            // 'booking_date',
            // 'booking_time',
            // 'created_date',
            // 'updated_date',
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
