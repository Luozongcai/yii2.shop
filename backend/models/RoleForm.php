<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9 0009
 * Time: 12:52
 */

namespace backend\models;


use yii\base\Model;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permissions;
    public $oldName;

    //场景 场景必须要对应验证规则
    const SCENARIO_Add = 'add';
    const SCENARIO_Edit = 'edit';


    public function attributeLabels()
    {
        return [
            'name'=>'角色名',
            'description'=>'描述',
            'permissions'=>'选择权限'
        ];
    }

    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe'],
            //自定义规则
            ['name','validateName','on'=>[self::SCENARIO_Add]],//添加时生效 修改时不生效
            ['name','validateUpdateName','on'=>[self::SCENARIO_Edit]],//修改时生效 修改时不生效

        ];
    }


    //添加时验证
    public function validateName(){
        //自定义验证方法 只处理验证失败的情况
        $auth = \Yii::$app->authManager;
        $model = $auth->getRole($this->name);
        //$model = $auth->getPermission($this->name);
        if($model){
            //角色已存在,添加错误信息
            $this->addError('name','角色已存在');
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
            $model = $auth->getRole($this->name);
            if($model){
                //角色已存在
                $this->addError('name','角色已存在');
            }
        }
    }

    public function update($name){
        //修改角色方法
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole($name);//获取name这个角色
        //给角色新的值
        $role->description=$this->description;
        $role->name=$this->name;

        //删掉所有权限
        $auth-> removeChildren($role);
        //重新分配权限
        if ($this->permissions ){
            foreach ($this->permissions as $permissionName){
                $permission = $auth->getPermission($permissionName);
                $auth->addChild($role,$permission);
            }
        }

        return  $auth->update($name,$role);
    }


}