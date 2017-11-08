<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'email')->textInput();
echo $form->field($model,'status')->radioList(['1'=>'启用','0'=>'禁用']);
echo \yii\bootstrap\Html::submitButton('提交',['class' => 'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();
