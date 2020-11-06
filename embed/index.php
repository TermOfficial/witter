<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <link href="/static/css/required.css" rel="stylesheet">
    </head>
    <body style="background-image: none; background-color: white;">
        <table id="feed">
            <tr>
                <th style="width: 48px;">&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            <?php
            $stmt = $conn->prepare("SELECT * FROM weets WHERE realid = ?");
            $stmt->bind_param('s', $_GET['i']);
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
            <?php } ?>
        </table>
    </body>
</html>