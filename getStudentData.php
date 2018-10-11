<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
  <title>Mark Group</title>
  <link rel="stylesheet" href="video.css">
</head>

<body>
<?php
//connect to database
  include 'connecttodb.php';
  $whichStudent=$_POST["westid"];
  $studentPassword=$_POST["mypass"];
  $studentPassword="pass";//just for testing!

//check password
  $query= "Select * from student where userid = '". $whichStudent . "' AND password='" .$studentPassword."';";
  $result = mysqli_query($connection,$query);
  if (!$result) {
        die("Database query failed");
  }

//get the groups that this group is supposed to mark
  $row = mysqli_fetch_assoc($result);
  $whichUser = $row["userid"];
  $smallGroup = $row["smallgroupid"];
  $query="SELEcT biggroupid FROM smallgroup where smallgroupid ='" . $smallGroup ."';";
  $result = mysqli_query($connection,$query);
  if (!$result) {
        die("Database query failed");
  }
  $row1=mysqli_fetch_assoc($result);
  $whichBigGroup = $row1["biggroupid"];
  $query="SELEcT smallgroupid FROM smallgroup where biggroupid ='" . $whichBigGroup ."';";
  $result = mysqli_query($connection,$query);
  if (!$result) {
        die("Database query failed");
  }

//get the peers for this student to do peer marking
  $query = "SELECT * FROM student WHERE smallgroupid = " . $smallGroup . ";";
  $result2 = mysqli_query($connection,$query);
  echo $query;
  if (!$result2) {
        die("Database query failed");
  }

//get information to figure out average marks
  $query = "SELECT AVG(creativitymark) as 'avgmark'  FROM markingscheme WHERE videoid = " . $smallGroup . ";";
  $resultfinal = mysqli_query($connection, $query);
  if (!$resultfinal) {
        die("Database query failed");
  }
  $rowavg = mysqli_fetch_assoc($resultfinal);
  $avgcreative = $rowavg["avgmark"];
  $query = "SELECT AVG(relevancemark) as 'avgmark'  FROM markingscheme WHERE videoid = " . $smallGroup . ";";
  $resultfinal = mysqli_query($connection, $query);
  if (!$resultfinal) {
        die("Database query failed");
  }
  $rowavg = mysqli_fetch_assoc($resultfinal);
  $avgrel= $rowavg["avgmark"]; 
  $query = "SELECT AVG(examplesmark) as 'avgmark'  FROM markingscheme WHERE videoid = " . $smallGroup . ";";
  $resultfinal = mysqli_query($connection, $query);
  if (!$resultfinal) {
        die("Database query failed");
  }
  $rowavg = mysqli_fetch_assoc($resultfinal);
  $avgexample = $rowavg["avgmark"];
  $query = "SELECT AVG(claritymark) as 'avgmark'  FROM markingscheme WHERE videoid = " . $smallGroup . ";";
  $resultfinal = mysqli_query($connection, $query);
  if (!$resultfinal) {
        die("Database query failed");
  }
  $rowavg = mysqli_fetch_assoc($resultfinal);
  $avgclear = $rowavg["avgmark"];
  $finalaverage = ($avgcreative + $avgrel + avgexample + $avgclear)/4;

//find fav video
  $query = "SELECT videoid FROM markingscheme WHERE favgroup = 1 AND markingsmallgroupid = " . $smallGroup . ";";
  $resultfinal = mysqli_query($connection, $query);
  if (!$resultfinal) {
        die("Database query failed");
  }
  $rowfav = mysqli_fetch_assoc($resultfinal);
  $favgroup = $rowfav["videoid"];
  $finalaverage = ($avgcreative + $avgrel + $avgexample + $avgclear)/4;

//check if group marked all the videos
   $query = "SELECT count(videoid) as nummarked FROM markingscheme WHERE markingsmallgroupid=". $smallGroup . ";";
  
   $markall = mysqli_query($connection, $query);
   if (!$markall) {
       die ("database failed while checking if group marked all");
   }
   $markall1=mysqli_fetch_assoc($markall);
   $groupMarked = $markall1["nummarked"];
   $query = "SELECT count(smallgroupid) as numgroups FROM smallgroup WHERE biggroupid = ". $whichBigGroup . ";";

   $markall = mysqli_query($connection, $query);
   if (!$markall) {
       die ("database failed while checking if group marked all");
   }
   $markall1=mysqli_fetch_assoc($markall);
   $numOfGroups = $markall1["numgroups"];
   echo $groupMarked . "<br>";
   echo $numOfGroups . "<br>";
   if ($groupMarked + 1 == $numOfGroups) {
     $groupDidMarking=2;
   } else if ($groupMarked > 0) {
     $groupDidMarking=1;
   } else {
     $groupDidMarking=0;
   } 

