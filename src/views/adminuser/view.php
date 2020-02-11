<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Adminuser */

$this->title = '查看用户:' . $model->nickname;
?>
<div class="adminuser-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'nickname',
            'email:email',
            'profile:ntext',
            [
                'attribute' => 'pic',
                "format"=>'raw',
                'value' => function($model){
                    return Html::img($model->pic,["width"=>"50","height"=>"50"]);
                }
            ],
            'last_login_time',
        ],
    ]) ?>

</div>
