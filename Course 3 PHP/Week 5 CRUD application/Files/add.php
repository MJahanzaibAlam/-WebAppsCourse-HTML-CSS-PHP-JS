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
        header("Location: add.php");
        return;
    }
    else if ( is_numeric($_POST['year']) && is_numeric($_POST['mileage']) ) {
        $stmt = $pdo->prepare('INSERT INTO autos
            (make, model, year, mileage) VALUES ( :mk, :md, :yr, :mi)');
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':md' => $_POST['model'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage'])
        );
        $_SESSION['success'] = "Record added";
        header("Location: view.php");
        return;
    } else if ( !is_numeric($_POST['year'])) {
        $_SESSION['failure'] = "Year must be numeric";
        header("Location: add.php");
        return;
    } else {
        $_SESSION['failure'] = "Mileage must be numeric";
        header("Location: add.php");
        return;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Mohammad Jahanzaib Alam's Automobile Tracker</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Tracking Automobiles for <?php echo htmlentities($_SESSION['email']); ?></h1>
<?php
if ( isset($_SESSION['failure']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['failure'])."</p>\n");
    unset($_SESSION['failure']);
}
?>

<form method="post">
<p>Make:
<input type="text" name="make" size="40"/></p>
<p>Model:
<input type="text" name="model" size="40"/></p>
<p>Year:
<input type="text" name="year" size="10"/></p>
<p>Mileage:
<input type="text" name="mileage" size="10"/></p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</form>

</div>
</body>
</html>