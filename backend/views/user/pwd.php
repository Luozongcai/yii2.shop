<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'password1')->textInput();
echo $form->field($model,'password2')->passwordInput();
echo $form->field($model,'password3')->passwordInput();
echo \yii\bootstrap\Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();