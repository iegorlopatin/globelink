<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Coffee;
use yii\helpers\Url;

$this->title = 'Coffee Place';
?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Coffee Place</h1>
    </div>
    <div class="body-content">
        <h3>We have available:</h3>
        <?php
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => \common\models\Coffee::find()->where('stock > 0'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        echo \yii\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'name',
                'intensity',
                'price',
            ]
        ]);
        ?>
        <h2>Want some coffee? Place your order here:</h2>
        <?php echo '<h3>' . nl2br(Html::encode($message)) . '</h3>';?>
        <?php
            $coffee = new Coffee();
            $form = ActiveForm::begin([
                'id' => 'form-place-order',
                'action' => Url::to(['/site/submit-order']),
                'enableAjaxValidation' => true,
                'validationUrl' => Url::to(['/site/validate-order']),
            ]);

            echo $form->field($coffee, 'name')->dropDownList($coffee->getAvailableCoffee());
            echo $form->field($coffee, 'stock')->label('Quantity');
        ?>
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
