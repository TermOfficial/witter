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
                <big><big><big>Editing your Weet </big></big></big> <?php if(isset($error)) { echo "<small style='color: red;'>" . $error . "</small>"; } ?> <span id="textlimit">0/500</span>
                <form method="post" enctype="multipart/form-data" id="submitform">
                    <textarea cols="32" style="width: 534px;" id="upltx" name="comment"><?php echo $weet['contents']; ?></textarea><br>
                    <script src="/js/commd.js"></script>
                    <input style="float: right; font-size: 1.2em; margin-top: 5px; margin-right: -6px;" type="submit" value="update" class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_sitekey']; ?>" data-callback="onLogin">
                    <script>
                        document.getElementById("upltx").onkeyup = () => {
                            document.getElementById("feedtext").innerHTML = document.getElementById("upltx").value.replace(/(?:\r\n|\r|\n)/g,"<br/>");
                        };
                    </script>
                </form>
                <table id="feed">
                    <tr>
                        <th style="width: 48px;">&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr>
                        <big><big><big>
                            <td>
                                <img id="pfp" src="/dynamic/pfp/<?php echo getPFPFromUser($row['author'], $conn); ?>">
                            </td>
                            <td><a id="tag" href="/u.php?n=<?php echo handleTag($row['author']); ?>"><?php echo($row['author']); ?></a>
                                <?php if(returnVerifiedFromUsername($row['author'], $conn) != "") { ?> <span style="border-radius: 10px; background-color: deepskyblue; color: white; padding: 3px;"><?php echo(returnVerifiedFromUsername($row['author'], $conn)); ?></span> <?php } ?>
                                <div id="floatRight" class="dropdown">
                                    <span><img style="vertical-align: middle;" src="/static/witter-dotdotdot.png"></span>
                                    <div class="dropdown-content">
                                        <a href="#<?php //echo report.php?r=$row['realid']; ?>"><img style="vertical-align: middle;" src="/static/witter-report.png"></a><br>
                                        <?php if(isset($_SESSION['siteusername']) && $row['author'] == $_SESSION['siteusername']) { ?>
                                            <a href="/delete.php?rid=<?php echo $row['realid']; ?>"><img style="vertical-align: middle;" src="/static/witter-trash.png"></a><br>
                                            <a href="/edit.php?rid=<?php echo $row['realid']; ?>"><img style="vertical-align: middle;" src="/static/witter-edit.png"></a><br>
                                        <?php } ?>
                                    </div>
                                </div>
                                <span id="floatRight">
                                    <?php if(ifLiked($_SESSION['siteusername'], $row['id'], $conn) == true) { ?>
                                        <a href="/unlike.php?id=<?php echo $row['id']; ?>"><img style="vertical-align: middle;" src="/static/witter-like.png"></a>
                                    <?php } else { ?>
                                        <a href="/like.php?id=<?php echo $row['id']; ?>"><img style="vertical-align: middle;" src="/static/witter-liked.png"></a>
                                    <?php } ?>
                                </span>
                                <div id="feedtext"><?php echo parseText($row['contents']); ?> </div>
                                <small id="grey">about <?php echo time_elapsed_string($row['date']); ?> from web
                                    <span id="floatRight">
                                        <?php echo getComments($row['realid'], $conn); ?><img style="vertical-align: middle;" src="/static/witter-replies.png"> &bull; <a href="/v.php?rid=<?php echo $row['realid']; ?>">Reply</a> &bull; <a href="/home.php?text=https://witter.spacemy.xyz/embed/?i=<?php echo $row['realid']; ?>">Reweet</a>
                                    </span>
                                </small><br>
                                <?php
                                $likes = getLikesReal($row['id'], $conn);
                                while($row = $likes->fetch_assoc()) {
                                    ?>
                                    <a href="/u.php?n=<?php echo handleTag($row['fromu']); ?>"><img style="width: 30px; height: 30px; margin-left: 2px;" id="pfp" src="/dynamic/pfp/<?php echo getPFPFromUser($row['fromu'], $conn); ?>"></a>&nbsp;
                                <?php } ?>
                            </td>
                        </big></big></big>
                    </tr>
                </table>
            </div>
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
        </div>
    </div>
</div>
</body>
</html>