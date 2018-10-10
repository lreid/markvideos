<html>



<body>

<?php
//connect to database
  include 'connecttodb.php';
  $beingAssessedStudent =$_POST["peereval"];
  $valueGiven=$_POST["peerr"];
  $doingAssessingStudent = $_POST["doingUserId"];
  $query = "SELECT * FROM peerassessment WHERE assessor='".$doingAssessingStudent . "' AND gettingassess ='". $beingAssessedStudent ."';";
  $result = mysqli_query($connection,$query);
  if (!$result) {
    die ("Error: while checking if peer assessment exists");
  }
  if (mysqli_num_rows($result) > 0){
     $queryset = "UPDATE peerassessment SET peerassessmark = " . $valueGiven . " WHERE assessor='".$doingAssessingStudent . "' AND gettingassess ='". $beingAssessedStudent ."';";
     
  } else {
     $queryset = "INSERT INTO peerassessment VALUES ('". $doingAssessingStudent . "','" . $beingAssessedStudent . "',". $valueGiven . ");";
  }
  $result = mysqli_query($connection, $queryset);
  if (!$result) {
    die ("Error: while updating peer assessment");
  }
  echo "<h2>Peer evaluation updated! - Go back a page to update your other group members</h2>";
?>


</body>
</html>
