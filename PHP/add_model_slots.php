<?php
  include 'connection.php';
  $connect = connectDB();

  if($connect){
    $modelPHP = $_POST['modelSlotPHP'];
    $manufacturerPHP = $_POST['manufacturerModelSlotsPHP'];
    $models = explode(",", $modelPHP);

    foreach ($models as &$value) {
      $query = "INSERT INTO slot_model(`name_model`,`fk_id_manufacturer`) VALUES(\"".$value."\", ".$manufacturerPHP.")";
      $connect->query($query);
    }
    header("location: ../modify.php");
  }
  else{
    header("location: ../modify.php?error=".$connect->error);
  }
?>
