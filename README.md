yii-account-module
==================

One simple account module for Yii framework

It currently allows basic operations on an account: register, login, logout, account, change email, change password and desactivate. Next contributions could be: keep time and IP of registration and last activity, account verification by email and account administration.

Demo: [http://fcofdeznra.me/account-module/](http://fcofdeznra.me/account-module/)

Installation
------------

* Unpack account module under your modules directory.

* Execute account/data/schema.mysql.sql script in your database.

* Enable account module in your configuration:

```
  'modules'=>array(
		...
		'account'=>array(
			'defaultController'=>'account',
		),
	),
```

* Change your login URL to /account/account/login:


```
	'user'=>array(
		...
		'loginUrl'=>array('/account/account/login'),
	),
```

* Add Register, Login, Account and Logout options to your main menu:

```
	<?php $this->widget('zii.widgets.CMenu',array(
		'items'=>array(
			...
			array('label'=>'Register', 'url'=>array('/account/account/register'), 'visible'=>Yii::app()->user->isGuest),
			array('label'=>'Login', 'url'=>array('/account/account/login'), 'visible'=>Yii::app()->user->isGuest),
			array('label'=>'Account', 'url'=>array('/account/account/account'), 'visible'=>!Yii::app()->user->isGuest),
			array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/account/account/logout'), 'visible'=>!Yii::app()->user->isGuest)
		),
	)); ?>
```
