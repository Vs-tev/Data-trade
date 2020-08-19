<?php
$first_letter = escape($user->data()->username[0]); 
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!--Roboto without blur -->
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:light,regular,medium,thin,italic,mediumitalic,bold" title="roboto">
    <link rel="stylesheet" href="/loginregister.oop/navbar/navbar_top.css">

</head>

<body>
    <nav class="navbar main-navbar navbar-expand-md sticky-top">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse">

            <i class="material-icons toggle-navbar-i">menu</i>

        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                  
                <li class="nav-item">
                    <a href="<?php echo $link ?>" class="nav-link"><?php echo $analysis_alltrades ?></a>
                </li>
                <?php  echo @$li ?>
                <li class="nav-item">
                    <a href="/loginregister.oop/main_one/mainpage-traderecord.php" class="nav-link">Recording Trade</a>
                </li>
            </ul>
            <ul class="navbar-nav navbar-right">
               <li class="nav-item">
                    <a href="#" class="nav-link"><i class="material-icons home_i">home</i> Home</a>
                </li>
                <li class="li_logout">
                    <a href="/loginregister.oop/logout.php" class="nav-link logout "><i class="material-icons logout_i">power_settings_new</i>Logout</a>
                </li>
            </ul>
        </div>
    </nav>
</body>
</html>