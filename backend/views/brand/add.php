<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'status')->radioList([0=>'隐藏',1=>'显示']);
echo $form->field($model,'imgFile')->fileInput();
echo \yii\bootstrap\Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();
