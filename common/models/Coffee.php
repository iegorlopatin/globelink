<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "coffee".
 *
 * @property int $id
 * @property string $name
 * @property string $intensity
 * @property double $price
 * @property int $stock
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Order[] $orders
 */
class Coffee extends \yii\db\ActiveRecord
{
    public $user_id;

    protected $request;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coffee';
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
            [['name', 'intensity', 'price',], 'required'],
            [['price'], 'number'],
            [['name'], 'unique'],
            [['stock', 'created_at', 'updated_at'], 'integer'],
            [['name', 'intensity'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'intensity' => 'Intensity',
            'price' => 'Price',
            'stock' => 'Stock',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['coffee_id' => 'id']);
    }

    /**
     * Provides coffee available on stock (only "name" field)
     * @return array
     */
    public function getAvailableCoffee()
    {
        $result = [];
        $coffees = self::find()->addSelect('name')->asArray()->where('stock > 0')->all();

        foreach ($coffees as $key => $coffee) {
            $result[$coffee['name']] = $coffee['name'];
        }

        return $result;
    }

    /**
     * Handles create and update actions
     * @return bool
     */
    public function createUpdateCoffee()
    {
        $this->request = \Yii::$app->getRequest();
        $requestData = $this->request->getBodyParam('Coffee');
        $coffee = self::find()->where(['name' => $requestData['name']])->one();
        if (!$coffee) {
            return $this->createNewCoffee();
        }

        return $this->updateCoffee();
    }

    /**
     * Creates new coffee
     * @return bool
     */
    protected function createNewCoffee()
    {
        $coffee = new Coffee();
        $coffee->load($this->request->post());
        if ($coffee->save()) {
            return true;
        }
        $this->addErrors($coffee->getErrors());

        return false;
    }

    /**
     * Updates existing coffee
     * @return bool
     */
    protected function updateCoffee()
    {
        $this->load($this->request->post());
        if ($this->save()) {
            return true;
        }

        return false;
    }
}