?>
      <h2>Hello 
<?php
   echo $row["firstname"]. " " . $row["lastname"] . '</h2>'; 
?>
      <h2>You are in group: <span id="groupnum">
<?php
  echo $smallGroup;
?>

</span></h2>
      You must mark all the videos before midnight of <span id="markingdue"></span><br> Do you want to: <br>
      <input type="radio" name="mark" id="video" onclick="showVideoMarking() ">Mark some videos<br>
      <input type="radio" name="mark" id="peer" onclick="showPeerEval()">Do the peer evaluation for people in groups<br>
      <input type="radio" name="mark" id="seemark" disabled>See your group's video mark (you cannot select this option until after all the other groups have marked you!) <br>
      <hr>

     </div>

<!-- Code to allow the group to mark a video -------------------------------
-->

    <div id="markvideo">
      <h3>Mark Videos</h3>
      <form action="setGroupMark.php" method="post" enctype="multipart/form-data">

      Select the group you want to mark. NOTE: Groups in red have already been marked but you can still change that mark. Groups in green have not been marked yet:

<?php
   echo '<input type="hidden" name="groupDoingScoring" value="' . $smallGroup . '">';

   echo '<select name="whichgroup" size="9" id="pickagroup"> ';
  $setFirstOne=0;
  while ($row = mysqli_fetch_assoc($result)) {


 	  echo "<option value='" . $row["smallgroupid"];

//set the url to be marked
 	  $query = "SELECT * FROM video WHERE smallgroupid = " . $row["smallgroupid"] . ";";
	  $rowv = mysqli_query($connection,$query);
	  if (!$rowv) {
        	die("Database query failed - getting URL ");
	  }
          $rowvideo = mysqli_fetch_assoc($rowv);
	  $url = 'data-url="'. $rowvideo["url"] .'" ';

//set the video topic
	  $query = "SELECT topic FROM videotopic WHERE vtopicid = " . $rowvideo["vtopicid"] .";";
	  $rowt = mysqli_query($connection,$query);
	  if (!$rowt) {
        	die("Database query failed - getting video topic");
  	  }
          $rowtopic = mysqli_fetch_assoc($rowt);
	  $topic = 'data-topic="' . $rowtopic["topic"] . '" ';

//check to see if this group has already been marked
	  $query = "SELECT * FROM markingscheme WHERE videoid = " . $rowvideo["videoid"] ." AND markingsmallgroupid = '" . $smallGroup ."';";
	  $rowm = mysqli_query($connection,$query);
	  if (!$rowm) {
        	die("Database query failed - getting video topic");
  	  }
          $rowmark = mysqli_fetch_assoc($rowm);
          if (mysqli_num_rows($rowm) > 0) {
             $hasBeenDone = ' class="alreadydone" ';
             $creative = ' data-creative="'. $rowmark["creativitymark"] . '" ';
             $rel = ' data-rel="'. $rowmark["relevancemark"] . '" ';
             $example = ' data-example="'. $rowmark["examplesmark"] . '" ';
             $clear = ' data-clear="'. $rowmark["claritymark"] . '" ';
             $fav = ' data-fav="'. $rowmark["favgroup"] . '" ';
          } else {
             $hasBeenDone = ' class="notdone" ';
             $creative = ' data-creative="" ';
             $rel = ' data-rel="" ';
             $example = ' data-example="" ';
             $clear = ' data-clear="" ';
             $fav = ' data-fav="0" ';
          }


          echo "' ";
          if ($setFirstOne < 1) {
              echo " selected ";
              $setFirstOne = 1;
          }
 	  echo  $url . " " . $topic . $hasBeenDone . $fav .  $creative . $rel . $example . $clear . ">Group " . $row["smallgroupid"] . "</option>";
  

  }
  echo "</select>";

