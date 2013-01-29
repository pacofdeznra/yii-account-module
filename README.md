yii-account-module
==================

One simple account module for Yii framework

It allows basic operations on an account, see [changelog](#changelog) and [roadmap](#roadmap) for current and next features.

Demo: [http://fcofdeznra.me/account-module/](http://fcofdeznra.me/account-module/)

Changelog
---------

###v0.2 (Jan 29, 2013)

* Requires [mail extension](https://github.com/fcofdeznra/yii-mail-extension).
* Register and change email are verified by email.
* Reset password.

###v0.1 (Jan 18, 2013)

* Register, login, logout, account, change email, change password and desactivate.

Roadmap
-------

* Keep time and IP of registration and last activity.
* Account administration.

Installation
------------

* Unpack account module under your modules directory.

* Unpack [mail extension](https://github.com/fcofdeznra/yii-mail-extension) under your extensions directory and configure it.

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

* Show user flashes in your main layout:

```
    <?php foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
    } ?>
```
