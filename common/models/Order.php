<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $user_id
 * @property int $coffee_id
 * @property int $quantity
 * @property double $amount_to_pay
 * @property string $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Coffee $coffee
 * @property User $user
 */
class Order extends \yii\db\ActiveRecord
{
    public static $userOutOfStockMessage = 'We are sorry, but this coffee is no longer available on the stock.';
    public static $employeeOutOfStockMessage = 'There is not enough coffee on stock to complete this order.';
    public static $statuses = [
        'in_progress' => 'In Progress',
        'complete' => 'Complete',
        'canceled' => 'Canceled',
        'placed' => 'Placed',
    ];

    protected static $defaultStatus = 'placed';
    protected static $statusComplete = 'complete';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'coffee_id', 'quantity', 'amount_to_pay', 'status'], 'required'],
            [['id', 'user_id', 'coffee_id', 'quantity', 'created_at', 'updated_at'], 'integer'],
            [['amount_to_pay'], 'number'],
            [['status'], 'string', 'max' => 255],
            [['coffee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Coffee::className(), 'targetAttribute' => ['coffee_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'coffee_id' => 'Coffee ID',
            'quantity' => 'Quantity',
            'amount_to_pay' => 'Amount To Pay',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoffee()
    {
        return $this->hasOne(Coffee::className(), ['id' => 'coffee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Validates an order placed by user
     */
    public function validateOrder()
    {
        $request = \Yii::$app->getRequest();
        $requestData = $request->getBodyParam('Coffee');
        $coffee = Coffee::find()->where(['name' => $requestData['name']])->one();

        if (!$coffee) {
            $coffee = new Coffee();
        }

        if ($coffee->stock < $requestData['stock']) {
            $coffee->addError('coffee-stock', self::$userOutOfStockMessage);
        }

        return $coffee->hasErrors() ? $coffee->getErrors() : true;
    }

    /**
     * Places user order
     */
    public function place()
    {
        $request = \Yii::$app->getRequest();
        $requestData = $request->getBodyParam('Coffee');
        $coffee = Coffee::find()->where(['name' => $requestData['name']])->one();

        $this->setAttributes([
            'user_id' => \Yii::$app->user->id,
            'coffee_id' => $coffee->id,
            'quantity' => $requestData['stock'],
            'amount_to_pay' => $requestData['stock'] * $coffee->price,
            'status' => self::$defaultStatus
        ]);

        if ($this->save()) {
            return true;
        }

        return false;
    }

    /**
     * Validates an order which an Employee tries to update
     */
    public function validateOrderBeforeProcessing()
    {
        $request = \Yii::$app->getRequest();
        $requestData = $request->getBodyParam('Order');
        $order = (new self())->findOne($requestData['id']);
        if (!$order) {
            $order = new self;
            $order->addError('order-id', 'There are no order found in the database by given ID.');
        } else {
            if ($requestData['status'] == self::$statusComplete && $order->coffee->stock < $order->quantity) {
                $order->addError('order-status', self::$employeeOutOfStockMessage);
            }
        }

        return $order->hasErrors() ? $order->getErrors() : true;
    }

    /**
     * Processes customer order
     */
    public function setStatus()
    {
        $request = \Yii::$app->getRequest();
        $requestData = $request->getBodyParam('Order');
        $this->id = $requestData['id'];
        if ($this->refresh()) {
            $this->status = $requestData['status'];
            $coffeeOutOfStock = $this->coffee->stock < $this->quantity;
            if ($this->status == self::$statusComplete && $coffeeOutOfStock) {
                $this->addError('status', self::$employeeOutOfStockMessage);

                return false;
            }
            // todo: this is a good case for a transaction!
            if ($this->save()) {
                $this->updateRelatedCoffee();

                return true;
            };
        }

        return false;
    }

    /**
     * Updates related Coffee, reduces stock amount
     * @return bool
     */
    protected function updateRelatedCoffee()
    {
        if ($this->status == self::$statusComplete) {
            $this->coffee->stock = $this->coffee->stock - $this->quantity;
            if ($this->coffee->save()) {
                return true;
            }
        }

        return false;
    }
}