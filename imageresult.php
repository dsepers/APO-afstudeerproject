<?php
$searchstring = "SELECT * FROM Images";
// list of all possible fields
$conditions = array();
//if field is set and not empty
if (isset($_REQUEST['object']) && $_REQUEST['object'] != '') {
  $conditions[] = "OBJECT LIKE '%" . mysql_real_escape_string($_REQUEST['object']) . "%'";
}
if (isset($_REQUEST['filter']) && $_REQUEST['filter'] != '') {
  $conditions[] = "Filter LIKE '%" . mysql_real_escape_string($_REQUEST['filter']) . "%'";
}
if (isset($_REQUEST['exposurelow']) && isset($_REQUEST['exposurehigh']) && $_REQUEST['exposurelow'] < $_REQUEST['exposurehigh']) {
  $conditions[] = "Exposure_time BETWEEN '" . $_REQUEST['exposurelow'] . "' AND '" . $_REQUEST['exposurehigh'] . "'";
}
if (isset($_REQUEST['RAlow']) && isset($_REQUEST['RAhigh']) && $_REQUEST['RAlow'] < $_REQUEST['RAhigh']) {
  $conditions[] = "RA BETWEEN '" . $_REQUEST['RAlow'] . "' AND '" . $_REQUEST['RAhigh'] . "'";
}
if (isset($_REQUEST['DEClow']) && isset($_REQUEST['DEChigh']) && $_REQUEST['DEClow'] < $_REQUEST['DEChigh']) {
  $conditions[] = "DEC BETWEEN '" . $_REQUEST['DEClow'] . "' AND '" . $_REQUEST['DEChigh'] . "'";
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
<th>RA</th>
<th>DEC</th>
<th>PA</th>
<th>Object</th>
<th>FWHM</th>
<th>X-Binning</th>
<th>Y-Binning</th>
<th>Seeing</th>
<th>Simbad link</th>
</tr>";

while($row = mysqli_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['Size_X'] . "</td>";
  echo "<td>" . $row['Size_Y'] . "</td>";
  echo "<td>" . $row['Exposure_time'] . "</td>";
  echo "<td>" . $row['Filter'] . "</td>";
  echo "<td><a href=\"downloadimage.php?file=" . $row['Path'] . "\">".$row['Path'] . "</a></td>";
  echo "<td>" . $row['Date'] . "</td>";
  echo "<td>" . $row['RA'] . "</td>";
  echo "<td>" . $row['DEC'] . "</td>";
  echo "<td>" . $row['PA'] . "</td>";
  echo "<td>" . $row['OBJECT'] . "</td>";
  echo "<td>" . $row['FWHM'] . "</td>";
  echo "<td>" . $row['XBINNING'] . "</td>";
  echo "<td>" . $row['YBINNING'] . "</td>";
  echo "<td>" . $row['SEEING'] . "</td>";
  echo "<td><a href=http://simbad.u-strasbg.fr/simbad/sim-basic?Ident=" . $row['OBJECT'] . "&submit=SIMBAD+search></td>";
  echo "</tr>";
  }
  
echo "</table>";
echo "<a href=\"index.html\">back to index</a>";

mysqli_close($con);
?>