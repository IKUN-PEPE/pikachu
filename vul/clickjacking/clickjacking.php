<?php
/**
 * Created by runner.han
 * There is nothing new under the sun
 */

$ACTIVE = array_fill(0, 150, '');
$ACTIVE[136] = 'active open';
$ACTIVE[137] = 'active';

$PIKA_ROOT_DIR = "../../";
include_once $PIKA_ROOT_DIR . 'header.php';
?>

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="clickjacking.php">Clickjacking</a>
                </li>
                <li class="active">概述</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="vul info">
                <p>Clickjacking(点击劫持)</p><br>
                点击劫持的核心问题是：目标页面可以被第三方站点用 <code>iframe</code> 嵌套，而页面本身又没有设置 <code>X-Frame-Options</code> 或 <code>CSP frame-ancestors</code>。
                <br><br>
                这样攻击者就可以把真实按钮藏在诱导界面下面，骗用户点击本不想点的功能。
                <br><br>
                Clickjacking 和 CSRF 有联系，但不一样。CSRF 更强调“请求被跨站发出”，Clickjacking 更强调“用户被视觉误导后自己点下去”。
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
