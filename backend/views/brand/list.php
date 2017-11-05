<h1>品牌列表</h1>
<table class="table table-bordered well">
    <tr>
        <th>ID</th>
        <th>品牌名称</th>
        <th>LOGO</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><img src="<?=$model->logo?>" width="80"></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['edit','id'=>$model->id],['class'=>'btn btn-warning'])?>
            <a href="javascript:;"  id="del" class="del btn-danger btn" data-id="<?=$model->id?>">删除</a>
        </td>
    </tr>
    <?php endforeach;?>
      <!--  <tr>
            <td><?/*=\yii\bootstrap\Html::a('添加',['add'],['class'=>'btn btn-primary  btn-lg']); */?></td>
        </tr>-->

</table>
<?php
echo \yii\widgets\LinkPager::widget([
        'pagination'=>$pager
]);

//ajax删除
$url=\yii\helpers\Url::to(['brand/delete']);
$this->registerJs(
    <<<JS
    $(".del").click(function(){
        if(confirm('是否删除该品牌?')){
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


