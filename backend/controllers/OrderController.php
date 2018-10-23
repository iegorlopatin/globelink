<?php
namespace backend\controllers;

use common\models\Order;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Orders controller
 */
class OrderController extends Controller
{
    protected $request;
    protected $coffeeModel;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'validate-order-processing',
                            'set-order-status',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'validate-order-processing' => ['post'],
                    'set-order-status' => ['get', 'post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Renders Orders Page
     */
    public function actionIndex()
    {
        return $this->render('/order/orders');
    }

    /**
     * Validates Order Processing
     */
    public function actionValidateOrderProcessing()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        return (new Order())->validateOrderBeforeProcessing();
    }

    /**
     * Processes Order (Sets order status)
     */
    public function actionSetOrderStatus()
    {
        $order = new Order();
        if ($order->setStatus()) {
            $message = 'Status of the order has been successfully updated!';
        } else {
            $message =  'Order has not been updated. Reason:' . Json::encode($order->getErrors());
        }

        return $this->render('/order/orders', ['message' => $message]);
    }
}
