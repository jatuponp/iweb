<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use adLDAP\adLDAP;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model {

    public $username;
    public $password;
    public $rememberMe = true;
    public $search;
    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword() {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            $ds = ldap_connect('10.0.99.33');
            $bind = @ldap_bind($ds);
            $search = ldap_search($ds, "ou=khonkaen,o=kku", "uid=$this->username");

            if (ldap_count_entries($ds, $search) != 1) {
                $this->addError('password', 'Incorrect username or password.');
            }
            $info = ldap_get_entries($ds, $search);

            //Now, try to rebind with their full dn and password.
            $bind = @ldap_bind($ds, $info[0][dn], $this->password);
            if (!$bind || !isset($bind)) {
                if (!$user) {
                    $this->addError('password', 'Incorrect username or password.');
                }
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            if($this->getUser()){
                return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            }else{
                Yii::$app->getResponse()->redirect(\yii\helpers\Url::to(['site/signup', 'uid' => $this->username]));
            }
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser() {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

}
