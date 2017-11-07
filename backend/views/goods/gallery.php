<?php

//webuploader图片上传------------------------------------
//1.注册css,js
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className(),//指定依赖关系
]);

$url=\yii\helpers\Url::to(['goods/upload']);
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
        mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'
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
    <div><img id="img" width="1000" /></div>

<?php
//-----------------------------------

?>
<table class="table">
    <tr></tr>
    <tr>
        <th><h2>图片</h2></th>
        <th><h2>操作</h2></th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><img src="<?=$model->path?>" width="1000"></td>
            <td>
                  <a href="javascript:;"  id="del" class="del btn-danger btn" data-id="<?=$model->id?>">删除</a>
            </td>
        </tr>
    <?php endforeach;?>

</table>
<?php


//ajax删除
$url=\yii\helpers\Url::to(['goods/gallery-delete']);
$this->registerJs(
    <<<JS
    $(".del").click(function(){
            var url = "{$url}";
            var id = $(this).attr('data-id');
            var that = this;
            $.post(url,{id:id},function(data){
                if(data == 'yes'){
                    //alert('删除成功');
                    $(that).closest('tr').fadeOut();
                }else{    
                    alert(data);
                }
            });
        
    });
JS

);


