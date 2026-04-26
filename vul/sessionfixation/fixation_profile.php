<?php
/**
 * Created by runner.han
 * There is nothing new under the sun
 */

$PIKA_ROOT_DIR = "../../";
include_once $PIKA_ROOT_DIR . 'inc/config.inc.php';

if(!isset($_SESSION['sessionfixation']['username'])){
    header('location:fixation_login.php');
    exit();
}

$ACTIVE = array_fill(0, 150, '');
$ACTIVE[128] = 'active open';
$ACTIVE[131] = 'active';

$username = $_SESSION['sessionfixation']['username'];
$login_time = isset($_SESSION['sessionfixation']['login_time']) ? $_SESSION['sessionfixation']['login_time'] : '';

include_once $PIKA_ROOT_DIR . 'header.php';
?>

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="sessionfixation.php">Session Fixation</a>
                </li>
                <li class="active">Profile</li>
            </ul>
            <a href="#" style="float:right" data-container="body" data-toggle="popover" data-placement="bottom" title="tips"
               data-content="If the Session ID here is the same as the one fixed before login, the fixation attack worked. Root cause: no session_regenerate_id(true) after login.">
                point
            </a>
        </div>
        <div class="page-content">
            <div class="vul info">
                <p>Current user: <strong><?php echo htmlspecialchars($username);?></strong></p>
                <p>Current Session ID: <code><?php echo htmlspecialchars(session_id());?></code></p>
                <p>Login status: <strong>logged in</strong></p>
                <p>Login time: <?php echo htmlspecialchars($login_time);?></p>
                <br>
                The app intentionally keeps the old Session ID after login. That is the vulnerability in this lab.
                <br><br>
                <a href="fixation_login.php">Back</a>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
