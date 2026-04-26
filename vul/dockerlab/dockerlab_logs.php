<?php
/**
 * Docker Lab Phase 1 logs placeholder.
 */

$ACTIVE = array_fill(0, 160, '');
$ACTIVE[140] = 'active open';
$ACTIVE[143] = 'active';

$PIKA_ROOT_DIR = "../../";
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
                <li class="active">日志说明</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="vul info">
                <p>日志查看功能预留中。</p>
                <p>Phase 1 当前只提供白名单模板加载、Docker 环境检查和只读容器状态展示。</p>
                <p>后续阶段如开放日志查看，仍会限制为目标模板容器的最近 200 行输出，并对所有页面输出做 <code>htmlspecialchars</code> 处理。</p>
                <p><a href="dockerlab_center.php">返回模板列表</a></p>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
