<?php
/* @var $this AccountController */
/* @var $model Account */

$this->breadcrumbs=array(
	'Account',
);

$this->menu=array(
	array('label'=>'Change Email', 'url'=>array('changeEmail')),
	array('label'=>'Change Password', 'url'=>array('changePassword')),
	array('label'=>'Desactivate', 'url'=>array('desactivate')),
);
?>

<h1>Account</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'email',
	),
)); ?>
