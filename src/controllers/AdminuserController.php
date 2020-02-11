<?php

namespace dsj\adminuser\controllers;

use dsj\adminuser\models\Adminuser;
use dsj\adminuser\models\AdminuserSearch;
use dsj\components\controllers\WebController;
use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AdminuserController implements the CRUD actions for Adminuser model.
 */
class AdminuserController extends WebController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Adminuser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminuserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Adminuser model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Adminuser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Adminuser();

        if ($model->load(Yii::$app->request->post())) {
            $model->generateDefaultInfo();
            if ($model->save()){
                return $this->redirect(['index']);
            }
            Yii::$app->session->setFlash('danger',Json::encode($model->getErrors()));
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Adminuser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     *
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     *重置密码
     */
    public function actionResetPassword($id){

        $model = new ResetPasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()){
            if ($model->setNewPassword($id)){
                Yii::$app->session->setFlash('success','密码重置成功');
                return $this->redirect(['index']);
            }
            Yii::$app->session->setFlash('danger','密码重置失败');
        }

        return $this->render('reset-password',['model' => $model]);
    }

    public function actionResetPasswordAjax($id){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new ResetPasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()){
            if ($model->setNewPassword($id)){
                return ['code' => 200,'msg' => '密码重置成功'];
            }
        }
        return ['code' => 400,'msg' => $model->getFirstStrError()];
    }
    /**
     * Finds the Adminuser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Adminuser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Adminuser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     * 分配角色
     */
    public function actionAssign($id){
        $model = $this->findModel($id);
        $allRoleList = RbacItem::getAllRoles();
        $hasRoleList = RbacAssignment::getRolesByUser($id);
        $notHasRoleList = ToolsServer::getDiffArrayByPk($allRoleList,$hasRoleList,'name');
        if (Yii::$app->request->isAjax){
            $getData = Yii::$app->request->get();
            $user = $getData['id'];
            RbacAssignment::deleteAll(['user_id' => $user]);
            if (isset($getData['data'])){
                $data = $getData['data'];
                $dataArr = explode(',',$data);
                foreach ($dataArr as $item){
                    $childModel = new RbacAssignment();
                    $childModel->item_name = $item;
                    $childModel->user_id = $user;
                    $childModel->created_at = time();
                    if (!$childModel->save()){
                        return Json::encode(['code' => 100,'msg' => Json::encode($childModel->getErrors())]);
                    }
                }
            }
            return Json::encode(['code' => 200,'msg' => '角色分配成功']);
        }
        return $this->render('assign',[
            'model' => $model,
            'hasRoleList' => Json::encode($hasRoleList),
            'notHasRoleList' => Json::encode($notHasRoleList),
        ]);
    }



}
