<?php

/**
 * This is the model class for table "verification".
 *
 * The followings are the available columns in table 'verification':
 * @property integer $account_id
 * @property integer $type
 * @property string $code
 * @property string $data
 *
 * The followings are the available model relations:
 * @property Account $account
 */
class Verification extends CActiveRecord
{
	const TYPE_REGISTER = 1;

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
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('account_id, type, code', 'required'),
			array('account_id, type', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>128),
			array('data', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('account_id, type, code, data', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'account_id' => 'Account',
			'type' => 'Type',
			'code' => 'Code',
			'data' => 'Data',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('data',$this->data,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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