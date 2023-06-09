<?php
  include 'connection.php';
  $connect = connectDB();

  $permit_number = $_POST['permit_number'];
  $lat="";
  $lng="";

  $typeString = $_POST['type'];
  $types = explode(",",$typeString);

  if(isset($_POST['coordinates']) && $_POST['coordinates'] != "unchecked"){
    $coordinates = explode(":",$_POST['coordinates']);
    $lat = $coordinates[0];
    $lng = $coordinates[1];
  }

  if($connect){
    $query= "UPDATE establishment SET name=\"".$_POST["name"]."\", active=".$_POST["state"]." WHERE permit_number=".$permit_number.";";
    if($result = $connect->query($query)){
      $query= "SELECT fk_id_pstl_adr FROM establishment WHERE permit_number=".$permit_number.";";
      if($result = $connect->query($query)){
        if($result->num_rows > 0){
          $row = $result->fetch_assoc();
          $fk_id_pstl_adr = $row["fk_id_pstl_adr"];
          $query ="";
          if(isset($_POST['coordinates']) && $_POST['coordinates'] != "unchecked"){
            $query= "UPDATE pstl_adr SET strt_nm=\"".$_POST["strt_nm"]."\", bldg_nm=\"".$_POST["bldgNb"]."\", twn_nm=\"".$_POST["twnNm"]."\", ctry=\"".$_POST["ctry"]."\", pstl_code_number=\"".$_POST["zip"]."\", latitude=".$lat.", longitude=".$lng."  WHERE id_pstl_adr=".$fk_id_pstl_adr.";";
          }
          else{
            $query= "UPDATE pstl_adr SET strt_nm=\"".$_POST["strt_nm"]."\", bldg_nm=\"".$_POST["bldgNb"]."\", twn_nm=\"".$_POST["twnNm"]."\", ctry=\"".$_POST["ctry"]."\", pstl_code_number=\"".$_POST["zip"]."\", latitude=null, longitude=null WHERE id_pstl_adr=".$fk_id_pstl_adr.";";
          }

          if($connect->query($query)){

            $query= "DELETE FROM establishment_type WHERE fk_permit_number = ".$permit_number.";";
            if($connect->query($query)){

              $query = "INSERT INTO establishment_type(`fk_permit_number`,`fk_establishment_type`) VALUES";
              for($i=0;$i<count($types);$i++){

               // $query = $query. " (".$permit_number.",\"4\");";  
				$query = $query. " (".$permit_number.",\"".$types[$i]."\");"; 
				//$query = $query. " (".$permit_number.",\"".$typeString."\");"; 

                if($i+1 != count($types)){
                  $query = $query. ",";
                }
                else{
                  $query = $query. ";";
                }
				
              }
              if($connect->query($query)){
                header("location: ../establishment.php");
              }
              else{
                header("location: ../establishment.php?error=".$connect->error);
              }
            }
            else{
              header("location: ../establishment.php?error=".$connect->error);
            }
          }
          else{
            header("location: ../establishment.php?error=".$connect->error);
          }
        }
        else{
          header("location: ../establishment.php?error=".$connect->error);
        }
      }
      else{
        header("location: ../establishment.php?error=".$connect->error);
      }
    }
    else{
      header("location: ../establishment.php?error=".$connect->error);
    }
  }
  else{
    header("location: ../establishment.php?error=".$connect->error);
  }
?>
