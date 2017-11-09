<?php
/*$form = \yii\bootstrap\ActiveForm::begin(['id' => 'login-form']);
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'password')->passwordInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
*/?>
<div class="site-login">

    <div class="row">
        <div class="col-lg-5">
            <?php $form = \yii\bootstrap\ActiveForm::begin(); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

           <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div class="form-group">
                <?= \yii\bootstrap\Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
    </div>
</div>
