<?php

/* @var $this yii\web\View */

use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Order;
use yii\helpers\Url;

$this->title = 'Coffee Place Orders Management';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Orders Management Section</h1>
    </div>

    <div class="body-content">
        <h3>Orders List:</h3>
        <?php
            $dataProvider = new ActiveDataProvider([
                'query' => Order::find()->with('coffee')->with('user')->orderBy('status DESC'),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    [
                        'label' => 'Coffee Name',
                        'attribute' => 'coffee.name',
                    ],
                    [
                        'label' => 'User Name',
                        'attribute' => 'user.username',
                    ],
                    'quantity',
                    [
                        'label' => 'Amount To Pay',
                        'attribute' => 'amount_to_pay',
                    ],
                    'updated_at:datetime',
                    'status',
                ]
            ]);
        ?>

        <h3>To process an order use a form to set status: </h3>
        <?php echo '<h3>' . nl2br(Html::encode($message)) . '</h3>';?>

        <?php
            $order = new Order();
            $form = ActiveForm::begin([
                'id' => 'form-order-set-status',
                'action' => Url::to(['order/set-order-status']),
                'enableAjaxValidation' => true,
                'validationUrl' => Url::to(['/order/validate-order-processing']),
            ]);

            echo $form->field($order, 'id')->label('Order ID');
            echo $form->field($order, 'status')->dropDownList(Order::$statuses)->label('Order Status');
        ?>
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>
