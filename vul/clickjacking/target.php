<?php
/**
 * Created by runner.han
 * There is nothing new under the sun
 */

$ACTIVE = array_fill(0, 150, '');
$ACTIVE[136] = 'active open';
$ACTIVE[138] = 'active';

$PIKA_ROOT_DIR = "../../";
include_once $PIKA_ROOT_DIR . 'header.php';

$result = '';
if(isset($_GET['action'])){
    if($_GET['action'] == 'change_email'){
        $result = 'Action done: demo email changed to attacker@pikachu.com';
    }elseif($_GET['action'] == 'delete_message'){
        $result = 'Action done: one demo message deleted';
    }
}
?>

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="clickjacking.php">Clickjacking</a>
                </li>
                <li class="active">Target</li>
            </ul>
            <a href="#" style="float:right" data-container="body" data-toggle="popover" data-placement="bottom" title="tips"
               data-content="This page intentionally does not set X-Frame-Options or frame-ancestors, so attacker.php can iframe it.">
                point
            </a>
        </div>
        <div class="page-content">
            <div class="vul info" style="padding:30px 20px;">
                <h3 style="margin-top:0;">Account Center</h3>
                <p>Bound email: admin@pikachu.com</p>
                <p>Latest message: normal</p>
                <p style="margin-top:30px;">
                    <a class="btn btn-danger" href="target.php?action=change_email" style="width:220px;">Change Email</a>
                </p>
                <p style="margin-top:15px;">
                    <a class="btn btn-warning" href="target.php?action=delete_message" style="width:220px;">Delete One Message</a>
                </p>
                <?php if($result){ ?>
                    <div class="notice" style="margin-top:30px;"><?php echo htmlspecialchars($result);?></div>
                <?php } ?>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
