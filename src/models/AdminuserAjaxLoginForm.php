<?php


namespace dsj\adminuser\models;


use Yii;
use yii\base\Model;

class AdminuserAjaxLoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = false;
    public $tocken;
    private $_user;

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'rememberMe' => '记住密码',
            'password' => '密码',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password','tocken'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],

            ['tocken', 'validateTocken'], //验证码
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '账号或密码错误。');
            }
        }
    }

    public function validateTocken($attribute, $params){
        if (!$this->hasErrors()){
            if ($this->tocken != Yii::$app->session->get('tocken')){
                $this->addError($attribute, 'tocken错误。');
            }
        }
    }

    /**
     * @return bool
     *
     */
    public function login()
    {

        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * @return \frontend\models\Adminuser|null
     *
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Adminuser::findByUsername($this->username);
        }

        return $this->_user;
    }

    public function generateTocken(){
        $tocken = Yii::$app->security->generatePasswordHash(time().rand(10000,99999));

        Yii::$app->session->set('tocken',$tocken);

        return $tocken;
    }

    public function deleteTocken(){
        Yii::$app->session->remove('tocken');
    }
}