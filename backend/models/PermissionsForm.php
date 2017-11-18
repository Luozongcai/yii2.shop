<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9 0009
 * Time: 12:53
 */

namespace backend\models;


use yii\base\Model;

class PermissionsForm extends Model
{
    public $name;
    public $description;
    public $oldName;

    //场景 场景必须要对应验证规则
    const SCENARIO_Add = 'add';
    const SCENARIO_Edit = 'edit';


    public function attributeLabels()
    {
        return [
            'name'=>'权限(路由)',
            'description'=>'描述',

        ];
    }

    public function rules()
    {
        return [
            [['name','description'],'required'],
        //自定义验证规则.on表示只有在[]里的场景生效
            ['name','validateName','on'=>[self::SCENARIO_Add]],//添加时生效 修改时不生效
            ['name','validateUpdateName','on'=>[self::SCENARIO_Edit]],//修改时生效 修改时不生效

        ];
    }
    //添加时验证
    public function validateName(){
        //自定义验证方法 只处理验证失败的情况
        $auth = \Yii::$app->authManager;
        $model = $auth->getPermission($this->name);
        if($model){
            //权限已存在,添加错误信息
            $this->addError('name','权限已存在');
        }
    }
    //修改时验证权限名称
    public function validateUpdateName()
    {
        //只处理验证失败的情况  名称被修改,新名称已存在
        $auth = \Yii::$app->authManager;
        //新名称不等于原名称
        if($this->oldName != $this->name){
            //查看新名称是否存在
            $model = $auth->getPermission($this->name);
            if($model){
                //权限已存在
                $this->addError('name','权限已存在');
            }
        }
    }

    public function add(){
        //添加权限方法
        $auth = \Yii::$app->authManager;
        $permission = $auth->createPermission($this->name);
        $permission->description = $this->description;
        return $auth->add($permission);
    }

    public function update($name){
        //修改权限方法
        $auth = \Yii::$app->authManager;
        $permissions=$auth->getPermission($name);//获取name这条权限
        //给权限新的值
        $permissions->description=$this->description;
        $permissions->name=$this->name;
        //跟新数据
       return $auth->update($name,$permissions);
    }
}