<?php


namespace dsj\adminuser\models;


use yii\base\Model;

class ResetPasswordForm extends Model
{
    public $password;
    public $password_repeat;

    public function rules()
    {
        return [

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password_repeat','compare','compareAttribute'=>'password','message'=>'两次输入密码不一致'],

        ];
    }

    public function attributeLabels()
    {
        return [

            'password' => '新密码',
            'password_repeat' => '重新输入新密码',
        ];
    }

    public function setNewPassword($id){
        $adminuser = Adminuser::findOne(['id' => $id]);
        $adminuser->setPassword($this->password);
        $adminuser->password_reset_token = '*';
        if ($adminuser->save()){
            return true;
        }
        return false;
    }

    public function  getFirstStrError(){
        $msg = '';
        foreach ($this->getErrors() as $item){
            $msg = $item[0];
            break;
        }
        return $msg;
    }
}