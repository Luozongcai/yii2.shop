<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'email')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class' => 'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();
