<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'imgFile')->fileInput();
echo $form->field($model,'goods_category_id')->dropDownList(\backend\models\GoodsCategory::getItems());

echo $form->field($model,'brand_id')->dropDownList(\backend\models\Brand::getItems());
echo $form->field($model,'market_price')->textInput();
echo $form->field($model,'shop_price')->textInput();
echo $form->field($model,'stock')->textInput();

echo $form->field($model,'status',['inline'=>1])->radioList([1=>'正常',0=>'回收站']);
echo $form->field($model,'is_on_sale',['inline'=>1])->radioList([0=>'下架',1=>'在售']);
echo $form->field($model,'sort')->textInput();
echo $form->field($model2,'content')->widget('\kucha\ueditor\UEditor',
    [
    'clientOptions' => [
    //编辑区域大小
    'initialFrameHeight' => '400',
    //设置语言
    'lang' =>'zh-cn', //为 en
    //定制菜单
    'toolbars' => [
       [
            'fullscreen', 'source', 'undo', 'redo', '|',
            'fontsize',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
            'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
            'forecolor', 'backcolor', '|',
            'lineheight', '|',
            'indent', '|'
        ],
    ]
]
    ]);

echo \yii\bootstrap\Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();
