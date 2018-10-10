<html>



<body>

<?php
//connect to database
  include 'connecttodb.php';

//get data from previous page

  $markingGroupId =$_POST["groupDoingScoring"];
  $creative=$_POST["creative"];
  $example=$_POST["examples"];
  $rel=$_POST["relevance"];
  $clear=$_POST["clearly"];
  $videonum = $_POST["whichgroup"];
  $fav = $_POST["favvideo"];

  echo $fav . "<br>";
//build query
  if ($fav == "on") {
     $fav = 1;

     $query = "UPDATE markingscheme SET favgroup=0 WHERE  markingsmallgroupid =". $markingGroupId .";";
     echo $query;
     $result = mysqli_query($connection, $query);
     if (!$result) {
       die ("Error: while fixing favourites");
     }   
  } else {
     $fav = 0;
  }
  
  $query = "SELECT * FROM markingscheme WHERE videoid=".$videonum . " AND markingsmallgroupid =". $markingGroupId .";";
  echo $query . "<br>";
  echo $rel . "<br>";
  $result = mysqli_query($connection,$query);
  if (!$result) {
    die ("Error: while checking if group mark already exists");
  }
  if (mysqli_num_rows($result) > 0){
     $queryset = "UPDATE markingscheme SET creativitymark = " . $creative . ", relevancemark=". $rel . 
        ",examplesmark=" . $example . ",claritymark=" . $clear . ",favgroup=" . $fav . 
       " WHERE videoid=".$videonum . " AND markingsmallgroupid =". $markingGroupId .";";
  } else {
     $queryset = "INSERT INTO markingscheme (creativitymark,relevancemark,examplesmark,claritymark,videoid,markingsmallgroupid,favgroup)
       VALUES (". $creative . "," . $rel . "," . $example . "," . $clear . "," . $videonum . "," .
           $markingGroupId . "," . $fav . ");"; 
   }
  echo $queryset;
  $result = mysqli_query($connection, $queryset);
  if (!$result) {
    die ("Error: while setting group mark");
  }
  echo "<h2>Group evaluation updated! - Go back a page to evaluate the other group videos</h2>";
?>


</body>
</html>
