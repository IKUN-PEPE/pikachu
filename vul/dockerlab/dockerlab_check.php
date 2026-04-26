<?php
/**
 * Docker Lab environment check page.
 */

$ACTIVE = array_fill(0, 160, '');
$ACTIVE[140] = 'active open';
$ACTIVE[142] = 'active';

$PIKA_ROOT_DIR = "../../";
require_once __DIR__ . '/dockerlab_lib.php';

$env = dockerlab_check_environment();

include_once $PIKA_ROOT_DIR . 'header.php';
?>

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="dockerlab.php">Docker Lab</a>
                </li>
                <li class="active">环境检查</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="vul info">
                <p>Docker 环境检查</p><br>
                <p>本页只执行只读检查，不会启动、停止、删除任何容器。</p>
                <table class="table table-bordered table-striped">
                    <tr>
                        <td>检查项</td>
                        <td>结果</td>
                    </tr>
                    <tr>
                        <td>docker 命令可用</td>
                        <td><?php echo dockerlab_html($env['docker_found'] ? '是' : '否'); ?></td>
                    </tr>
                    <tr>
                        <td>Docker CLI 版本可读取</td>
                        <td><?php echo dockerlab_html($env['docker_version_ok'] ? '是' : '否'); ?></td>
                    </tr>
                    <tr>
                        <td>Docker daemon 可达</td>
                        <td><?php echo dockerlab_html($env['daemon_reachable'] ? '是' : '否'); ?></td>
                    </tr>
                    <tr>
                        <td>Client Version</td>
                        <td><?php echo dockerlab_html($env['client_version']); ?></td>
                    </tr>
                    <tr>
                        <td>Server Version</td>
                        <td><?php echo dockerlab_html($env['server_version']); ?></td>
                    </tr>
                    <tr>
                        <td>说明</td>
                        <td><?php echo dockerlab_html($env['message']); ?></td>
                    </tr>
                </table>
                <p class="notice">如果 daemon 不可达，请先确认 Docker Desktop 已启动，并且当前用户可以执行 <code>docker version</code> 与 <code>docker info</code>。</p>
                <p><a href="dockerlab_center.php">查看模板列表</a></p>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
