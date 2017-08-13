<!DOCTYPE html>
<html>
<head>
    <title>Retail Billing</title>
</head>

<style type="text/css">
    body {
        background-color: white;
    }
th {
    
    color: white;
    padding: 12px 20px;
        background-color: #4CAF50; /* Green */
}
td {
    border : 1px solid black;
    padding: 12px 20px;
    margin: 8px 8px;
    box-sizing: border-box;

}
input[type=number] {
    width: 30%;
    padding: 4px 4px;
    margin: 2px 2px;
    box-sizing: border-box;
    font-size: 16px;
}
input[type=submit] {
    background-color: white; /* Green */
    border: none;
    color: #4CAF50;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
}
select {
    width: 100%;
    padding: 6px 2px;
    border: none;
    border-radius: 4px;
    background-color:#4CAF50;
    text-align: center;
    color: #f1f1f1;
    font-size: 16px;
}
select:hover {
background-color:#4CAF50;
color: #f1f1f1;
}

table {
    display: table;
    border-collapse: separate;
    border-spacing: 2px;
    border-color: gray;
}
</style>



<body>

    <?php


    include 'dbconnect.php';

    $dbconnect = new mysqli($servername, $username, $password, $dbname);
// Check connection
    if ($dbconnect->connect_error) {
        die("database Connection failed: " . $dbconnect->connect_error);
    }


 

    $sql = "SELECT billNo FROM amount";
    $result = $dbconnect->query($sql);

    if ($result->num_rows > 0) {
    // Generating billNo from Datanase
        while($row = $result->fetch_assoc()) {
            $billNo=$row['billNo']+1;
        }
    }
    // Inititalizing billNo at if there is no exisiting billNo
    else { $billNo='1'; }




//Getting item list

    $sql = "SELECT sno ,item FROM pricelist";
    $result = $dbconnect->query($sql);

    if ($result->num_rows > 0) {
    // Generating billNo from Datanase
        while($row = $result->fetch_assoc()) {
            $totalItems=$row['sno'];
            $availableItems[]=$row['item'];
            
        }
    }
    

    echo "<table  width='100%'>

    <tr>
        <td width='100%' colspan='5'>Username : Sarath kumar S</td>
    </tr>
    <tr>
        <td width='100%' colspan='5'>Bill no:".$billNo."</td>
    </tr>
    <tr>
        <th>S.no</th>
        <th width='30%'>Item</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Total</th>
        
    </tr>
    ";


//Checking that the form has sumbitted or not

    if($_POST['submit']){

//Getting Post variables on submit
    $item=$_POST['item'];
    $qty=$_POST['qty'];



    $sql = "SELECT price FROM pricelist where item='$item'";
    $result = $dbconnect->query($sql);

    if ($result->num_rows > 0) {
    // Getting price list  of item
        while($row = $result->fetch_assoc()) {
            $itemPrice=$row['price'];
        }

    }
    





   


// Creating table for each bill no
        $sql = "CREATE TABLE IF NOT EXISTS table_$billNo(
        sno INT(6) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        item VARCHAR(10) NOT NULL,
        qty INT(10) NOT NULL,
        price INT(10),
        total INT(10)
        )";

        if ($dbconnect->query($sql) === TRUE) {


//Calculating  each items total price           
            $itemPrice;
            $itemCountPrice=( $itemPrice * $qty);

//Inserting each row items into table 

echo $price;
            $sql = "INSERT INTO table_$billNo(item , qty, price , total) VALUES ('$item','$qty','$itemPrice','$itemCountPrice')";
            if ($dbconnect->query($sql) === FALSE) {
                       
                 die("Adding new items failed: " . $dbconnect->error);
            }

        } 



//Display added items

        $sql = "SELECT sno, item , qty, price, total FROM table_$billNo";
        $result = $dbconnect->query($sql);

        if ($result->num_rows > 0) {
    
            while($row = $result->fetch_assoc()) {

                echo "<tr>";
                
                echo "<td>".$row['sno']."</td>";
                echo "<td>".$row['item']."</td>";
                echo "<td>".$row['qty']."</td>";
                echo "<td>".$row['price']."</td>";
                echo "<td>".$row['total']."</td>";
                

                echo "</tr>";

            }
        }

//Display  Grand Total amount for the bill

        $sql = "SELECT sum(total) FROM table_$billNo";
        $result = $dbconnect->query($sql);

        if ($result->num_rows > 0) {
    
            while($row = $result->fetch_assoc()) {
                $sumtotal=$row['sum(total)'];


            }


    }



        }

//Check printing called

        if($_GET['print']){

            $printAmount=$_GET['print'];
           
//Insert into transaction table ( bill vs amount )
            $sql = "INSERT INTO amount(amount)VALUES('$printAmount')";
            if ($dbconnect->query($sql) === TRUE) {

//Redirect to home page on printing current bill
                
                header("Location: http://localhost/index.php");
            }
            else {
                die("Adding bill to master database failed : " . $dbconnect->error);
            }



            
        }



//Close the Database connection

        $dbconnect->close();


    ?>



    <tr>
        <form action="index.php" method="post">
            
            <td></td>
            <td><select name='item'>
<<?php 
echo "<option>Select</option>";
for ($i=0;$i<$totalItems;$i++){

    echo "<option>".$availableItems[$i]."</option>";
}

?>
  </select></td>
            <td><input type="number" name="qty" min="1"> </td>
            <td></td>
            <td><input type="submit" style="display: none" name='submit'/></td>
            

        </form>

    
    </tr>


    <tr>
    
        <td colspan="5">Discount : </td>
    
    </tr>
    
    <tr>
    
        <td width="100%" colspan="5">Total : <?php echo $sumtotal; ?> </td>
    
    </tr>

    <form action="index.php?print=<?php echo $sumtotal; ?>" method='post'>


        <tr>

            <th width="100%" colspan="5"><input type="submit" name="print" value="Print"></th>
        </tr>


    </form>
</table>



</body>
</html>




