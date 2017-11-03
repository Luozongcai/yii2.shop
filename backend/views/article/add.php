<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textInput();
echo $form->field($model,'status')->radioList([0=>'隐藏',1=>'显示']);
echo $form->field($model,'article_category_id')->dropDownList(\backend\models\Brand::getItems());
echo $form->field($model,'sort')->textInput();
echo $form->field($model2,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();
