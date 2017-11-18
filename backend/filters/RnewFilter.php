<?php

namespace backend\filters;
use yii\base\ActionFilter;
use yii\web\HttpException;

class RnewFilter extends ActionFilter
{
    public function beforeAction($action)
    {

        //未登录可分登录
        if(\Yii::$app->user->isGuest){
            if($action->uniqueId == 'user/login'){
                return true;
            }

        }

        if(!\Yii::$app->user->isGuest){
            //登录,可访问,登出,改密码
        if($action->uniqueId == 'user/logout'){
            return true;
        }
        if ($action->uniqueId == 'user/pwd'){
            return true;
        }
        }
       if(!\Yii::$app->user->can($action->uniqueId)){
            //如果没有登录,则跳转到登录页面
            if(\Yii::$app->user->isGuest){
                //send()确保立刻跳转
                return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
            }else{
                throw new HttpException(403,'对不起,您没有该操作权限');
                return false;
            }

        }


        return parent::beforeAction($action);

    }
}