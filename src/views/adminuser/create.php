<?php


/* @var $this yii\web\View */
/* @var $model backend\models\Adminuser */

$this->title = '创建用户';
$this->params['breadcrumbs'][] = ['label' => '后台用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="adminuser-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
