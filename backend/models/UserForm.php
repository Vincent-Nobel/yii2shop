<?php
/**
 * Created by PhpStorm.
 * User: 22121
 * Date: 2017/7/25
 * Time: 19:50
 */

namespace backend\models;


use yii\base\Model;
use yii\db\ActiveRecord;

class UserForm extends Model
{
    public $password;//保存旧密码
    public $new_password;//再次确认新密码
    public $re_new_password;//原字段

    public function rules()
    {
        return [
            [['new_password', 'password','re_new_password'], 'required','message'=>'{attribute}不可为空！'],
        ];
    }
    public function attributeLabels()
    {
        return [
            're_new_password'=>'确认新密码',
            'password'=>'旧密码',
            'new_password'=>'新密码',
        ];
    }
}