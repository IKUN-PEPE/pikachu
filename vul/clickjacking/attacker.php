<?php
/**
 * Created by runner.han
 * There is nothing new under the sun
 */

$ACTIVE = array_fill(0, 150, '');
$ACTIVE[136] = 'active open';
$ACTIVE[139] = 'active';

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
                <li class="active">恶意诱导页</li>
            </ul>
            <a href="#" style="float:right" data-container="body" data-toggle="popover" data-placement="bottom" title="tips(提示一下下)"
               data-content="看起来你是在点福利按钮，实际点到的是 iframe 里的目标页面。这和 CSRF 的区别在于，这里主要利用的是视觉误导。">
                点一下提示~
            </a>
        </div>
        <div class="page-content">
            <div class="vul info">
                <p>下面这个“立即领取”只是诱导层，真正会响应的是 iframe 里的目标按钮。</p>
                <div style="position:relative;width:760px;height:420px;overflow:hidden;border:1px solid #d5d5d5;background:#f7f7f7;">
                    <div style="position:absolute;left:80px;top:35px;font-size:28px;color:#d15b47;font-weight:bold;z-index:1;">
                        恭喜你获得 Pikachu 限量福利
                    </div>
                    <div style="position:absolute;left:140px;top:165px;width:220px;height:48px;line-height:48px;text-align:center;background:#d9534f;color:#fff;border-radius:4px;font-size:20px;z-index:3;pointer-events:none;">
                        立即领取
                    </div>
                    <iframe src="target.php" style="position:absolute;left:-30px;top:-105px;width:850px;height:620px;border:0;opacity:0.08;z-index:2;"></iframe>
                    <div style="position:absolute;left:80px;bottom:30px;width:600px;color:#666;z-index:1;">
                        核心风险不是脚本多复杂，而是目标页面可以被第三方 iframe 嵌套。
                    </div>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
