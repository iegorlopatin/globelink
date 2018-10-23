<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Coffee;
use yii\helpers\Json;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'logout',
                            'index',
                            'validate-coffee',
                            'create-update-coffee'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'validate-coffee' => ['post'],
                    'create-update-coffee' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->coffeeModel = new Coffee();

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
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
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays Coffee List/Update Page.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('/site/coffee');
    }

    /**
     * Processes Add/Update Coffee form data.
     */
    public function actionValidateCoffee()
    {
        $request = \Yii::$app->getRequest();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isPost && $this->coffeeModel->load($request->post())) {
            return ActiveForm::validate($this->coffeeModel);
        }
    }

    /**
     * Processes Add/Update Coffee form data.
     */
    public function actionCreateUpdateCoffee()
    {
        if ($this->coffeeModel->createUpdateCoffee()) {
            $message = 'New coffee has been successfully created!';
        } else {
            $message = 'An Error occurred while creating new coffee: '
                . Json::encode($this->coffeeModel->getErrors());
        }

        return $this->render('/site/coffee', ['message' => $message]);
    }
}
