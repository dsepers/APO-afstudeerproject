<?php
$searchstring = "SELECT * FROM Images";
// list of all possible fields
$conditions = array();
//if field is set and not empty
if (isset($_REQUEST['filename']) && $_REQUEST['filename'] != '') {
  $conditions[] = "Path LIKE '%" . mysql_real_escape_string($_REQUEST['filename']) . "%'";
}
if (isset($_REQUEST['filter']) && $_REQUEST['filter'] != '') {
  $conditions[] = "Filter LIKE '%" . mysql_real_escape_string($_REQUEST['filter']) . "%'";
}
if (isset($_REQUEST['exposurelow']) && isset($_REQUEST['exposurehigh']) && $_REQUEST['exposurelow'] < $_REQUEST['exposurehigh']) {
  $conditions[] = "Exposure_time BETWEEN '" . $_REQUEST['exposurelow'] . "' AND '" . $_REQUEST['exposurehigh'] . "'";
}
if (isset($_REQUEST['RA---TANlow']) && isset($_REQUEST['RA---TANhigh']) && $_REQUEST['RA---TANlow'] < $_REQUEST['RA---TANhigh']) {
  $conditions[] = "RATAN BETWEEN '" . $_REQUEST['RA---TANlow'] . "' AND '" . $_REQUEST['RA---TANhigh'] . "'";
}
if (isset($_REQUEST['DEC---TANlow']) && isset($_REQUEST['DEC---TANhigh']) && $_REQUEST['DEC---TANlow'] < $_REQUEST['DEC---TANhigh']) {
  $conditions[] = "DECTAN BETWEEN '" . $_REQUEST['DEC---TANlow'] . "' AND '" . $_REQUEST['DEC---TANhigh'] . "'";
}
if (isset($_REQUEST['date']) && $_REQUEST['date'] != '') {
  $conditions[] = "DATE_FORMAT(Date,\"%Y-%m-%d\") = '" . $_REQUEST['date'] . "'";
}

// build query with these parameters
if (count($conditions) > 0) {
  $searchstring .= " WHERE " . implode (' AND ',$conditions);
}
echo $searchstring;

$con=mysqli_connect("localhost","apouser","apo3141","apo");
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysql_connect_error();
  }

$result = mysqli_query($con,$searchstring);

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