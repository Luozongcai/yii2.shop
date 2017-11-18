<h1>商品列表</h1>
<?php

$form = \yii\bootstrap\ActiveForm::begin([
'action'=>\yii\helpers\Url::toRoute(['goods/list']),
'method'=>'get',
]);
echo "商品名称:".\yii\helpers\Html::input('text','name',$name);
echo "货号:". \yii\helpers\Html::input('text','sn',$sn);
echo "价格下限:". \yii\helpers\Html::input('text','down',$down);
echo "价格上限:". \yii\helpers\Html::input('text','top',$top);
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
?>

<table class="table well table-bordered">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>商品分类</th>
        <th>品牌分类</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>添加时间</th>
        <th>LOGO图片</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->sn?></td>
            <td><?=\backend\models\Goods::findOne($model->id)->getGoodsCategory()->one()->name;?></td>
            <td><?=\backend\models\Goods::findOne($model->id)->getBrand()->one()->name;?></td>
            <td><?=$model->market_price?></td>
            <td><?=$model->stock?></td>
            <td><?=$model->is_on_sale==1?'在售':'下架';?></td>
            <td><?=$model->status==1?'正常':'回收站';?></td>
            <td><?=date('Y-m-d H:i:s',$model->create_time)?></td>
            <td><img src="<?=$model->logo?>" width="80" ></td>


            <!--  <td><?/*=$model->article_category->name*/?></td>-->
            <!--<td><?/*=\backend\models\Article::findOne($model->id)->getArticleCategory()->one()->name;*/?></td>
         -->

            <td>
                <?=\yii\bootstrap\Html::a('相册',['gallery','id'=>$model->id],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::a('修改',['edit','id'=>$model->id],['class'=>'btn btn-warning'])?>
                   <a href="javascript:;"  id="del" class="del btn-danger btn" data-id="<?=$model->id?>">删除</a>
                <?=\yii\bootstrap\Html::a('预览',['preview','id'=>$model->id],['class'=>'btn btn-danger'])?>

            </td>
        </tr>
    <?php endforeach;?>
    <tr>
            <td><?=\yii\bootstrap\Html::a('添加',['add'],['class'=>'btn btn-primary  btn-lg']); ?></td>
        </tr>

</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager
]);

//ajax删除
$url=\yii\helpers\Url::to(['goods/delete']);
$this->registerJs(
    <<<JS
    $(".del").click(function(){
        if(confirm('是否删除该商品?')){
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
        }
    });
JS

);


