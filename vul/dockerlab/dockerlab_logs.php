<?php
/**
 * Docker Lab read-only logs page.
 */

$ACTIVE = array_fill(0, 170, '');
$ACTIVE[140] = 'active open';
$ACTIVE[143] = 'active';

$PIKA_ROOT_DIR = "../../";
require_once __DIR__ . '/dockerlab_lib.php';

$lab_id = isset($_GET['id']) ? $_GET['id'] : '';
$error = '';
$log_text = '';
$template = false;
$status = array('state' => 'unknown');

if(!dockerlab_validate_lab_id($lab_id)){
    $error = '请求的模板 ID 非法。';
}else{
    $env = dockerlab_check_environment();
    if(!$env['daemon_reachable']){
        $error = 'Docker 当前不可用：' . $env['message'];
    }else{
        $log_result = dockerlab_get_logs($lab_id, 200);
        $template = $log_result['template'];
        $status = $log_result['status'];
        if(!$log_result['ok']){
            $error = $log_result['message'];
        }else{
            $log_text = $log_result['logs'] !== '' ? $log_result['logs'] : '[最近 200 行日志为空]';
        }
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
                <li class="active">日志查看</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="vul info">
                <p>日志查看（只读）</p><br>
                <p>当前模板 ID：<code><?php echo dockerlab_h($lab_id); ?></code></p>
                <?php if($template !== false){ ?>
                    <p>容器名：<code><?php echo dockerlab_h($template['container_name']); ?></code></p>
                    <p>当前状态：<strong><?php echo dockerlab_h(dockerlab_state_text($status['state'])); ?></strong></p>
                <?php } ?>

                <?php if($error !== ''){ ?>
                    <p class="notice"><?php echo dockerlab_h($error); ?></p>
                <?php } else { ?>
                    <p class="notice">以下内容只读取白名单模板对应容器的最近 200 行日志：</p>
<pre style="width:100%;white-space:pre-wrap;word-break:break-all;"><?php echo dockerlab_h($log_text); ?></pre>
                <?php } ?>

                <p><a href="dockerlab_center.php">返回模板列表</a></p>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
