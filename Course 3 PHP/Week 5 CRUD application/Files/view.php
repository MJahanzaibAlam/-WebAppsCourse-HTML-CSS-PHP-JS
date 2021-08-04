<?php
session_start();
if ( !isset($_SESSION['email']) ){
    die("Not logged in");
}

require_once "pdo.php";
?>

<!DOCTYPE html>
<html>
<head>
<title>Mohammad Jahanzaib Alam's Automobile Tracker</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Welcome to the Automobiles Database</h1>

<?php
if ( isset($_SESSION['error']) ) {
  echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
  unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
  echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
  unset($_SESSION['success']);
}

echo('<table border="1">'."\n");

echo "<thead><tr>
<th>Make</th>
<th>Model</th>
<th>Year</th>
<th>Mileage</th>
<th>Action</th>
</tr></thead>";

$stmt = $pdo->query("SELECT make, model, year, mileage, autos_id FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($rows) > 0) {
  foreach ( $rows as $row ) {
    echo "<tr><td>";
    echo(htmlentities($row['make']));
    echo("</td><td>");
    echo(htmlentities($row['model']));
    echo("</td><td>");
    echo(htmlentities($row['year']));
    echo("</td><td>");
    echo(htmlentities($row['mileage']));
    echo("</td><td>");
    echo('<a href="edit.php?autos_id='.$row['autos_id'].'">Edit</a> / ');
    echo('<a href="delete.php?autos_id='.$row['autos_id'].'">Delete</a>');
    echo("</td></tr>\n");
  }
  echo "</table>";
} else {
  echo "<p>No rows found</p>";
}

?>

<p><a href="add.php">Add New Entry</a></p>
<p><a href="logout.php">Logout</a></p>

</div>
</body>
</html>