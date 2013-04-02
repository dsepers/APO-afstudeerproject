<?php
$con=mysqli_connect("localhost","apouser","apo3141","apo");
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysql_connect_error();
  }

$result = mysqli_query($con,"SELECT * FROM Images");

echo "<table border='1'>
<tr>
<th>Size X</th>
<th>Size Y</th>
<th>Exposure time</th>
<th>Filter</th>
<th>Path</th>
<th>Date</th>
<th>RA---TAN</th>
<th>DEC---TAN</th>
</tr>";

while($row = mysqli_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['Size_X'] . "</td>";
  echo "<td>" . $row['Size_Y'] . "</td>";
  echo "<td>" . $row['Exposure_time'] . "</td>";
  echo "<td>" . $row['Filter'] . "</td>";
  echo "<td>" . $row['Path'] . "</td>";
  echo "<td>" . $row['Date'] . "</td>";
  echo "<td>" . $row['RATAN'] . "</td>";
  echo "<td>" . $row['DECTAN'] . "</td>";
  echo "</tr>";
  }
  
echo "</table>";
echo "<a href=\"index.html\">back to index</a>";

mysqli_close($con);
?>