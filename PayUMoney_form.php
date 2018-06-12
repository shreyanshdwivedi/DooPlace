<?php
  /*
   *  @author   Gopal Joshi
   *  @wesite   www.sgeek.org
   *  @about    PayUMoney Payment Gateway integration in PHP
   */
  ob_start();
  session_start();

  include 'includes/fb-login.php';
    
  require_once('includes/gmail-setting.php');
  $login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';

  if(isset($_POST['numGuests'])) {
    $numGuests = $_POST['numGuests'];
    $checkIn = $_POST['checkIn'];
    $checkOut = $_POST['checkOut'];
    $amount = $_POST['amount'];
    $propertyID = $_POST['propertyID'];
    $numDays = $_POST['numDays'];

    $_SESSION['propertyID'] = $propertyID;
    $_SESSION['checkIn'] = $checkIn;
    $_SESSION['checkOut'] = $checkOut;

    $checkInDate = date('d F Y', strtotime($checkIn));
    $checkOutDate = date('d F Y', strtotime($checkOut));

    $conn = new mysqli("localhost", "root", "", "codingCampus");
    $stmt = $conn->prepare("SELECT * FROM property WHERE id=?");
    $stmt->bind_param("s", $propertyID);
    $stmt->execute();
    $property = $stmt->get_result()->fetch_assoc();
    $stmt->close(); 
  } else {
    header('Location: index.php');
  }

  if(isset($_POST['bookOne'])) {
    $_SESSION['adults'] = $_POST['adultGuests'];
    $_SESSION['children'] = $_POST['childGuests'];
    $_SESSION['countryCode'] = $_POST['countryCode'];
    $_SESSION['phoneNum'] = $_POST['phoneNumber'];
    $_SESSION['msg'] = $_POST['msg'];
  }

	$merchant_key  = "gtKFFx";
	$salt          = "eCwWELxi";
	$payu_base_url = "https://test.payu.in"; // For Test environment
	$action        = '';
	$currentDir	   = 'http://localhost/desktop/Website-CodingCampus/';
	$posted = array();
	if(!empty($_POST)) {
	  foreach($_POST as $key => $value) {    
	    $posted[$key] = $value; 
	  }
	}

	$formError = 0;
	if(empty($posted['txnid'])) {
	  $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
	} else {
	  $txnid = $posted['txnid'];
	}

	$hash         = '';
	$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

	if(empty($posted['hash']) && sizeof($posted) > 0) {
	  if(
          empty($posted['key'])
          || empty($posted['txnid'])
          || empty($posted['amount'])
          || empty($posted['firstname'])
          || empty($posted['email'])
          || empty($posted['phone'])
          || empty($posted['productinfo'])
          || empty($posted['surl'])
          || empty($posted['furl'])
	  ){
	    $formError = 1;

	  } else {
	   	$hashVarsSeq = explode('|', $hashSequence);
	    $hash_string = '';	
		foreach($hashVarsSeq as $hash_var) {
	      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
	      $hash_string .= '|';
	    }
	    $hash_string .= $salt;
	    $hash = strtolower(hash('sha512', $hash_string));
	    $action = $payu_base_url . '/_payment';
	  }
	} elseif(!empty($posted['hash'])) {
	  $hash = $posted['hash'];
	  $action = $payu_base_url . '/_payment';
	}
