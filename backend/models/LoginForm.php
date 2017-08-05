<?php
/**
 * Created by PhpStorm.
 * User: 22121
 * Date: 2017/7/24
 * Time: 19:11
 */

namespace backend\models;


use yii\base\Model;

class LoginForm extends Model
{
    /**
     * @var
     */
    public $username;
    public $password_hash;
    public $remeber;
    public $code;

    public function rules()
    {
        return [
          [['username','password_hash'],'required','message'=>'不能为空'],
            ['remeber','safe'],
            //验证码验证规则
            ['code','captcha','captchaAction'=>'user/captcha'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password_hash' => '密码',
            'remeber' => '记住密码',
            'code' => '验证码'
        ];
    }

}