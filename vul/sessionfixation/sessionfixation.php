<?php
/**
 * Created by runner.han
 * There is nothing new under the sun
 */

$ACTIVE = array_fill(0, 150, '');
$ACTIVE[128] = 'active open';
$ACTIVE[129] = 'active';

$PIKA_ROOT_DIR = "../../";
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
                <li class="active">概述</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="vul info">
                <p>Session Fixation(会话固定)</p><br>
                会话固定的根因是：用户登录成功后，应用没有刷新 Session ID，而是继续沿用登录前的会话标识。
                <br><br>
                如果攻击者能在登录前先固定一个已知 sid，再诱导受害者使用它登录，那么攻击者就可能复用这个登录后的会话。
                <br><br>
                本模块可以真实演示：
                <br>
                1. 登录前通过 <code>?sid=</code> 固定 Session ID；
                <br>
                2. 登录成功后故意不调用 <code>session_regenerate_id(true)</code>；
                <br>
                3. 登录后页面继续展示同一个 Session ID。
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
