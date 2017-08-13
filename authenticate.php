<?php
if( $_POST['UserName'] || $_POST['PassWord'] ) {
  if(is_null($_POST['UserName']) == 1 && is_null($_POST['PassWord']) == 1 ){
  include 'dbconnect.php';
// Check connection
  if ($dbconnect->connect_error) {
    die("Connection failed:".$dbconnect->connect_error);
  }

  $sql = "SELECT UserName, PassWord FROM UserTable";
  $result = $dbconnect->query($sql);

  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      if (strcmp($_POST['UserName'],$row['UserName']) == '0' && strcmp($_POST['PassWord'],$row['PassWord']) == '0'){
        echo "Success";
      }else {
        echo "failed";
        $dbconnect->close();
        header("Location: http://localhost/login.php?auth=0");

      }
    }
  }
}
else echo ("Null");
}
?>