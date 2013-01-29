<html>
	<body>
		To complete the reset of your password at <?php echo Yii::app()->name; ?>, please go to the following URL:<br />
		<?php $url=Yii::app()->createAbsoluteUrl('/account/account/completeResetPassword', array(
			'account_id'=>$verification->account_id,
			'code'=>$verification->code,
		)); 
		echo CHtml::link($url, $url); ?>
	</body>
</html>
