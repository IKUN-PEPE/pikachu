<?php
/**
 * Created by runner.han
 * There is nothing new under the sun
 */

$ACTIVE = array_fill(0, 150, '');
$ACTIVE[125] = 'active open';
$ACTIVE[127] = 'active';

$PIKA_ROOT_DIR = "../../";
$html = '';

if(isset($_POST['submit'])){
    $account = '';
    if(isset($_POST['account'])){
        $account = htmlspecialchars($_POST['account']);
    }

    $host_raw = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '127.0.0.1';

    $scheme = 'http';
    if(
        (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === '1')) ||
        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
    ){
        $scheme = 'https';
    }

    $path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $reset_link_raw = $scheme . '://' . $host_raw . $path . '/trust.php?token=pikachu-reset';
    $host = htmlspecialchars($host_raw, ENT_QUOTES, 'UTF-8');
    $reset_link = htmlspecialchars($reset_link_raw, ENT_QUOTES, 'UTF-8');

    $html = <<<A
<div class="host_main">
    <p class="notice">系统正在为 <strong>{$account}</strong> 生成绝对重置链接。</p>
    <p class="notice">当前直接信任的 Host：<code>{$host}</code></p>
    <p class="notice">生成出的绝对链接：<a href="{$reset_link}">{$reset_link}</a></p>
    <p class="notice">本靶场仍然直接使用 HTTP_HOST，只是把 scheme 改成了动态识别。</p>
    <p class="notice">生产环境里如果读取 X-Forwarded-Proto，前提也必须是请求经过可信代理边界。</p>
</div>
A;
}

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
                <li class="active">Host Header Trust</li>
            </ul>
            <a href="#" style="float:right" data-container="body" data-toggle="popover" data-placement="bottom" title="tips(提示)"
               data-content="在 Burp、curl 或浏览器代理里修改 Host 头后再次提交，页面生成的绝对链接会跟着你注入的 Host 变化。">
                点一点提示
            </a>
        </div>
        <div class="page-content">
            <div class="vul info">
                <form method="post">
                    <p>输入一个账号，观察系统如何拼接绝对重置链接：</p>
                    <p>
                        <input class="input_text" type="text" name="account" value="admin@pikachu.com" style="width:280px;" />
                        <input class="submit" type="submit" name="submit" value="生成链接" />
                    </p>
                </form>
                <?php echo $html; ?>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
