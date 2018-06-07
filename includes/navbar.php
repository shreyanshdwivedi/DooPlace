<nav class="navbar navbar-fixed-top navbar-no-bg" role="navigation">
<div class="container">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.html">Bootstrap Navbar Menu Template</a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->                
    <div class="collapse navbar-collapse" id="top-navbar-1">
        <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Become a Host</a></li>
            <li><a href="#">About Us</a></li>
            <?php
              if(isset($_SESSION['isLoggedIn']) && ($_SESSION['isLoggedIn'] == true)) {
                echo('
                <li>
                  <a class="dropdown-toggle" id="menu1" data-toggle="dropdown" style="cursor: pointer;">'.$_SESSION['name'].'</a>
                  <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                    <li role="presentation"><a role="menuitem" href="profile.php?section=edit">Edit Profile</a></li>
                    <li role="presentation"><a role="menuitem" href="includes/signout.php">Logout</a></li>
                    <li role="presentation"><a role="menuitem" href="#">JavaScript</a></li>
                    <li role="presentation" class="divider"></li>
                    <li role="presentation"><a role="menuitem" href="#">About Us</a></li>
                  </ul>
                </li> ');
              } else {
                echo('<li><a data-toggle="modal" data-target="#SignUp" style="cursor: pointer;">SignUp</a></li>
                <li><a data-toggle="modal" data-target="#Login" style="cursor: pointer;">Login</a></li>');
              }
              
            ?>
        </ul>
    </div>
</div>
</nav>
<br/><br/><br/>

<?php
        if(isset($_SESSION['error'])) {
            echo('<div class="alert alert-danger alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Error!</strong> '.$_SESSION['error'].'
                </div>');
            unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])) {
            echo('<div class="alert alert-success alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success!</strong> '.$_SESSION['success'].'
                </div>');
            unset($_SESSION['success']);
        }
    ?>
<div id="Login" class="modal fade" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title text-center">LOGIN</h4>
  </div>
  <div class="modal-body">
    <center>
      <a href="<?php echo $loginUrl; ?>" target="_self">
        <button class="loginBtn loginBtn--facebook" id="myelement">
          Login with Facebook
        </button>
      </a>
      <a href="<?php echo $login_url; ?>" target="_self">
        <button class="loginBtn loginBtn--google">
          Login with Google
        </button>
      </a><br/><br/>

      OR <br/><br>

      <form role="form" id="loginForm" method="post" action="includes/login.php">
        <hr class="colorgraph">
          <div class="form-group has-feedback">
            <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" tabindex="4">
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" tabindex="4">
          </div>
          <hr class="colorgraph">
          <div class="row">
            <div class="col-xs-12 col-md-12">
              <input type="submit" value="Login" class="btn btn-primary btn-block btn-lg" tabindex="7" name="loginBtn">
            </div>
          </div>
      </form>
    </center>
  </div>
</div>
</div>
</div>
<div id="SignUp" class="modal fade" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title text-center">SIGNUP</h4>
  </div>
  <div class="modal-body">
    <center>
      <a href="<?php echo $loginUrl; ?>" target="_self">
        <button class="loginBtn loginBtn--facebook" id="myelement">
          Signup with Facebook
        </button>
      </a>
      <a href="<?php echo $login_url; ?>" target="_self">
        <button class="loginBtn loginBtn--google">
          Signup with Google
        </button>
      </a><br/><br/>

      OR  <br/><br/>

      <form role="form" id="registration" action="includes/signup.php" method="post">
        <hr class="colorgraph">
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="form-group has-feedback">
                <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" tabindex="1">
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="form-group has-feedback">
              <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" tabindex="2">
            </div>
          </div>
        </div>
        <div class="form-group has-feedback">
          <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" tabindex="4">
        </div>
        <div class="form-group has-feedback">
          <input type="date" name="dob" id="dob" class="form-control" placeholder="Date Of Birth" tabindex="3">
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="form-group has-feedback">
              <input type="password" name="password" id="regPassword" class="form-control" placeholder="Password" tabindex="5">
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="form-group has-feedback">
              <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" tabindex="6">
            </div>
          </div>
        </div>
        <hr class="colorgraph">
        <div class="row">
          <div class="col-xs-12 col-md-12"><input type="submit" value="Register" class="btn btn-primary btn-block btn-lg" tabindex="7" name="register"></div>
        </div>
      </form>
    </center>
  </div>
</div>

</div>
</div>
<br/><br/>