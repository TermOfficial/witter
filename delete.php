<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $stmt = $conn->prepare("SELECT * FROM weets WHERE author = ? AND realid = ?");
    $stmt->bind_param("ss", $_SESSION['siteusername'], $_GET['rid']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0) {
        $_SESSION['errorMsg'] = ('You do not own this weet');
        goto skip;
    }
    while($row = $result->fetch_assoc()) {
        deleteWeet($_GET['rid'], $conn);
    }
    $stmt->close();

    skip:
    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>