<?php
/**
 * Created by runner.han
 * There is nothing new under the sun
 */

$ACTIVE = array_fill(0, 150, '');
$ACTIVE[125] = 'active open';
$ACTIVE[126] = 'active';

$PIKA_ROOT_DIR = "../../";
include_once $PIKA_ROOT_DIR . 'header.php';
?>

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="hostheader.php">Host Header</a>
                </li>
                <li class="active">概述</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="vul info">
                <p>Host Header Trust</p><br>
                有些应用会直接信任 HTTP 请求头里的 <code>Host</code>，然后用它去拼接密码重置链接、站内绝对 URL、邮件中的跳转地址等。
                <br><br>
                如果服务端没有对白名单 Host 做校验，攻击者就可以通过修改 Host 请求头，把这些链接污染成自己控制的域名。
                <br><br>
                这类问题常见于：
                <br>
                --> 密码找回邮件中的重置地址；
                <br>
                --> 站内生成的绝对链接；
                <br>
                --> 依赖 Host 做业务判断的跳转逻辑。
                <br><br>
                本模块用于演示 Host Header 被盲目信任时的风险点，属于靶场漏洞场景，不是推荐实现。
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
