<?php
/**
 * Created by PhpStorm.
 * User: 22121
 * Date: 2017/7/26
 * Time: 16:50
 */

namespace backend\models;


use yii\base\Model;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permissions=[];
    public function rules()
    {
        return [
            [['name','description'],'required','message'=>'{attribute}不可为空!'],
            ['permissions','safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'description'=>'描述',
            'permissions'=>'权限分类',
        ];
    }
}