<h1>权限列表</h1>

<?php
//<!-- DataTables CSS -->
//<link rel="stylesheet" type="text/css" href="@web/DataTables/media/css/jquery.dataTables.css">
$this->registerCssFile('@web/DataTables/media/css/jquery.dataTables.css');
//<!-- jQuery -->
//<script type="text/javascript" charset="utf8" src="@web/DataTables/media/js/jquery.js"></script>

//<!-- DataTables -->
//<script type="text/javascript" charset="utf8" src="@web/DataTables/media/js/jquery.dataTables.js"></script>
$this->registerJsFile('@web/DataTables/media/js/jquery.dataTables.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
?>

<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>名称(路由)</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->name?></td>
            <td><?=$model->description?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['edit','name'=>$model->name],['class'=>'btn btn-warning'])?>
                <a href="javascript:;"  id="del" class="del btn-danger btn" data-id="<?=$model->name?>">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
   <!-- <tr>
        <td><?/*=\yii\bootstrap\Html::a('添加',['add-permissions'],['class'=>'btn btn-primary  btn-lg']); */?></td>
    </tr>-->
    </tbody>
</table>

<?php

//ajax删除
$url=\yii\helpers\Url::to(['auth/delete-permissions']);
$this->registerJs(
    <<<JS
    $(".del").click(function(){
        if(confirm('是否删除该权限(路由)?')){
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


$this->registerJs(
<<<JS
/*$(document).ready( function () {
    $('#table_id_example').DataTable();
} );*/

$(document).ready(function() {
        $('#table_id_example').DataTable({
            "sPaginationType" : "full_numbers",
            "oLanguage" : {
                "sLengthMenu": "每页显示 _MENU_ 条记录",
                "sZeroRecords": "抱歉， 没有找到",
                "sInfo": "从 _START_ 到 _END_ /共 _TOTAL_ 条数据",
                "sInfoEmpty": "没有数据",
                "sInfoFiltered": "(从 _MAX_ 条数据中检索)",
                "sZeroRecords": "没有检索到数据",
                 "sSearch": "名称:",
                "oPaginate": {
                "sFirst": "首页",
                "sPrevious": "前一页",
                "sNext": "后一页",
                "sLast": "尾页"
                }
                     
            }
        }
        );
    });
JS

);