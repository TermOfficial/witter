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
    <script>function onLogin(token){ document.getElementById('submitform').submit(); }</script>
    <script src="/js/i-have-no-idea-what-to-name-this-file-and-it-doesnt-really-matter.js"></script>
</head>
<body id="front">
<div id="container">
    <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); ?>
    <div id="content">
        <div class="wrapper">
            <div class="customtopRight">
                <img id="pfp" style="vertical-align: middle;" src="/dynamic/pfp/<?php echo $user['pfp']; ?>"> <b><big><big><?php echo $_SESSION['siteusername']; ?></big></big></b><br>
                <table id="cols">
                    <tr>
                        <th style="width: 33%;">&nbsp;</th>
                        <th style="width: 33%;">&nbsp;</th>
                        <th style="width: 33%;">&nbsp;</th>
                    </tr>
                    <tr>
                        <td><big><big><big><b><?php echo getFollowing($_SESSION['siteusername'], $conn); ?></b></big></big></big><br><span id="blue">following</span></td>
                        <td><big><big><big><b><?php echo getFollowers($_SESSION['siteusername'], $conn); ?></b></big></big></big><br><span id="blue">followers</span></td>
                        <td><big><big><big><b><?php echo getWeets(rhandleTag($_SESSION['siteusername']), $conn); ?></b></big></big></big><br><span id="blue">weets</span></td>
                    </tr>
                </table><br>
                <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/followRequire.php"); ?>
                <div class="altbg">
                    <a href="/home.php">Home</a><br>
                    <a href="/pms.php">Private Messages [200]</a>
                </div><br>
                <div class="altbg">
                    <center><a href="https://discord.gg/J5ZDsak">Join the Discord server</a></center>
                </div><br>
            </div>
            <div class="customtopLeft">
                <big><big><big>Contact</big></big></big><br>
                Contact us on these following platforms<br>
                - Discord [Link is on the right bar]<br>
                - GitHub<br><br>
                <a id="black" href="https://github.com/the-real-sumsome/witter">Our github</a>
            </div>
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
        </div>
    </div>
</div>
</body>
</html>