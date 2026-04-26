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
    $template = dockerlab_get_template($lab_id);
    if($template === false){
        $error = '请求的模板不存在或未通过白名单校验。';
    }else{
        $env = dockerlab_check_environment();
        if(!$env['daemon_reachable']){
            $error = 'Docker 当前不可用：' . $env['message'];
        }else{
            $status = dockerlab_get_container_status($template);
            if($status['state'] === 'not_created'){
                $error = '当前模板容器尚未运行，暂无日志可读。';
            }elseif($status['state'] === 'unknown'){
                $error = '当前无法确认容器状态：' . $status['docker_status'];
            }else{
                $result = dockerlab_run_command(array('docker', 'logs', '--tail', '200', $template['container_name']), 10);
                if($result['ok']){
                    $combined_output = trim($result['stdout']);
                    if(trim($result['stderr']) !== ''){
                        $combined_output = trim($combined_output . ($combined_output !== '' ? "\n" : '') . $result['stderr']);
                    }
                    $log_text = $combined_output !== '' ? $combined_output : '[最近 200 行日志为空]';
                }else{
                    $error = $result['stderr'] !== '' ? $result['stderr'] : '读取日志失败';
                }
            }
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
