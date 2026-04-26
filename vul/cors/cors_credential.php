<?php
/**
 * Created by runner.han
 * There is nothing new under the sun
 */

$ACTIVE = array_fill(0, 150, '');
$ACTIVE[132] = 'active open';
$ACTIVE[135] = 'active';

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
                <li class="active">Allow-Credentials</li>
            </ul>
            <a href="#" style="float:right" data-container="body" data-toggle="popover" data-placement="bottom" title="tips(提示)"
               data-content="只有不同 Origin 的页面里，浏览器才会真的进入 credential CORS 判断。把 cors_attacker.php 的模板复制到其他端口后，再观察响应头和 Cookie 携带行为。">
                点一点提示
            </a>
        </div>
        <div class="page-content">
            <div class="vul info">
                <p>场景说明</p>
                <p>这个场景会返回 <code>Access-Control-Allow-Credentials: true</code>，同时继续反射请求里的 <code>Origin</code>，用于演示宽松且错误的带凭证 CORS 配置。</p>
                <p>真正观察浏览器行为时，必须从<strong>不同 Origin</strong> 的页面发起请求。直接在 Pikachu 同源页面里执行，只能看到模板预览，不会真正触发浏览器的跨源决策。</p>
                <p>推荐做法：使用 <a href="cors_attacker.php">cors_attacker.php</a> 作为模板，并把它复制到不同端口或不同域名运行。</p>
                <p>接口地址：<a href="cors_api.php?scenario=credential" target="_blank">cors_api.php?scenario=credential</a></p>
<pre style="width:680px;">
// 请在不同 Origin 的页面中执行
fetch('http://127.0.0.1/pikachu/vul/cors/cors_api.php?scenario=credential', {
    credentials: 'include'
}).then(r => r.json()).then(console.log).catch(console.error)
</pre>
                <p>这个场景适合结合浏览器开发者工具和 Burp 观察响应头，重点是错误的 CORS 响应配置，而不是接口本身做了额外认证。</p>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
