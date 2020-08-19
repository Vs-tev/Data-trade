<?php
require_once 'init.php';
$title = "Data-trade Log in"; 
$style= "/loginregister.oop/singin-login.css";
 
//include 'google api'; 
require_once('api/config.php');

if(Input::exists()) { 
   if(Token::check(Input::get('token'))){
       $validate = new Validate();
       $validation = $validate->check($_POST, array(
           'username' => array('required' =>true),
           'password' => array('required' => true),
       ));
       if($validation->passed()) {
           $user = new User();
           
          $remember = (Input::get('remember') === 'on') ? true : false;
           $login = $user->login(Input::get('username'), Input::get('password'), $remember);
           
           if($login) {
               Session::put($user->_sessionPortfolio, '');
             Redirect::to('main_one/mainpage-traderecord.php');
           }else {
               $noUser = '<p>Invalid username or password! Please ty again.</p>';
           }
           
       }else {
           foreach($validation->errors() as $error) {
              // echo $error, '<br>';
           }
       }
   }
}

if(isset($_SESSION["access_token"])){
        Redirect::to('main_one/mainpage-traderecord.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="Trade data recording" content="Record and analyse your trades with many differents measure tools">
    <?php include 'head.php' ?>
</head>

<body>
    <nav class="navbar navbar-expand-sm">
        <a class="navbar-brand" href="#">.Data-trade</a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="link-item">Home</a></li>
        </ul>
    </nav>

    <div class="container col-12 col-md-6 col-lg-4 col-xl-3 form-group login-container">
        <h4>Log in to Your Account</h4>
         <div class="row p-0 m-0">
        <a class="google-btn d-flex align-items-center col-6" href="<?php echo $google_client->createAuthUrl() ?>"><img class="" src="google/signin-assets/web/icons8-google-48.png"><span class="flex-grow-1 text-center">With Google</span></a>
        <div class="divAfterGoogleBtn m-auto"><span>OR</span></div>    
        </div>
        <div class="d-flex justify-content-center align-self-center separator">
                <span class="create-account-label">With your Email</span>
        </div>
        <form id="login-form" class="form-group" method="POST">
            <div class="styledInputs mb-0 pb-0">
                <label for="inp" class="inp">
                    <input type="text" class="form-control" placeholder="&nbsp;" name="username" id="user_n">
                    <span class="label">Email</span>
                    <span class="border"></span>
                </label>
            </div>
            <div style="height:10px" class=""><span class="error" id="error_u_empty"></span></div>

            <div class="styledInputs mb-0 pb-0">
                <label for="inp" class="inp">
                    <input type="password" class="form-control" placeholder="&nbsp;" name="password" id="passw">
                    <span class="label">Password</span>
                    <span class="border"></span>
                </label>
            </div>
            <div style="height:10px" class=""><span class="error" id="error_p_empty"></span></div>

            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="customCheck" name="remember">
                <label class="custom-control-label" for="customCheck">Remember me</label>
            </div>

            <input type="submit" class="btn btn-primary btn-block mt-2" name="submit" id="log-in-submit" value="Log In">
            <a href="#" class="m-auto"><small>Forgot password?</small></a>
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

            <div style="height:10px; text-align:center" class=""><span class="error" id="error_u_empty"></span><?php  echo @$noUser; ?></div>

        </form>
        <label class="need-account mt-3">Need an Account? <a href="register.php" class="ml-1"> Sing up</a></label>

    </div>
</body>

</html>

<script>
    $(function() {

        $("#error_u_empty").hide();
        $("#error_p_empty").hide();


        var error_u_empty = false;
        var error_p_empty = false;

        $("#user_n").focusout(function() {
            check_user();
        });

        $("#passw").focusout(function() {
            check_passw();
        });

        function check_user() {
            var username_length = $("#user_n").val().length;


            if ((username_length < 3) || (username_length > 20)) {
                $("#error_u_empty").html("Incorrect username! Please try again.");
                $("#error_u_empty").show();
                $("#user_n").addClass('input-error');
                error_u_empty = true;
            } else {
                $("#error_u_empty").hide();
                $("#user_n").removeClass('input-error');
            }
        }

        function check_passw() {
            var password_length = $("#passw").val().length;
            var $regexpass = /^\S*$/;
            if ((password_length < 6) || (password_length > 20) || (!$("#passw").val().match($regexpass))) {
                $("#error_p_empty").html("Incorrect password. Please try adain");
                $("#error_p_empty").show();
                $("#passw").addClass('input-error');
                error_p_empty = true;
            } else {
                $("#error_p_empty").hide();
                $("#passw").removeClass('input-error');
            }
        }

        $("#login-form").submit(function() {

            error_u_empty = false;
            error_p_empty = false;

            check_user();
            check_passw();

            if (error_u_empty == false && error_p_empty == false) {
                return true;
            } else {
                return false;
            }

        });
    });
</script>