<h1>角色列表</h1>
<table class="table table-bordered well">
    <tr>

        <th>名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr  class="gradeB">
            <td><?=$model->name?></td>
            <td><?=$model->description?></td>
              <td>
                  <?=\yii\bootstrap\Html::a('修改',['edit','name'=>$model->name],['class'=>'btn btn-warning'])?>
                  <a href="javascript:;"  id="del" class="del btn-danger btn" data-id="<?=$model->name?>">删除</a>
              </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td><?=\yii\bootstrap\Html::a('添加',['add-role'],['class'=>'btn btn-primary  btn-lg']); ?></td>
    </tr>

</table>
<?php
//ajax删除
$url=\yii\helpers\Url::to(['auth/delete-role']);
$this->registerJs(
<<<JS
        $(".del").click(function(){
if(confirm('是否删除该角色?')){
var url = "{$url}";
var name = $(this).attr('data-id');
var that = this;
$.post(url,{name:name},function(data){
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
