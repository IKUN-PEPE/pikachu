<?php
/**
 * Created by runner.han
 * There is nothing new under the sun
 */

$ACTIVE = array_fill(0, 150, '');
$ACTIVE[132] = 'active open';
$ACTIVE[133] = 'active';

$PIKA_ROOT_DIR = "../../";
include_once $PIKA_ROOT_DIR . 'header.php';
?>

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="cors.php">CORS Misconfiguration</a>
                </li>
                <li class="active">概述</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="vul info">
                <p>CORS Misconfiguration(CORS 配置错误)</p><br>
                CORS 本身不是漏洞，问题出在服务端把跨域策略配置得过于宽松。
                <br><br>
                这个模块演示两类常见错误：
                <br>
                1. 直接反射任意 <code>Origin</code>；
                <br>
                2. 返回 <code>Access-Control-Allow-Credentials: true</code>，同时又对来源限制过松。
                <br><br>
                这里的 <code>cors_api.php</code> 会真实返回错误的 CORS 响应头，适合配合浏览器开发者工具、Burp 或 curl 观察。
                <br><br>
                需要记住：CORS 不是鉴权机制。
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
