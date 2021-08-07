<?php
   include('etc/config.php');
   session_start();
   
   $user_check = $_SESSION['nomor_induk'];
   
   $ses_sql = mysqli_query($mysqli,"select nomor_induk from pengguna where nomor_induk = '$user_check' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_session = $row['nomor_induk'];
   
   if(!isset($_SESSION['login'])){
      header("location:login.php");
      die();
   }
?>