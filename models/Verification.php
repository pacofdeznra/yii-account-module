<?php

/**
 * This is the model class for table "verification".
 *
 * The followings are the available columns in table 'verification':
 * @property integer $account_id
 * @property integer $type
 * @property string $code
 * @property string $data
 */
class Verification extends CActiveRecord
{
	const TYPE_REGISTER=1;
	const TYPE_CHANGE_EMAIL=2;
	const TYPE_RESET_PASSWORD=3;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Verification the static model class
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
		return 'verification';
	}

	/**
	 * Generates a random code
	 */
	public function generateCode()
	{
		return md5(mt_rand());
	}
	
	/**
	 * Validates the code
	 */
	public function validateCode($code)
	{
		return $code===$this->code;
	}
}