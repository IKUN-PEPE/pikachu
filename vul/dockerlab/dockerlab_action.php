<?php
/**
 * Docker Lab Phase 1 action placeholder.
 * Phase 1 intentionally refuses all state-changing actions.
 */

$ACTIVE = array_fill(0, 170, '');
$ACTIVE[140] = 'active open';
$ACTIVE[143] = 'active';

$PIKA_ROOT_DIR = "../../";
require_once __DIR__ . '/dockerlab_lib.php';

$method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
$action = '';
$lab_id = '';
$allowed = array('start', 'stop', 'remove', 'restart');

$message = 'Docker Lab Phase 1 当前只支持只读检查和模板展示，启动/停止/删除将在 Phase 2 开启。';
if($method !== 'POST'){
    $message = 'Docker Lab Phase 1 只支持只读检查和模板展示，状态改变动作将在 Phase 2 通过 POST + CSRF 开启。';
}else{
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $lab_id = isset($_POST['id']) ? $_POST['id'] : '';

    if($action !== '' && !in_array($action, $allowed, true)){
        $message = '请求的 action 不在允许列表中。Phase 1 不会执行任何 Docker 状态改变命令。';
    }elseif($lab_id !== '' && !dockerlab_validate_lab_id($lab_id)){
        $message = '请求的模板 ID 非法。Phase 1 不会执行任何 Docker 状态改变命令。';
    }
}

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
                <p><?php echo dockerlab_h($message); ?></p>
                <p>请求 action：<code><?php echo dockerlab_h($action); ?></code></p>
                <p>请求 id：<code><?php echo dockerlab_h($lab_id); ?></code></p>
                <p><a href="dockerlab_center.php">返回模板列表</a></p>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
