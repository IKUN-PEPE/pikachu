<?php
/**
 * Created by runner.han
 * There is nothing new under the sun
 */

$ACTIVE = array_fill(0, 150, '');
$ACTIVE[132] = 'active open';
$ACTIVE[134] = 'active';

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
                <li class="active">Origin 反射</li>
            </ul>
            <a href="#" style="float:right" data-container="body" data-toggle="popover" data-placement="bottom" title="tips(提示)"
               data-content="真正的 CORS 现象只能在不同 Origin 的页面里观察。可以把 cors_attacker.php 里的模板复制到其他端口，再看接口是否反射 Origin。">
                点一点提示
            </a>
        </div>
        <div class="page-content">
            <div class="vul info">
                <p>场景说明</p>
                <p>这个场景会把请求头里的 <code>Origin</code> 原样写回 <code>Access-Control-Allow-Origin</code>，用于演示错误的 Origin 反射配置。</p>
                <p>真正观察浏览器 CORS 流程时，必须从<strong>不同 Origin</strong> 的页面发起请求。直接在 Pikachu 同源页面里打开或执行，只是模板预览，不会触发浏览器的跨源拦截流程。</p>
                <p>推荐做法：使用 <a href="cors_attacker.php">cors_attacker.php</a> 作为攻击者页面模板，并把它复制到不同端口或不同域名运行。</p>
                <p>接口地址：<a href="cors_api.php?scenario=reflect" target="_blank">cors_api.php?scenario=reflect</a></p>
<pre style="width:680px;">
// 请在不同 Origin 的页面中执行
fetch('http://127.0.0.1/pikachu/vul/cors/cors_api.php?scenario=reflect')
    .then(r => r.text())
    .then(console.log)
    .catch(console.error)
</pre>
                <p>你可以直接打开上面的接口看 JSON，也可以用浏览器开发者工具或 Burp 手工修改 <code>Origin</code>，观察响应头是否跟着反射变化。</p>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