?>
<html>
  <head>
    <?php
      include 'includes/header.php';
      include 'includes/navbar.php';
    ?>
  <script>
    var hash = '<?php echo $hash ?>';
    function submitPayuForm() {
      if(hash == '') {
        return;
      }
      var payuForm = document.forms.payuForm;
      payuForm.submit();
    }
  </script>
  </head>
  <body onload="submitPayuForm()">
    <h2>PayU Form</h2>
    <br/>
    <?php if($formError) { ?>
      <span style="color:red">Please fill all mandatory fields.</span>
      <br/>
      <br/>
    <?php } ?>
        <div class="row">
            <div class="container">
                <div class="col-md-1">
                </div>
                <div class="col-md-10 col-sm-12">
                    <div class="col-sm-7 col-md-7">
                    <form action="<?php echo $action; ?>" method="post" name="payuForm">
                      <input type="hidden" name="key" value="<?php echo $merchant_key ?>" />
                      <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
                      <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
                      <input type="hidden" name="service_provider" value="" size="64" />
                      <input type="hidden" name="surl" value="<?php echo (empty($posted['surl'])) ? $currentDir.'success.php' : $posted['surl'] ?>" />
                      <input type="hidden" name="furl" value="<?php echo (empty($posted['furl'])) ? $currentDir.'failure.php' : $posted['furl'] ?>" />
                      <input type="hidden" name="checkIn" value="<?php echo($checkIn); ?>">
                      <input type="hidden" name="checkOut" value="<?php echo($checkOut); ?>">
                      <input type="hidden" name="numGuests" value="<?php echo($numGuests); ?>">
                      <input type="hidden" name="numDays" id="numDays" value="<?php echo($numDays); ?>">
                      <input type="hidden" name="propertyID" value="<?php echo($propertyID); ?>">
                      <div class="form-group">
                        <label>Amount</label>
                        <input class="form-control" name="amount" type="number" value="<?php echo(empty($posted['amount']) ? "150" : $posted['amount']); ?>" readonly/>
                      </div>
                      <div class="form-group">
                        <label>First Name</label>
                        <input class="form-control" type="text" name="firstname" id="firstname" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" />
                      </div>
                      <div class="form-group">
                        <label>Email</label>
                        <input class="form-control" type="email" name="email" id="email" value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>" />
                      </div>
                      <div class="form-group">
                        <label>Phone Number</label>
                        <input class="form-control" type="text" name="phone" value="<?php echo (empty($posted['phone'])) ? $_SESSION['countryCode'].$_SESSION['phoneNum'] : $posted['phone']; ?>" readonly/>
                      </div>
                      <div class="form-group">
                        <textarea name="productinfo" class="form-control" rows="3" readonly><?php echo (empty($posted['productinfo'])) ? $property['name'] : $posted['productinfo']; ?></textarea>
                      </div>
                      <?php if(!$hash) { ?>
                        <div class="form-group">
                          <input type="submit" class="btn btn-success btn-lg" style="width: 100%;" value="Continue">
                        </div>
                      <?php } ?>
                    </form>
                    </div>
                    <div class="col-sm-5 col-md-5" style="border: 1px solid #eee;">
                        <div style="padding-top: 10px; height: 80px;">
                            <div class="col-sm-5 col-md-5"> 
                                <img src="img/host_0.jpg" max-width="100%" height="75px">
                            </div>
                            <div class="col-sm-7 col-md-7" style="float: right; font-size: 18px;">
                                <span><b><?php echo($property['name']); ?></b></span>
                            </div>
                        </div>
                        <hr>
                        <div style="line-height: 2;">
                            <p> <?php echo($numGuests); ?> Guests </p>
                            <p> <?php echo($checkInDate); ?> - <?php echo($checkOutDate); ?> </p>
                        </div>
                        <hr>
                        <div style="line-height: 2;">
                            <div> 
                                <p style="float: right;"><?php echo($property['perDayRate']); ?> * <?php echo($numDays); ?> Night <p>
                                <p>Rs. <?php echo($amount); ?></p>
                            </div>
                            <div> 
                                <p style="float: right;">Service Fee <p>
                                <p>Rs. <?php echo(0.18*$property['perDayRate']); ?></p>
                            </div>
                            <hr>
                            <div> 
                                <p style="float: right;">Total <p>
                                <p>Rs. <?php echo($amount + (0.18*$property['perDayRate'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                </div>
            </div>
        </div>
        <?php
          include 'includes/footer.php';
        ?>
  </body>
</html>
