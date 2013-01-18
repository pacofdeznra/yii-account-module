<?php
/* @var $this AccountController */
/* @var $model Account */
/* @var $form CActiveForm */

$this->breadcrumbs=array(
	'Account'=>array('account'),
	'Change Email',
);
?>

<h1>Change Email</h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'account-form',
	'enableClientValidation'=>true,
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'confirmEmail'); ?>
		<?php echo $form->textField($model,'confirmEmail',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'confirmEmail'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Change'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->