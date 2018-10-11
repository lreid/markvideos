<html>

  <head>
    <meta charset="utf-8">

    <title>Introduction to Databases - Mark the Videos</title>
    <link rel="stylesheet" href="video.css">
    <link href="https://fonts.googleapis.com/css?family=Mali" rel="stylesheet">
    <script src="setup.js"></script>
  </head>

  <body>
    <?php
     include 'connecttodb.php';
     ?>

      <h1>Evaluate The Videos</h1>
      <form action="getStudentData.php" method="post" enctype="multipart/form-data">
        Enter Your Western UserID: <input type="text" name="westid"><br> 
        Enter the password for your video (NOT your western password): 
                <input type="password" name="mypass" onkeydown="checkKey(event)"><br>
        <input type="submit" value="Get Your Group Info">
      </form>

  </body>

</html>

