<?php

class AccountController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $defaultAction='account';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'confirmRegister' action
				'actions'=>array('confirmRegister'),
				'users'=>array('*'),
			),
			array('allow', // allow anonymous user to perform 'register' and 'login' actions
				'actions'=>array('register','login'),
				'users'=>array('?'),
			),
			array('allow', // allow authenticated user to perform 'logout', 'account', 'changeEmail', 'changePassword' and 'desactivate' actions
				'actions'=>array('logout','account','changeEmail','changePassword','desactivate'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Registers a new account.
	 * If registration is successful, the browser will be redirected to the to the previous page.
	 */
	public function actionRegister()
	{
		$model=new Account('register');
	
		if(isset($_POST['Account']))
		{
			$model->attributes=$_POST['Account'];
			if($model->validate())
			{
				$unhashedPassword=$model->password;
				$model->password=$model->hashPassword($model->password);
				$model->save(false);
				
				$verification=new Verification;
				$verification->account_id=$model->id;
				$verification->type=Verification::TYPE_REGISTER;
				$verification->code=$verification->generateCode();
				$verification->save(false);
				
				Yii::app()->mailer->sendMIME(
					Yii::app()->name.' <'.Yii::app()->params['adminEmail'].'>',
					$model->email,
					'Registration at '.Yii::app()->name,
					'',
					$this->renderPartial('/verification/register', array(
						'verification'=>$verification,
					), true)
				);
				
				$model->password=$unhashedPassword;
				$model->login();
				
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
	
		$this->render('register',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Confirms an account registration
	 */
	public function actionConfirmRegister($account_id, $code)
	{
		$verification=Verification::model()->findByPk(array(
			'account_id'=>$account_id,
			'type'=>Verification::TYPE_REGISTER,
		));
		
		if($verification)
		{
			if($verification->validateCode($code))
			{
				$verification->delete();
				
				Yii::app()->user->setFlash('success','Your registration has been confirmed');
			}
			else
			{
				Yii::app()->user->setFlash('error','Your registration could not be confirmed');
			}
		}
		else
		{
			Yii::app()->user->setFlash('notice','Your registration is already confirmed');
		}
		
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new Account('login');
	
		// collect user input data
		if(isset($_POST['Account']))
		{
			$model->attributes=$_POST['Account'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * Displays the user account.
	 */
	public function actionAccount()
	{
		$this->render('account',array(
			'model'=>$this->loadModel(Yii::app()->user->id),
		));
	}

	/**
	 * Changes the account email.
	 * If change is successful, the browser will be redirected to the 'account' page.
	 */
	public function actionChangeEmail()
	{
		$model=new Account('changeEmail');
	
		if(isset($_POST['Account']))
		{
			$model->attributes=$_POST['Account'];
			if($model->validate())
			{
				$account=$this->loadModel(Yii::app()->user->id);
				if($account->validatePassword($model->password))
				{
					$account->email=$model->email;
					$account->save(false);
					Yii::app()->user->name=$model->email;
					$this->redirect(array('account'));
				}
				else
				{
					$model->addError('password','Incorrect password.');
				}
			}
		}
	
		$this->render('changeEmail',array(
			'model'=>$model,
		));
	}

	/**
	 * Changes the account password.
	 * If change is successful, the browser will be redirected to the 'account' page.
	 */
	public function actionChangePassword()
	{
		$model=new Account('changePassword');
	
		if(isset($_POST['Account']))
		{
			$model->attributes=$_POST['Account'];
			if($model->validate())
			{
				$account=$this->loadModel(Yii::app()->user->id);
				if($account->validatePassword($model->oldPassword))
				{
					$account->password=$account->hashPassword($model->password);
					$account->save(false);
					$this->redirect(array('account'));
				}
				else
				{
					$model->addError('oldPassword','Incorrect old password.');
				}
			}
		}
	
		$this->render('changePassword',array(
			'model'=>$model,
		));
	}

	/**
	 * Desactivates the user account.
	 * If desactivation is successful, the browser will be redirected to the homepage.
	 */
	public function actionDesactivate()
	{
		$model=new Account('desactivate');
	
		if(isset($_POST['Account']))
		{
			$model->attributes=$_POST['Account'];
			if($model->validate())
			{
				$account=$this->loadModel(Yii::app()->user->id);
				if($account->validatePassword($model->password))
				{
					$account->delete();
					Yii::app()->user->logout();
					$this->redirect(Yii::app()->homeUrl);
				}
				else
				{
					$model->addError('password','Incorrect old password.');
				}
			}
		}
	
		$this->render('desactivate',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Account the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Account::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
