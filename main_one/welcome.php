<?php
    session_start();
$id = $_SESSION['user_id'];
$name = $_SESSION['u_user'];
$email =  $_SESSION['u_email'];



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home</title>
    <meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1">
    
<link href="singin-login.css" rel="stylesheet">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script>
<link href="fontawesome-free-5.3.1-web/css/all.css" rel="stylesheet">  
<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>    
    </head>
<body>
  
 <div class="container-fluid navbar"> 
            <div class="col-12">
                <div class="row justify-content-between m-2">      
                  <h4>EXPLORYE</h4>
             </div>
            </div>    
          </div>     
    
    
    
<div class="container m-auto col-lg-12">
 <h3 class="text-center"> Hello  <?php echo $name ;?></h3>
<h1 class="display-4 text-center">Welcome to Explorye...</h1>  
   
     <h6 class="mt-5 text-center">CREATE YOUR FIRST RECCORDING PORTFOLIO</h6>
       
      <form class="col-3 m-auto" action="create-first portfolio.php" method="POST">
       <div class="styledInputs mt-3">           
        <label for="inp" class="inp">
           <input type="text" class="form-control" placeholder="&nbsp;" name="account_name" required>              
           <span class="label">Account/Portfolio Name</span>
            <span class="border"></span>
        </label>
       </div>  
       
       <div class="styledInputs">           
        <label for="inp" class="inp">
           <input type="number" class="form-control" placeholder="&nbsp;" name="equity" required>              
           <span class="label">Equity</span>
            <span class="border"></span>
        </label>
       </div>  
         <select class="form-control mb-4" id="sel1" name="currency" required>
          <option>EUR</option>
          <option>USD</option>
          <option>GBP</option>
          <option>CAD</option>
          <option>JPY</option>
        </select>
   
    <button type="submit" class="btn btn-primary btn-block" name="submit">LET'S START</button>   
           
           
</form>
</div>

    
    
    
</body>
</html>    