?>
  

      <br><br>
	  Of all the videos we watched, this group's was my favourite (check for YES):  <input name="favvideo" type="checkbox" id="favouritevideo"> <br>
	  If you check this box, then any other videos you have previously selected will be UNSELECTED<br>
      <p></p>

      <h3> URL: <a href="" id="videourlhref" target="_blank">
      <span id="videourl">www.csd.uwo.ca</span></a></h3> 
      <h3>Topic: <span id="videotopic"></span></h3>

      <h3> Ranking Scale: 1=Poor, 2=OK, 3=Average, 4=Good, 5=Excellent</h3> 
      Creativity: &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
      &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <input type="radio" value="1" name="creative" id="c1">1 
      <input type="radio" name="creative" value="2" id="c2">2 
      <input type="radio" name="creative" value="3" id="c3">3
      <input type="radio" name="creative" value="4" id="c4">4
      <input type="radio" name="creative" value="5" id="c5">5<br> 
      Use of Examples: &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 
      <input type="radio" name="examples" value="1" id="e1">1 <input type="radio" name="examples" value="2" id="e2">2 
     <input type="radio" name="examples" value="3" id="e3">3
      <input
        type="radio" name="examples" value="4" id="e4">4<input type="radio" name="examples" value="5" id="e5">5<br> 
       Concepts Explained Clearly: <input type="radio" name="clearly" value="1"  id="cc1">1 
       <input type="radio" name="clearly" value="2"  id="cc2">2 
       <input type="radio" name="clearly" value="3"  id="cc3">3
       <input type="radio" name="clearly"  value="4"  id="cc4">4
        <input type="radio" name="clearly"  value="5"  id="cc5">5<br> Explanation of why the Concept is Relevant/Important: 
        <input type="radio" name="relevance"  value="1"  id="r1">1 
           <input type="radio" name="relevance" value="2"  id="r2">2 
        <input type="radio" name="relevance" value="3"  id="r3">3
        <input type="radio" name="relevance" value="4"  id="r4">4
          <input type="radio" name="relevance" value="5"  id="r5">5<br>
        <br>
      <input type="submit" value="Submit Group Evaluation" disabled="true" id="submitGroupEval">

   <iframe width="420" height="315" id="setTheVideo"></iframe>
</form>
    </div>

    

<!--  Peer Evaluation Code ---------------------------------------------------------------------
-->

    <div id="peereval">

      <h3>Peer Evaluation</h3>
      Select the person you want to evaluate:
<h3>Your Group Members:</h3>
<form action="setPeerMark.php" method="post" enctype="multipart/form-data">

 <?php
   echo '<input type="hidden" name="doingUserId" value="' . $whichUser . '">';
   echo '<select id="pickpeer" name="peereval" size="3" width="300">';

   $setFirstChoice = 0;
   while ($row = mysqli_fetch_assoc($result2)) {
      if ($row["userid"] <> $whichUser) {
          echo "<option value='" . $row["userid"] . "'";

          if ($setFirstChoice > 0)  {
             echo " selected ";
          } 

           $setFirstChoice = 1;
          echo ">" . $row["firstname"] ." " . $row["lastname"] . " ";
          $query = "SELECT * FROM peerassessment WHERE assessor = '" . $whichUser . "' AND gettingassess = '" . $row["userid"] ."';";
          $result3 = mysqli_query($connection,$query);
          if (!$result3) {
               die("Database query failed");
          } else  {
            $rowpeer=mysqli_fetch_assoc($result3);
            if (is_null($rowpeer["peerassessmark"])) {
              $peermark[$row["userid"]] = -1 ;
              echo "- No ranking yet";
            } else {
              $peermark[$row["userid"]] = $rowpeer["peerassessmark"] ;
              echo "- was previously ranked by you with a " . $peermark[$row["userid"]];
            }
          }
          echo "</option>";
      }
   }
 ?>
</select>


      <p>
        0=This student did absolutely nothing to contribute to this group- WARNING: this could cause this student to get 0 for the video<br> 
        1=This student did do a bit, but not as much as the other members<br> 
        2=This student did their fair share of the project</p>

<h2>      <span id="studentpeer"></span>: </h2>
      <h4> Give New Ranking </h4> 
      <input type="radio" value="0" name="peerr" id="peer0">0 
      <input type="radio" value="1" name="peerr" id="peer1">1 
      <input type="radio" value="2" name="peerr" id="peer2" checked="checked">2<br>
      <input type="submit" value="Enter Peer Evaluation" disabled="true" id="submitPeerEval">
      </form>

    </div>
<div id="finalmark">
    <hr>
  $finalaverage = ($avgcreative + $avgrel + avgexample + $avgclear)/4;
 <h1>These are the marks for your video</h1>
 <ul>
 <li>CREATIVITY AVERAGE: 
<?php
  echo $avgcreative;
?>
 </li>
 <li>USE OF EXAMPLES AVERAGE: 
<?php
  echo $avgexample;
?>
</li>
 <li>CLARITY AVERAGE: 
<?php
  echo $avgclear;
?>
</li>
 <li>RELEVANCE AVERAGE: 
<?php
  echo $avgrel;
?>
</li>
</ul>
 <h2>Your Groups Overall Average, out of 4, was:
<?php
  echo $finalaverage * 4 /5;
?>
</h2>
<h2> Your group's mark out of 2 for marking the other groups is: 
<?php
    echo $groupDidMarking;
?>

</h2>
 <h3>The favourite video for the group of videos that you marked was Group Number: <span id="favgroup"></span></h3>
 </ul>
<hr>
</div>

  <script src="video.js"></script>
  <script>displayPeer()
          displayVideo()
  </script>
</body>
</html>

