<h1>菜单列表</h1>
<table class="table table-bordered well">
    <tr>

        <th>名称</th>
        <th>路由</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr  class="gradeB">
            <td><?=str_repeat('--',$model->parent_id==0?0:1*4).$model->name?></td>
           <!-- <td><?/*=$model->name*/?></td>-->
            <td><?=$model->url?></td>
            <td><?=$model->sort?></td>
            <td>
                 <?php
                if(\Yii::$app->user->can('menu/edit')){
                    echo  \yii\bootstrap\Html::a('修改',['edit','id'=>$model->id],['class'=>'btn btn-warning']);
                }
                if(\Yii::$app->user->can('menu/delete')){
                    echo   '<a href="javascript:;"  id="del" class="del btn-danger btn" data-id="<?=$model->id?>">删除</a>';

                }
                ?>
               <!-- <a href="javascript:;"  id="del" class="del btn-danger btn" data-id="<?/*=$model->id*/?>">删除</a>
           --> </td>
        </tr>
    <?php endforeach;?>

    <tr>
        <td><?=\yii\bootstrap\Html::a('添加',['add'],['class'=>'btn btn-primary  btn-lg']); ?></td>
    </tr>
</table>
<?php
//分页
/*echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager
]);*/

//ajax删除
$url=\yii\helpers\Url::to(['menu/delete']);
$this->registerJs(
    <<<JS
        $(".del").click(function(){
if(confirm('是否删除菜单?')){
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
