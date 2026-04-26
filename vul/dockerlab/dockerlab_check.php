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
                        <td>PHP OS</td>
                        <td><?php echo dockerlab_h($env['os']); ?></td>
                    </tr>
                    <tr>
                        <td>exec 可用</td>
                        <td><?php echo dockerlab_h($env['exec_available'] ? '是' : '否'); ?></td>
                    </tr>
                    <tr>
                        <td>proc_open 可用</td>
                        <td><?php echo dockerlab_h($env['proc_open_available'] ? '是' : '否'); ?></td>
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
                        <td>docker version 摘要</td>
                        <td><?php echo dockerlab_h($env['docker_version']); ?></td>
                    </tr>
                    <tr>
                        <td>Docker Info（简要）</td>
                        <td><?php echo dockerlab_html($env['docker_info']); ?></td>
                    </tr>
                    <tr>
                        <td>说明</td>
                        <td><?php echo dockerlab_html($env['message']); ?></td>
                    </tr>
                </table>
                <p class="notice">常见问题与排查（Windows / PowerShell）：</p>
<pre style="width:100%;">
docker version
docker info
docker ps -a --filter "label=pikachu.lab=true"
</pre>
                <p class="notice">如果显示 daemon 不可达：请确认 Docker Desktop 已启动，并等待其初始化完成；如有权限问题，请用同一用户在 PowerShell 中直接执行上面的命令查看报错。</p>
                <p><a href="dockerlab_center.php">查看模板列表</a></p>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
