


<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($models,'name')->textInput();
echo $form->field($models,'description')->textInput();
echo $form->field($models,'permissions',['inline'=>1])->checkboxList($permissions);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();