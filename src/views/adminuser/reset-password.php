<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = false;
?>
<div class="reset-password-form">
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('提交', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
