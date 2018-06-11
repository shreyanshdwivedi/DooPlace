<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<div>
		<h2>Payment Gateway Testing Sample</h2>
		<h3>Fill the form and submit it for starting the transaction...</h3>
	</div>

<div>
<table>
	<form name="postForm" action="form_process.php" method="POST" >
	<tr><td>txnid</td><td><input type="text" name="txnid" value="<?php echo $txnid=time().rand(1000,99999); ?>" /></td></tr>
	<tr><td>amount</td><td><input type="text" name="amount" value="" /></td></tr>
	<tr><td>firstname</td><td><input type="text" name="firstname" value="" /></td></tr>
	<tr><td>email</td><td><input type="text" name="email" value="" /></td></tr>
	<tr><td>phone</td><td><input type="text" name="phone" value="" /></td></tr>
	<tr><td>productinfo</td><td><input type="text" name="productinfo" value="" /></td></tr>
	<input type="hidden" name="service_provider" value="payu_paisa" size="64" />
	<tr><td>success url</td><td><input type="text" name="surl" value="http://localhost/desktop/Website-CodingCampus/payment/success.php" size="64" /></td></tr>
	<tr><td>failure url</td><td><input type="text" name="furl" value="http://localhost/desktop/Website-CodingCampus/payment/fail.php" size="64" /></td></tr>
	<tr><td><input type="submit" /></td><td><input type="reset" /></td></tr>
	</form>
</table>
</div>
</body>
</html>