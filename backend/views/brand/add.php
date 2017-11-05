<?php
/**
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'status')->radioList([0=>'隐藏',1=>'显示']);
echo $form->field($model,'logo')->hiddenInput();

//webuploader图片上传------------------------------------
  //1.注册css,js
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className(),//指定依赖关系
  //  'position'=>\yii\web\View::POS_END
]);

    $url=\yii\helpers\Url::to(['upload']);
//上传图片
    $this->registerJs(
      <<<Js
      var uploader = WebUploader.create({
    // 选完文件后，是否自动上传。
    auto: true,
    // swf文件路径
    swf: '/js/Uploader.swf',
    // 文件接收服务端。
    server: '{$url}',
    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',
    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif',
    }
});

// 回显图片
uploader.on( 'uploadSuccess', function( file ,response) {
    //在回选图片上+src路径
    $("#img").attr('src',response.url);
    //将图片地址写入logo
    $("#brand-logo").val(response.url);
});

Js
);
?>
    <!--dom结构部分  按钮-->
    <div id="uploader-demo">
        <!--用来存放item-->
        <div id="fileList" class="uploader-list"></div>
        <div id="filePicker">选择图片</div>
    </div>
    <div><img id="img" width="100" /></div>

<?php
//-----------------------------------

//echo \yii\bootstrap\Html::img($model->logo?$model->logo:false,['id'=>'img','height'=>50]);
echo \yii\bootstrap\Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();
