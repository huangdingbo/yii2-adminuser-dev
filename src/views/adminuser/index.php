<?php

use dsj\components\grid\ResponsiveActionColumn;
use dsj\components\grid\ResponsiveGridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AdminuserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '后台用户管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="adminuser-index">

    <p>
        <?= Html::button('创建用户', ['class' => 'btn btn-success data-create','url' => Url::to(['create'])]) ?>
    </p>

    <?= ResponsiveGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layerOption' => [
            'reset-password' => [
                'type' => 2,
                'area' => ['900px','400px'],
                'shadeClose'=> true,
            ],
        ],
        'pager'=>[
            'firstPageLabel'=>"首页",
            'prevPageLabel'=>'上一页',
            'nextPageLabel'=>'下一页',
            'lastPageLabel'=>'尾页',
        ],
        'columns' => [
            'username',
            'nickname',
            'email:email',
            [
                'attribute' => 'pic',
                "format"=>'raw',
                'value' => function ($model) {
                    return Html::img($model->pic,["width"=>"30","height"=>"30"]);
                },
            ],
            'last_login_time',

            [
                'class' => ResponsiveActionColumn::className(),
                'template' => '{view} {update} {assign} {reset-password} {delete}',
                'header' => '操作',
                'buttons' => [
                    'assign' => function ($url, $model, $key) {
                        return Html::a('分配角色',$url,[
                            'class' => 'btn btn-info btn-sm',
                        ]);
                    },
                    'reset-password' => function ($url, $model, $key) {
                        return Html::button('重置密码', [
                            'url' => $url,
                            'class' => 'btn btn-success btn-sm data-reset-password',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
