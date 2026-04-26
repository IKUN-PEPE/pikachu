<?php
/**
 * Docker Lab template list and read-only status page.
 */

$ACTIVE = array_fill(0, 160, '');
$ACTIVE[140] = 'active open';
$ACTIVE[143] = 'active';

$PIKA_ROOT_DIR = "../../";
require_once __DIR__ . '/dockerlab_lib.php';

$env = dockerlab_check_environment();
$templates = dockerlab_load_templates();

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
                <li class="active">模板列表</li>
            </ul>
            <a href="#" style="float:right" data-container="body" data-toggle="popover" data-placement="bottom" title="tips(提示)"
               data-content="Phase 1 只提供白名单模板展示与只读容器状态检查，不会在页面里执行 docker run、docker stop、docker rm 或任意 Docker 命令。">
                点一点提示
            </a>
        </div>
        <div class="page-content">
            <div class="vul info">
                <p>当前能力</p>
                <p>Docker 环境状态：<strong><?php echo dockerlab_html($env['daemon_reachable'] ? '可用' : '不可用'); ?></strong></p>
                <p>已加载白名单模板：<strong><?php echo dockerlab_html((string)count($templates)); ?></strong></p>
                <p>当前阶段为只读展示。启动、停止、删除、重启功能尚未开放。</p>
                <p><a href="dockerlab_check.php">查看详细环境检查</a> | <a href="dockerlab_logs.php">查看日志页说明</a></p>
                <br>
                <table class="table table-bordered table-striped">
                    <tr>
                        <td>模板 ID</td>
                        <td>名称</td>
                        <td>分类</td>
                        <td>镜像</td>
                        <td>容器名</td>
                        <td>本地入口</td>
                        <td>状态</td>
                        <td>端口映射</td>
                    </tr>
                    <?php foreach($templates as $template){ ?>
                        <?php $status = dockerlab_get_container_status($template); ?>
                        <tr>
                            <td><?php echo dockerlab_html($template['id']); ?></td>
                            <td><?php echo dockerlab_html($template['name']); ?></td>
                            <td><?php echo dockerlab_html($template['category']); ?></td>
                            <td><?php echo dockerlab_html($template['image']); ?></td>
                            <td><?php echo dockerlab_html($template['container_name']); ?></td>
                            <td>
                                <?php if($template['entry_url'] !== ''){ ?>
                                    <a href="<?php echo dockerlab_html($template['entry_url']); ?>" target="_blank"><?php echo dockerlab_html($template['entry_url']); ?></a>
                                <?php } else { ?>
                                    <?php echo dockerlab_html('无 Web 入口'); ?>
                                <?php } ?>
                            </td>
                            <td><?php echo dockerlab_html(dockerlab_state_text($status['state'])); ?></td>
                            <td><?php echo dockerlab_html($status['ports']); ?></td>
                        </tr>
                    <?php } ?>
                </table>
                <p class="notice">状态查询只会读取带 <code>pikachu.lab=true</code> label 的容器；不存在的模板会显示“未创建”。</p>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
