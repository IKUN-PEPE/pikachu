<?php
/**
 * Docker Lab overview page.
 */

$ACTIVE = array_fill(0, 160, '');
$ACTIVE[140] = 'active open';
$ACTIVE[141] = 'active';

$PIKA_ROOT_DIR = "../../";
require_once __DIR__ . '/dockerlab_lib.php';
include_once $PIKA_ROOT_DIR . 'header.php';

$templates = dockerlab_load_templates();
?>

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="dockerlab.php">Docker Lab</a>
                </li>
                <li class="active">概述</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="vul info">
                <p>Docker Lab / 靶场编排中心</p><br>
                Docker Lab 用于在 Pikachu 页面里查看本地 Docker 漏洞环境模板，并展示这些白名单模板对应容器的当前状态。
                <br><br>
                当前仅实现 Phase 1 只读能力：
                <br>
                1. Docker 环境检查；
                <br>
                2. 白名单模板加载；
                <br>
                3. 模板列表与只读容器状态展示。
                <br><br>
                当前不会在页面中启动、停止、删除或重启容器，也不会提供任意 Docker 命令执行入口。
                <br><br>
                安全边界：
                <br>
                - 只允许内置白名单模板；
                <br>
                - 默认端口只允许绑定 <code>127.0.0.1</code>；
                <br>
                - 容器名必须以 <code>pikachu-</code> 开头；
                <br>
                - 容器必须带 <code>pikachu.lab=true</code> label；
                <br>
                - 不允许 <code>volume</code>、<code>privileged</code>、<code>--network host</code>、<code>--cap-add</code>、<code>--device</code>、<code>/var/run/docker.sock</code>。
                <br><br>
                当前白名单模板数量：<strong><?php echo dockerlab_html((string)count($templates)); ?></strong>
                <br><br>
                <a href="dockerlab_check.php">进入环境检查页</a> |
                <a href="dockerlab_center.php">进入模板列表页</a>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
