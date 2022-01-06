<?php
	echo '<p> Hello World! </p>';
	$queryString = 'SELECT * FROM pessoa';
	$servername = "localhost";
	$username = "root";
	$password = "";
	$db = "e02";
	$conn = mysqli_connect($servername, $username, $password, $db);
	$queryResult = mysqli_query($conn, $queryString);
	
	if (mysqli_num_rows($queryResult) > 0) {
	  // output data of each row
	  while($row = mysqli_fetch_array($queryResult,MYSQLI_NUM)) {
		echo '<p> id: '.$row[0].' - name: '.$row[4].' '.$row[5].'</p>';
	  }
	} else {
	  echo "<p>0 results</p>";
	}
?>