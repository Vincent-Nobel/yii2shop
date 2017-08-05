<?php
/**
 * Created by PhpStorm.
 * User: 22121
 * Date: 2017/7/26
 * Time: 14:53
 */

namespace backend\models;


use yii\base\Model;

class PermissionForm extends Model
{
    public $name;
    public $description;
    const SCENARIO_ADD='add';//场景常量
    public function rules()
    {
//        required
        return [
            [['name','description'],'required','message'=>'{attribute}不可为空'],
            ['name','validateName','on'=>self::SCENARIO_ADD]
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'description'=>'描述',
        ];
    }
    public function validateName()
    {
        $authManage=\Yii::$app->authManager;
        //检测该权限名称是否已存在
        if ($authManage->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }
    }
}