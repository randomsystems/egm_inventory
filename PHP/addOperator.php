<?php
  include 'connection.php';
  $connect = connectDB();

  $lat="";
  $lng="";
  $brandString = $_POST['brandPHP'];
  $brands = explode(",",$brandString);

  if($_POST['coordinates'] != "unchecked"){
    $coordinates = explode(":",$_POST['coordinates']);
    $lat = $coordinates[0];
    $lng = $coordinates[1];
  }

  if($connect){
    echo $lat.":".$lng."</br>";
    $query="";
    if($_POST['coordinates'] != "unchecked"){
      $query="INSERT INTO pstl_adr(`strt_nm`, `bldg_nm`, `twn_nm`, `ctry`, `latitude`, `longitude`, `pstl_code_number`)";
      $query = $query." VALUES (\"".$_POST['strt_nm']."\",\"".$_POST['bldgNb']."\",\"".$_POST['twnNm']."\",\"".$_POST['ctry']."\",".$lat.",".$lng.",\"".$_POST['zip']."\");";
    }
    else{
      $query="INSERT INTO pstl_adr(`strt_nm`, `bldg_nm`, `twn_nm`, `ctry`, `pstl_code_number`)";
      $query = $query." VALUES (\"".$_POST['strt_nm']."\",\"".$_POST['bldgNb']."\",\"".$_POST['twnNm']."\",\"".$_POST['ctry']."\",\"".$_POST['zip']."\");";
    }
    if($result = $connect->query($query)){
      $last_id = $connect->insert_id;
      $query= "INSERT INTO operator(`official_license_number`, `jurisdiction`, `fk_id_pstl_adr`,`company_name`, `company_telephone`, `company_email`, `company_website`)";
      $query = $query . "VALUES(\"".$_POST["license_number"]."\",\"".$_POST["jurisdiction"]."\",".$last_id.",\"".$_POST["company_name"]."\",\"".$_POST["company_telephone"]."\",\"".$_POST["company_email"]."\",\"".$_POST["company_website"]."\");";
      if($connect->query($query)){
        $last_id = $connect->insert_id;
        $query = "INSERT INTO brand_operator(`fk_operator`,`fk_brand`) VALUES";
        for($i=0;$i<count($brands);$i++){
          $query = $query. " (".$last_id.",".$brands[$i].")";
          if($i+1 != count($brands)){
            $query = $query. ",";
          }
          else{
            $query = $query. ";";
          }
        }
        if($connect->query($query)){
          header("location: ../operator.php");
        }
        else{
          header("location: ../operator.php?error=".$connect->error);
        }
      }
      else{
        header("location: ../operator.php?error=".$connect->error);
      }
    }
    else{
      header("location: ../operator.php?error=".$connect->error);
    }
  }
  else{
    header("location: ../operator.php?error=".$connect->error);
  }

?>
