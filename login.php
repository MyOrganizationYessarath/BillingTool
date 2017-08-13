<!DOCTYPE html>
<html>
<head>
	<title>Welcome to E-bill</title>
</head>
<body>
	<form action="authenticate.php" method="post">
		Username:
		<input type="text" name="UserName"><br><br>
		Password :
		<input type="password" name="PassWord"><br><br>
		<input type="submit" value="Submit">
	</form> 
</body>
</html>
<?php
if ($_GET['auth'] == "0"){
	echo "Error";
}
?>