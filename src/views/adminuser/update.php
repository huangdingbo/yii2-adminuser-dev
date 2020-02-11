<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Adminuser */

$this->title = '修改用户:' . $model->nickname;
?>
<div class="adminuser-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
