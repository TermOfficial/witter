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
    <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php");
    $stmt = $conn->prepare("SELECT banstatus FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if($row['banstatus'] == 'suspended'){ $error = "you're suspended"; goto skipcomment; }
        if(!isset($_SESSION['siteusername'])){ $error = "you are not logged in"; goto skipcomment; }
        if(!$_POST['comment']){ $error = "your comment cannot be blank"; goto skipcomment; }
        if(strlen($_POST['comment']) > 500){ $error = "your comment must be shorter than 500 characters"; goto skipcomment; }
        if(!isset($_POST['g-recaptcha-response'])){ $error = "captcha validation failed"; goto skipcomment; }
        if(!validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skipcomment; }

        $stmt = $conn->prepare("INSERT INTO `weets` (realid, author, contents) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $uniqid, $_SESSION['siteusername'], $text);
        $uniqid = time() . uniqid();
        $text = htmlspecialchars($_POST['comment']);
        $stmt->execute();
        $stmt->close();
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
            <?php if(isset($_SESSION['siteusername'])) { ?>
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
            <?php } ?>
            <div class="customtopLeft">
                <big><big><big>What are you doing? </big></big></big> <?php if(isset($error)) { echo "<small style='color: red;'>" . $error . "</small>"; } ?> <span id="textlimit">0/500</span>
                <?php if($user['banstatus'] != "suspended" || isset($_SESSION['siteusername'])) { ?>
                    <form method="post" enctype="multipart/form-data" id="submitform">
                        <textarea cols="32" style="width: 534px;" id="upltx" name="comment"><?php if(isset($_GET['text'])) { echo $_GET['text']; } ?></textarea><br>
                        <script src="/js/commd.js"></script>
                        <input style="float: right; font-size: 1.2em; margin-top: 5px; margin-right: -6px;" type="submit" value="update" class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_sitekey']; ?>" data-callback="onLogin">
                    </form>
                <?php } else { ?>
                    <div style='padding: 5px; border: 5px solid green;'>
                        <h4 id='noMargin'>
                            You have been suspended or you are not logged in.
                        </h4>
                    </div>
                <?php } ?>
                <script src='/js/limit.js'></script><br>
                <?php if(!isset($_GET['page'])) { ?>
                    <table id="cols">
                        <tr>
                            <th style="width: 33%;">&nbsp;</th>
                            <th style="width: 33%;">&nbsp;</th>
                            <th style="width: 33%;">&nbsp;</th>
                        </tr>
                        <tr style="vertical-align: top;">
                            <td id="cBorder" <?php if(!isset($_GET['t'])) { ?> style="background-color: #F8F8F8; border-bottom: 1px dashed #333;" <?php } ?>><b><a id="blacklink" href="?t=latest">Latest</a></b></td>
                            <td id="cBorder" <?php if(isset($_GET['t']) && $_GET['t'] == "popular") { ?> style="background-color: #F8F8F8; border-bottom: 1px dashed #333;" <?php } ?>><b><a id="blacklink" href="?t=popular">Most Popular</a></b></td>
                            <td id="cBorder" <?php if(isset($_GET['t']) && $_GET['t'] == "oldest") { ?> style="background-color: #F8F8F8; border-bottom: 1px dashed #333;" <?php } ?>><b><a id="blacklink" href="?t=oldest">Oldest</a></b></td>
                        </tr>
                    </table><br>
                <?php } ?>
                <table id="feed">
                    <tr>
                        <th style="width: 48px;">&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                    <?php
                    $total_pages = $conn->query('SELECT COUNT(*) FROM weets')->fetch_row()[0];
                    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                    $num_results_on_page = 16;

                    $stmt = $conn->prepare("SELECT * FROM weets ORDER BY id DESC LIMIT ?,?");
                    $calc_page = ($page - 1) * $num_results_on_page;
                    $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while($row = $result->fetch_assoc()) { ?>
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
                                                <a href="#" onclick="unlike(<?=$row['id']?>)" id="like-toggle-<?=$row['id']?>"><img style="vertical-align: middle;" src="/static/witter-liked.png"></a>
                                            <?php } else { ?>
                                                <a href="#" onclick="like(<?=$row['id']?>)" id="like-toggle-<?=$row['id']?>"><img style="vertical-align: middle;" src="/static/witter-like.png"></a>
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
                        <?php
                    }
                    ?>
                </table>
                <center>
                    <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page-1 ?>">Prev</a>
                        <?php endif; ?>

                        <?php if ($page > 3): ?>
                            <a href="?page=1">1</a>
                            ...
                        <?php endif; ?>

                        <?php if ($page-2 > 0): ?><a href="?page=<?php echo $page-2 ?>"><?php echo $page-2 ?></a><?php endif; ?>
                        <?php if ($page-1 > 0): ?><a href="?page=<?php echo $page-1 ?>"><?php echo $page-1 ?></a><?php endif; ?>

                        <a href="?page=<?php echo $page ?>"><?php echo $page ?></a>

                        <?php if ($page+1 < ceil($total_pages / $num_results_on_page)+1): ?><a href="?page=<?php echo $page+1 ?>"><?php echo $page+1 ?></a></li><?php endif; ?>
                        <?php if ($page+2 < ceil($total_pages / $num_results_on_page)+1): ?><a href="?page=<?php echo $page+2 ?>"><?php echo $page+2 ?></a></li><?php endif; ?>

                        <?php if ($page < ceil($total_pages / $num_results_on_page)-2): ?>
                            ...
                            <a href="?page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                        <?php endif; ?>

                        <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                            <a href="?page=<?php echo $page+1 ?>">Next</a>
                        <?php endif; ?>
                    <?php endif; ?>
            </div>
            </center>
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
        </div>
    </div>
</div>
</body>
</html>
