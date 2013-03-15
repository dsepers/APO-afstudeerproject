<?php
$con=mysqli_connect("localhost","apouser","apo3141","apo");
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysql_connect_error();
  }

$result = mysqli_query($con,"SELECT * FROM Images");

while($row = mysqli_fetch_array($result))
  {
  echo $row['Size_X'] . " " . $row['Size_Y'] . " " . $row['Exposure_time'] . " " . $row['Path'] . " " . $row['Date'];
  echo "<br />";
  }

mysqli_close($con);
?>