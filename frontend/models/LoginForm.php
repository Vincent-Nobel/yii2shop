<?php
namespace frontend\models;
use yii\base\Model;
use yii\helpers\Json;

class LoginForm extends Model{
    public $username;
    public $password;
    public $rememberMe;

    public function login(){
        //找到该用户
        $member=Member::findOne(['username'=>$this->username]);
        if($member){
            //验证用户信息
            if(\Yii::$app->security->validatePassword($this->password,$member->password_hash)){
                //验证通过
                $member->last_login_time=time();//保存最后登录时间
                \Yii::$app->user->login($member,$this->rememberMe ? 3600*24*7:0);
                $member->save();
                return Json::encode(['status'=>true,'msg'=>'登录成功']);
            }else{
                $this->addError('password','密码不正确');
            }
        }else{
            $this->addError('username','用户名不存在');
        }
    }

    public function rules()
    {
        return [
            [['username','password'],'required'],
            // ['code','captcha','captchaAction'=>'admin/captcha'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
        ];
    }
}