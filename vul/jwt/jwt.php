<?php
/**
 * Created by runner.han
 * There is nothing new under the sun
 */

$ACTIVE = array_fill(0, 150, '');
$ACTIVE[122] = 'active open';
$ACTIVE[123] = 'active';

$PIKA_ROOT_DIR = "../../";
include_once $PIKA_ROOT_DIR . 'header.php';
?>

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="jwt.php">JWT</a>
                </li>
                <li class="active">概述</li>
            </ul>
        </div>
        <div class="page-content">
            <div id="vul_info">
                <dd class="vul_detail">
                    JWT(JSON Web Token) 常用于前后端分离系统中的登录态传递。问题往往不在 JWT 格式本身，而在服务端对 Token 的校验、签名算法和权限字段的处理是否足够严格。
                </dd>
                <dd class="vul_detail">
                    本模块刻意采用了不安全的解析逻辑，用来演示服务端盲信 Token 内容时会出现什么问题。这里的实现属于靶场漏洞示例，不是安全写法。
                </dd>
                <dd class="vul_detail_1">
                    你可以重点观察：
                    <br />
                    1. 是否错误接受 <code>alg=none</code>；
                    <br />
                    2. 是否直接信任 Token 里的 <code>role</code> / <code>level</code>；
                    <br />
                    3. 是否在权限判断时只看 payload，不重新核对服务端状态。
                </dd>
                <dd class="vul_detail_1">
                    演示账号：
                    <br />
                    admin / 123456
                    <br />
                    pikachu / 000000
                </dd>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
