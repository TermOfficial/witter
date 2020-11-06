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
    <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php");
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(!isset($_SESSION['siteusername'])){ $error = "you are not logged in"; goto skipcomment; }
        if(!$_POST['comment']){ $error = "your comment cannot be blank"; goto skipcomment; }
        if(strlen($_POST['comment']) > 500){ $error = "your comment must be shorter than 500 characters"; goto skipcomment; }
        if(!isset($_POST['g-recaptcha-response'])){ $error = "captcha validation failed"; goto skipcomment; }
        if(!validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skipcomment; }

        $stmt = $conn->prepare("SELECT * FROM weets WHERE author = ? AND realid = ?");
        $stmt->bind_param("si", $_SESSION['siteusername'], $_GET['rid']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows === 0) {
            $error = ('you dont own this blog post');
            goto skipcomment;
        }
        $stmt->close();

        $stmt = $conn->prepare("UPDATE weets SET contents = ? WHERE realid = ?");
        $stmt->bind_param("ss", $text, $_GET['rid']);
        $text = htmlspecialchars($_POST['comment']);
        $stmt->execute();
        $stmt->close();

        header("Refresh: 0");

        skipcomment:
    }
    ?>
        <div id="content">
            <?php if(!isset($_SESSION['siteusername'])) { ?>
                <div style="background-color: lightyellow;" class="wrapper">
                    <big><big><big>Hey there! You arent logged in!</big></big></big><br>
                    <img style="float: left; margin-right: 5px;" src="/static/girl.gif">Witter is a free service that lets you keep in touch with people through the exchange of quick, frequent answers to one simple question: What are you doing? Log in or register to post.
                </div><br><br><br><br><br><br>
            <?php } ?>
            <div class="wrapper">
            <center>
                <img style="vertical-align: middle;" src="/static/girl.gif">
                <big><big><big><b>404</b></big></big></big> That's an error.<br>
                The requested URL was not found on this server.<br>
                <span style="color: gray;">That's all we know.</span>
            </center>
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
            </div>
        </div>
    </div>
</body>
</html>