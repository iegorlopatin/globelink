<?php

/* @var $this yii\web\View */

$this->title = 'Coffee Place Stock';

use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Coffee Management Section</h1>
    </div>

    <div class="body-content">
        <h3>Coffee list:</h3>
        <?php
            $dataProvider = new ActiveDataProvider([
                'query' => \common\models\Coffee::find(),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'name',
                    'intensity',
                    'price',
                    'stock',
                    'updated_at:datetime'
                ]
            ]);
        ?>

        <h3> Create/Update Coffee: </h3>
        <p>* Put a new name if you want to create new coffee</p>
        <p>* Use an existing name to update coffee</p>
        <?php echo '<h3>' . nl2br(Html::encode($message)) . '</h3>';?>

        <?php
            $coffee = new \common\models\Coffee();
            $form = ActiveForm::begin([
                'id' => 'coffee-create-update',
                'action' => Url::to(['site/create-update-coffee']),
                'enableAjaxValidation' => true,
                'validationUrl' => Url::to(['site/validate-coffee']),
            ]);

            echo $form->field($coffee, 'name');
            echo $form->field($coffee, 'intensity');
            echo $form->field($coffee, 'price');
            echo $form->field($coffee, 'stock');
        ?>
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
