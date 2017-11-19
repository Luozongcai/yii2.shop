<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
//echo $form->field($model,'imgFile')->fileInput();
echo $form->field($model,'logo')->hiddenInput();

//webuploader图片上传------------------------------------
//1.注册css,js
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className(),//指定依赖关系
    //  'position'=>\yii\web\View::POS_END
]);

$url=\yii\helpers\Url::to(['upload2']);
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
    $("#goods-logo").val(response.url);
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
    <div><img id="img" width="200" /></div>

<?php
//-----------------------------------

//echo $form->field($model,'goods_category_id')->dropDownList(\backend\models\GoodsCategory::getItems());


//商品分类id
echo $form->field($model,'goods_category_id')->hiddenInput();

//==============ZTREE=====================
//加载ztree静态资源 css js
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
$nodes = \yii\helpers\Json::encode(\yii\helpers\ArrayHelper::merge([['id'=>0,'parent_id'=>0,'name'=>'顶级分类']],\backend\models\GoodsCategory::getNodes()));
$this->registerJs(
    <<<JS
var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            callback:{
                onClick: function(event, treeId, treeNode){
                    //获取被点击节点的id
                    var id= treeNode.id;
                    //将id写入parent_id的值
                    $("#goods-goods_category_id").val(id);
                }
            }
            ,
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$nodes};
        
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        //展开所有节点
        zTreeObj.expandAll(true);
        //选中节点(回显)   
        //获取节点  ,根据节点的id搜索节点
        var node = zTreeObj.getNodeByParam("id", {$model->goods_category_id}, null);   
        zTreeObj.selectNode(node);
        
JS

);
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';
//=========================================

echo $form->field($model,'brand_id')->dropDownList(\backend\models\Brand::getItems());
echo $form->field($model,'market_price')->textInput();
echo $form->field($model,'shop_price')->textInput();
echo $form->field($model,'stock')->textInput();
echo $form->field($model,'status',['inline'=>1])->radioList([1=>'正常',0=>'回收站']);
echo $form->field($model,'is_on_sale',['inline'=>1])->radioList([0=>'下架',1=>'在售']);
echo $form->field($model,'sort')->textInput();


//富文本编辑器
echo $form->field($model2,'content')->widget('\kucha\ueditor\UEditor',
    [
    'clientOptions' => [
    //编辑区域大小
    'initialFrameHeight' => '400',
    //设置语言
    'lang' =>'zh-cn', //为 en
    //定制菜单
    /*'toolbars' => [
       [
            'fullscreen', 'source', 'undo', 'redo', '|',
            'fontsize',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
            'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
            'forecolor', 'backcolor', '|',
            'lineheight', '|',
            'indent', '|'
        ],
    ]*/
]
    ]);

echo \yii\bootstrap\Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();
