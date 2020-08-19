<?php
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
        header('Location: ../main_one/mainpage-traderecord.php');
            die();
    }else {
     if($_SERVER['REQUEST_METHOD']=='POST' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ){
         header('Location: ../main_one/mainpage-traderecord.php');
            die();
     }
 }

?>

<nav class="navbar nabvar_blue navbar-expand-sm bg-primary justify-content-center">

      <ul class="navbar-nav nav nav_blue" role="tablist">
          <li class="nav-item active">
              <a href="#tab-performance" class="btn btn-default" data-toggle="tab">Portfolio Performance</a>
          </li>
          <li class="nav-item">
              <a href="#tab-rules" class="btn btn-default" data-toggle="tab">Trading Rules</a>
          </li>
          <li class="nav-item">
              <a href="#strategy" class="btn btn-default" data-toggle="tab">Strategy</a>
          </li>
      </ul>
  </nav>