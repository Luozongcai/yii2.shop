<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($models,'name')->textInput();
echo $form->field($models,'description')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();