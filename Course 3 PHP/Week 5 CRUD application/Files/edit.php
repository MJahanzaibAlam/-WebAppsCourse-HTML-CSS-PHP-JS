<?php
session_start();
if ( ! isset($_SESSION["email"]) ) {
    die("ACCESS DENIED");
}

require_once "pdo.php";
$success = FALSE;
$failure = FALSE;

// If the user requested cancel go back to view.php
if ( isset($_POST['cancel']) ) {
    header('Location: view.php');
    return;
}

// If the user filled in car details. add to database
if ( isset($_POST['make']) ) {
    if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1
      || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
        $_SESSION['failure'] = "All fields are required";
        header('Location: edit.php?autos_id=' . $_POST['autos_id']);
        return;
    }
    else if ( is_numeric($_POST['year']) && is_numeric($_POST['mileage']) ) {
        $sql = "UPDATE autos SET make = :mk, 
                model = :md, year = :yr, mileage = :mi";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':md' => $_POST['model'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage'])
        );
        $_SESSION['success'] = "Record edited";
        header("Location: view.php");
        return;
    } else if ( !is_numeric($_POST['year'])) {
        $_SESSION['failure'] = "Year must be numeric";
        header('Location: edit.php?autos_id=' . $_POST['autos_id']);
        return;
    } else {
        $_SESSION['failure'] = "Mileage must be numeric";
        header('Location: edit.php?autos_id=' . $_POST['autos_id']);
        return;
    }
}

// Guardian: Make sure that autos_id is present
if ( ! isset($_GET['autos_id']) ) {
    $_SESSION['error'] = "Missing autos_id";
    header('Location: index.php');
    return;
  }

$stmt = $pdo->prepare("SELECT make, model, year, mileage, autos_id FROM autos WHERE autos_id = :autos_id");
$stmt->execute(array(":autos_id" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header( 'Location: index.php' ) ;
    return;
}

$mk = htmlentities($row['make']);
$md = htmlentities($row['model']);
$yr = htmlentities($row['year']);
$mi = htmlentities($row['mileage']);
$autos_id = $row['autos_id'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Mohammad Jahanzaib Alam's Automobile Tracker</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Editing Automobile</h1>

<?php
if ( isset($_SESSION['failure']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['failure'])."</p>\n");
    unset($_SESSION['failure']);
}
?>

<form method="post">
<p>Make
<input type="text" name="make" size="40" value="<?php echo $mk; ?>"/></p>
<p>Model
<input type="text" name="model" size="40" value="<?php echo $md; ?>"/></p>
<p>Year
<input type="text" name="year" size="10" value="<?php echo $yr; ?>"/></p>
<p>Mileage
<input type="text" name="mileage" size="10" value="<?php echo $mi; ?>"/></p>
<input type="hidden" name="autos_id" value="<?= $autos_id ?>">
<input type="submit" value="Save">
<input type="submit" name="cancel" value="Cancel">
</form>

</div>
</body>
</html>