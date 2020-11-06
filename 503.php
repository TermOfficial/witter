<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <link href="/static/css/required.css" rel="stylesheet">
    <title>Witter: What are you doing?</title>
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    <?php $user = getUserFromName($_SESSION['siteusername'], $conn); ?>
    <?php $weet = getWeetFromRID($_GET['rid'], $conn); ?>
    <script>function onLogin(token){ document.getElementById('submitform').submit(); }</script>
</head>
<body id="front">
    <div id="container">
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); ?>
        <div id="content">
            <div class="wrapper">
                <center>
                    <img style="vertical-align: middle;" src="/static/girl.gif">
                    <big><big><big><b>503</b></big></big></big> That's an error.<br>
                    The service you requested is not available at this time.<br>
                    <span style="color: gray;">That's all we know.</span>
                </center>
                <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
            </div>
        </div>
    </div>
</body>
</html>c