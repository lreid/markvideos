<?php
$connection="";

function openDatabase() {
	global $connection;

	$dbhost = "localhost";
	$dbuser= "root";
	$dbpass = "XXXX";
	$dbname = "markingvideo";
	$connection = mysqli_connect($dbhost, $dbuser,$dbpass,$dbname);
	if (mysqli_connect_errno()) {
	     die("database connection failed :" .
	     mysqli_connect_error() .
	     "(" . mysqli_connect_errno() . ")"
	         );
	    }	
}

function getTheStudent() {
	
}


//load up the marking group of videos
function loadBigGroups() {
        global $connection;
	$query = "Select biggroupid from biggroup";
	$result=mysqli_query($connection,$query);
	if (!$result) {
		 die ("Database query failed");
	}
	while ($row = mysqli_fetch_assoc($result)) {
		echo '<option value="' . $row['biggroupid']. '">Group '. $row["biggroupid"].'</option>';
	}
	mysqli_free_result($result);
}

function getStudentInfo() {


}

openDatabase();


?>
