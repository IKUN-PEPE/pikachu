<?php
/**
 * Docker Lab Phase 1 action placeholder.
 * Phase 1 intentionally does not expose state-changing Docker actions.
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
                <li class="active">操作入口</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="vul info">
                <p>Phase 1 只实现只读能力。</p>
                <p>当前页面不会接收启动、停止、删除、重启等状态变更请求。</p>
                <p><a href="dockerlab_center.php">返回模板列表</a></p>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
