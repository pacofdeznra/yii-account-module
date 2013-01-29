<?php

/**
 * This is the model class for table "account".
 *
 * The followings are the available columns in table 'account':
 * @property integer $id
 * @property string $email
 * @property string $password
 */
class Account extends CActiveRecord
{
	public $confirmEmail;
	public $confirmPassword;
	public $rememberMe;
	public $oldPassword;

	private $_identity;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Account the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'account';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('email', 'required', 'on'=>array('register','login','changeEmail','resetPassword')),
			array('email', 'length', 'max'=>128, 'on'=>array('register','changeEmail')),
			array('email', 'email', 'on'=>array('register','changeEmail')),
			array('email', 'unique', 'on'=>array('register','changeEmail')),
			array('email', 'exist', 'on'=>'resetPassword'),
			array('password', 'required', 'on'=>array('register','login','changeEmail','changePassword','completeResetPassword','desactivate')),
			array('password', 'length', 'min'=>6, 'max'=>128, 'on'=>array('register','changePassword','completeResetPassword')),
			array('confirmEmail', 'required', 'on'=>array('register','changeEmail')),
			array('confirmEmail', 'compare', 'compareAttribute'=>'email', 'on'=>array('register','changeEmail')),
			array('confirmPassword', 'required', 'on'=>array('register','changePassword','completeResetPassword')),
			array('confirmPassword', 'compare', 'compareAttribute'=>'password', 'on'=>array('register','changePassword','completeResetPassword')),
			array('rememberMe', 'boolean', 'on'=>'login'),
			array('oldPassword', 'required', 'on'=>'changePassword'),
			array('password', 'authenticate', 'on'=>'login'),
		);
	}

	/**
	 * Generates the password hash.
	 * @param string password
	 * @return string hash
	 */
	public function hashPassword($password)
	{
		return crypt($password);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->email,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Incorrect email or password.');
		}
	}

	/**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
	public function validatePassword($password)
	{
		return crypt($password,$this->password)===$this->password;
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->email,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
	
	/**
	 * Finds an account by email
	 * @param string $email The email
	 */
	public function findByEmail($email)
	{
		return $this->find('LOWER(email)=?',array(strtolower($email)));
	}
}