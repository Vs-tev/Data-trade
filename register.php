<?php
require_once 'init.php';

//include 'google api'; 
require_once('api/config.php');

$title = "Register";
$style= "/loginregister.oop/singin-login.css";

//var_dump(Token::check(Input::get('token'))); // check if session is match with token
// Input::get('username'); // the same as echo $_POST['username']';
//echo Config::get('session/session_username');

if(Input::exists()) {
if(Token::check(Input::get('token'))) {
 $validate = new Validate();
    $validation = $validate->check($_POST, array(
        'username' => array(
            'required' => true,
            'min' => 3,
            'max' => 20,
            'unique' => 'user',
            'letters_numbers' => true,
        ),
        'email' => array(
            'required' => true,
            'min' => 3,
            'max' => 50,
            'valide_email' => true,
            'unique' => 'user'
        ),
        'password' => array(
            'required' => true,
            'min' => 6,
        ),
        'g-recaptcha-response' => array(
             'required' => true,
        )
    ));
    if($validation->passed()){
        $user = new User();
        try {
            $user->create(array(
            'username' => Input::get('username'),
            'email' => Input::get('email'),
            'password' => password_hash(Input::get('password'), PASSWORD_DEFAULT),
            'joined' => date('Y=m-d H:i:s'),   
            ));
            
            Session::flash('home', 'You registered Successfuly and now can log in');
            Redirect::to('login.php');
            
        } catch(Exception $e) {
            die($e->getMessage()); // or redirect to anoher page with error mesage 
        }
    }else{
       foreach($validation->errors() as $error) {
        $error = 'Username or Email already exists. Please try again.';
       }
    }
}
} 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'head.php'; ?>
</head>

<body>
  
     <nav class="navbar navbar-expand-sm">
        <a class="navbar-brand" href="#">.Data-trade</a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="link-item">Home</a></li>
            <li class="nav-item"><a href="login.php" class="">Log In</a></li>
        </ul>
    </nav>
           
    <div class="container col-12 col-md-6 col-lg-4 col-xl-3 form-group singup">
        <h4>Sing Up</h4>
        <div class="row p-0 m-0">
        <a class="google-btn d-flex align-items-center col-6" href="<?php echo $google_client->createAuthUrl() ?>"><img class="" src="google/signin-assets/web/icons8-google-48.png">
            <span class="flex-grow-1 text-center ">With Google</span></a>
        <div class="divAfterGoogleBtn m-auto"><span>OR</span></div>    
        </div>
        <div class="d-flex justify-content-center align-self-center separator">
                <span class="create-account-label">Create an Account</span>
        </div>
        <form id="first-form" class="form-group" method="POST" action="">
            <div class=" justify-content-center">
                <div class="styledInputs mb-0 pb-0">
                    <label for="inp" class="inp">
                        <input type="text" class="form-control" placeholder="&nbsp;" id="user_n" name="username" data-placement="right">
                        <span class="label username">Username</span>
                        <span class="border"></span>
                    </label>
                </div>
            </div>
            <div style="height:10px" class=""><span class="error" id="error_username"></span></div>

            <div class="styledInputs mb-0 pb-0">
                <label for="inp" class="inp">
                    <input type="text" class="form-control" placeholder="&nbsp;" id="e_mail" name="email" data-placement="right">
                    <span class="label">E-mail Adress</span>
                    <span class="border"></span>
                </label>
            </div>
            <div style="height:10px" class=""><span class="error" id="error_email"></span></div>

            <div class="styledInputs mb-0 pb-0">
                <label for="inp" class="inp">
                    <input type="password" class="form-control" placeholder="&nbsp;" id="passw" name="password" data-placement="right">
                    <span class="label">Password</span>
                    <span class="border"></span>
                </label>
            </div>
            <div style="height:10px" class=""><span class="error" id="error_password"></span></div>

             <div style="" class="exists-error my-3"><?php echo $error ?? '' ?></div>
            
            <div class="">
             <div class="g-recaptcha" data-callback="recaptchaCallback" data-sitekey="6LewfrgUAAAAAL5BpyvXPzl9Sqw7zJyXh3zmhKQd"></div>
            </div>
            <input type="submit" class="btn btn-primary btn-block mt-4" id="submit" name="submit" value="Sing Up" disabled>
            
            <div style="height:10px" class=""><span class="error" id="error_captcha"></span></div>
            <p class="error"></p>
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        </form>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    </div>

</body>

</html>

<script>
    function recaptchaCallback() {
        $('#submit').removeAttr('disabled');
    }
    $(function() {


        $("#error_username").hide();
        $("#error_email").hide();
        $("#error_password").hide();
        $("#error_captcha").hide();

        var error_username = false;
        var error_email = false;
        var error_password = false;


        $("#user_n").focusout(function() {
            check_username();
        });

        $("#e_mail").focusout(function() {
            check_email();
        });

        $("#passw").focusout(function() {
            check_password();
        });

        function check_username() {
            var username_length = $("#user_n").val().length;
            var $regexname = /^[a-zA-Z0-9\s]*$/;

            if ((username_length < 3) || (username_length > 20) || (!$("#user_n").val().match($regexname))) {
                $("#error_username").html("Username must be alphanumeric between 4-20 characters! Please try again.");
                $("#error_username").show();
                $("#user_n").addClass('input-error');
                $('#submit').attr('disabled');
                error_username = true;

            } else {
                $("#error_username").hide();
                $("#user_n").removeClass('input-error');
            }
        }

        function check_email() {
            var $regexemail = /[A-Za-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[A-Za-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?/;
            if (!$("#e_mail").val().match($regexemail)) {
                $("#error_email").html("Please insert valid email format!");
                $("#error_email").show();
                $("#e_mail").addClass('input-error');
                $('#submit').attr('disabled');
                error_email = true;
            } else {
                $("#error_email").hide();
                $("#e_mail").removeClass('input-error');
            }
        }

        function check_password() {
            var password_length = $("#passw").val().length;
            var $regexpass = /^\S*$/;
            if ((password_length < 4) || (password_length > 30) || (!$("#passw").val().match($regexpass))) {
                $("#error_password").html("The password must contain between 4-30 charachters, no space allowed.");
                $("#error_password").show();
                $("#passw").addClass('input-error');
                $('#submit').attr('disabled');
                error_email = true;
            } else {
                $("#error_password").hide();
                $("#passw").removeClass('input-error');
            }
        }
        $("#first-form").on("submit", function(event) {

            error_username = false;
            error_email = false;
            error_password = false;

            check_username();
            check_email();
            check_password();

            if (error_username == false && error_email == false && error_password == false) {
                return true;
            } else {
                return false;
            }
        });
    });
</script>