<?php
/**
 * Docker Lab Phase 1 helper functions.
 * Phase 1 only exposes read-only Docker diagnostics and template status display.
 */

function dockerlab_h($value){
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function dockerlab_html($value){
    return dockerlab_h($value);
}

function dockerlab_template_dir(){
    return __DIR__ . DIRECTORY_SEPARATOR . 'templates';
}

function dockerlab_validate_lab_id($id){
    return is_string($id) && preg_match('/^[a-z0-9-]+$/', $id) === 1;
}

function dockerlab_validate_template($template, &$errors = array()){
    $errors = array();

    if(!is_array($template)){
        $errors[] = '模板不是有效数组';
        return false;
    }

    $forbidden_keys = array('volumes', 'privileged', 'network_mode', 'cap_add', 'devices');
    foreach($forbidden_keys as $key){
        if(array_key_exists($key, $template)){
            $errors[] = '模板包含禁止字段: ' . $key;
        }
    }

    $required_keys = array('id', 'name', 'category', 'image', 'container_name', 'labels', 'ports', 'env', 'cmd', 'entry_url', 'notes');
    foreach($required_keys as $key){
        if(!array_key_exists($key, $template)){
            $errors[] = '模板缺少字段: ' . $key;
        }
    }

    if(!isset($template['id']) || !dockerlab_validate_lab_id($template['id'])){
        $errors[] = 'id 必须匹配 ^[a-z0-9-]+$';
    }

    if(!isset($template['name']) || !is_string($template['name']) || trim($template['name']) === ''){
        $errors[] = 'name 不能为空';
    }

    if(!isset($template['category']) || !is_string($template['category']) || trim($template['category']) === ''){
        $errors[] = 'category 不能为空';
    }

    if(!isset($template['image']) || !is_string($template['image']) || trim($template['image']) === ''){
        $errors[] = 'image 不能为空';
    }

    if(!isset($template['container_name']) || !is_string($template['container_name']) || strpos($template['container_name'], 'pikachu-') !== 0){
        $errors[] = 'container_name 必须以 pikachu- 开头';
    }

    if(!isset($template['labels']) || !is_array($template['labels'])){
        $errors[] = 'labels 必须为数组';
    }else{
        if(!isset($template['labels']['pikachu.lab']) || (string)$template['labels']['pikachu.lab'] !== 'true'){
            $errors[] = 'labels.pikachu.lab 必须等于 "true"';
        }
        if(!isset($template['labels']['pikachu.template']) || (string)$template['labels']['pikachu.template'] !== (string)$template['id']){
            $errors[] = 'labels.pikachu.template 必须等于模板 id';
        }
    }

    if(!isset($template['ports']) || !is_array($template['ports']) || count($template['ports']) < 1){
        $errors[] = 'ports 必须为非空数组';
    }else{
        foreach($template['ports'] as $index => $port){
            if(!is_array($port)){
                $errors[] = 'ports[' . $index . '] 不是有效数组';
                continue;
            }
            if(!isset($port['host_ip']) || $port['host_ip'] !== '127.0.0.1'){
                $errors[] = 'ports[' . $index . '].host_ip 必须等于 127.0.0.1';
            }
            if(!isset($port['host_port']) || !ctype_digit((string)$port['host_port'])){
                $errors[] = 'ports[' . $index . '].host_port 必须为整数';
            }
            if(!isset($port['container_port']) || !ctype_digit((string)$port['container_port'])){
                $errors[] = 'ports[' . $index . '].container_port 必须为整数';
            }
            if(isset($port['protocol']) && $port['protocol'] !== 'tcp'){
                $errors[] = 'ports[' . $index . '].protocol 仅允许 tcp';
            }
        }
    }

    if(isset($template['env']) && !is_array($template['env'])){
        $errors[] = 'env 必须为数组';
    }

    if(isset($template['cmd']) && !is_array($template['cmd'])){
        $errors[] = 'cmd 必须为数组';
    }

    if(isset($template['entry_url']) && $template['entry_url'] !== ''){
        if(!is_string($template['entry_url']) || strpos($template['entry_url'], 'http://127.0.0.1') !== 0){
            $errors[] = 'entry_url 仅允许 http://127.0.0.1 本地入口';
        }
    }

    return count($errors) === 0;
}

function dockerlab_load_templates(){
    $templates = array();
    $dir = dockerlab_template_dir();
    if(!is_dir($dir)){
        return $templates;
    }

    $files = glob($dir . DIRECTORY_SEPARATOR . '*.json');
    if($files === false){
        return $templates;
    }

    foreach($files as $file){
        $content = @file_get_contents($file);
        if($content === false){
            continue;
        }
        $template = json_decode($content, true);
        if(!is_array($template)){
            continue;
        }
        $errors = array();
        if(!dockerlab_validate_template($template, $errors)){
            continue;
        }
        $templates[$template['id']] = $template;
    }

    ksort($templates);
    return $templates;
}

function dockerlab_get_template($id){
    if(!dockerlab_validate_lab_id($id)){
        return false;
    }
    $templates = dockerlab_load_templates();
    return isset($templates[$id]) ? $templates[$id] : false;
}

function dockerlab_exec_available(){
    $disabled = (string)ini_get('disable_functions');
    $disabled_list = array_filter(array_map('trim', explode(',', $disabled)));
    return function_exists('exec') && !in_array('exec', $disabled_list, true);
}

function dockerlab_proc_open_available(){
    $disabled = (string)ini_get('disable_functions');
    $disabled_list = array_filter(array_map('trim', explode(',', $disabled)));
    return function_exists('proc_open') && !in_array('proc_open', $disabled_list, true);
}

function dockerlab_run_command($args, $timeout = 10){
    if(!is_array($args)){
        return array('ok' => false, 'exit_code' => 1, 'stdout' => '', 'stderr' => '命令参数必须为数组', 'command' => '');
    }
    if(count($args) < 2){
        return array('ok' => false, 'exit_code' => 1, 'stdout' => '', 'stderr' => '命令参数不足', 'command' => '');
    }
    if($args[0] !== 'docker'){
        return array('ok' => false, 'exit_code' => 1, 'stdout' => '', 'stderr' => '只允许固定 docker 命令', 'command' => '');
    }

    $subcommand = (string)$args[1];
    $allowed = array('--version', 'version', 'info', 'ps', 'inspect', 'logs');
    if(!in_array($subcommand, $allowed, true)){
        return array('ok' => false, 'exit_code' => 1, 'stdout' => '', 'stderr' => '只允许只读 docker 子命令', 'command' => '');
    }

    if(!dockerlab_proc_open_available()){
        return array('ok' => false, 'exit_code' => 1, 'stdout' => '', 'stderr' => '当前 PHP 环境不可用 proc_open，无法执行只读 Docker 检查', 'command' => '');
    }

    $parts = array('docker');
    for($i = 1; $i < count($args); $i++){
        $parts[] = escapeshellarg((string)$args[$i]);
    }
    $cmd = implode(' ', $parts);

    $descriptorspec = array(
        0 => array('pipe', 'r'),
        1 => array('pipe', 'w'),
        2 => array('pipe', 'w')
    );

    $process = @proc_open($cmd, $descriptorspec, $pipes, null, null);
    if(!is_resource($process)){
        return array('ok' => false, 'exit_code' => 1, 'stdout' => '', 'stderr' => '无法启动命令进程', 'command' => $cmd);
    }

    fclose($pipes[0]);
    stream_set_blocking($pipes[1], false);
    stream_set_blocking($pipes[2], false);

    $stdout = '';
    $stderr = '';
    $start = microtime(true);
    $timed_out = false;

    do{
        $stdout .= stream_get_contents($pipes[1]);
        $stderr .= stream_get_contents($pipes[2]);
        $status = proc_get_status($process);
        if(!$status['running']){
            break;
        }
        if((microtime(true) - $start) >= (int)$timeout){
            $timed_out = true;
            proc_terminate($process);
            break;
        }
        usleep(100000);
    }while(true);

    $stdout .= stream_get_contents($pipes[1]);
    $stderr .= stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exit_code = proc_close($process);

    if($timed_out){
        return array('ok' => false, 'exit_code' => 124, 'stdout' => trim($stdout), 'stderr' => '命令执行超时', 'command' => $cmd);
    }

    return array(
        'ok' => ($exit_code === 0),
        'exit_code' => $exit_code,
        'stdout' => trim($stdout),
        'stderr' => trim($stderr),
        'command' => $cmd
    );
}

function dockerlab_check_environment(){
    $result = array(
        'os' => PHP_OS_FAMILY . ' / ' . PHP_OS,
        'exec_available' => dockerlab_exec_available(),
        'proc_open_available' => dockerlab_proc_open_available(),
        'docker_found' => false,
        'docker_version_ok' => false,
        'daemon_reachable' => false,
        'docker_version' => '',
        'docker_info' => '',
        'message' => ''
    );

    $version = dockerlab_run_command(array('docker', '--version'), 10);
    if(!$version['ok']){
        $result['message'] = $version['stderr'] !== '' ? $version['stderr'] : '未检测到可用的 Docker CLI';
        return $result;
    }

    $result['docker_found'] = true;
    $result['docker_version_ok'] = true;
    $result['docker_version'] = $version['stdout'];

    $info = dockerlab_run_command(array('docker', 'info', '--format', '{{.OperatingSystem}} | {{.OSType}} | {{.Architecture}}'), 10);
    if(!$info['ok']){
        $result['message'] = $info['stderr'] !== '' ? $info['stderr'] : 'Docker daemon 不可达';
        return $result;
    }

    $result['daemon_reachable'] = true;
    $result['docker_info'] = $info['stdout'];
    $result['message'] = 'Docker 环境可用（只读检测）';
    return $result;
}

function dockerlab_list_lab_containers(){
    $result = dockerlab_run_command(array(
        'docker', 'ps', '-a',
        '--filter', 'label=pikachu.lab=true',
        '--format', '{{.Names}}|{{.Status}}|{{.Ports}}|{{.Labels}}'
    ), 10);

    if(!$result['ok']){
        return array();
    }

    $items = array();
    $lines = preg_split('/\r\n|\r|\n/', $result['stdout']);
    foreach($lines as $line){
        $line = trim($line);
        if($line === ''){
            continue;
        }
        $parts = explode('|', $line, 4);
        $items[] = array(
            'name' => isset($parts[0]) ? $parts[0] : '',
            'status' => isset($parts[1]) ? $parts[1] : '',
            'ports' => isset($parts[2]) ? $parts[2] : '',
            'labels' => isset($parts[3]) ? $parts[3] : ''
        );
    }
    return $items;
}

function dockerlab_get_container_status($template){
    $status = array(
        'state' => 'unknown',
        'docker_status' => '',
        'ports' => '',
        'container_name' => isset($template['container_name']) ? $template['container_name'] : ''
    );

    if(!is_array($template) || !isset($template['container_name'])){
        $status['state'] = 'error';
        return $status;
    }

    $containers = dockerlab_list_lab_containers();
    if(count($containers) === 0){
        $env = dockerlab_check_environment();
        if(!$env['daemon_reachable']){
            $status['state'] = 'unknown';
            $status['docker_status'] = $env['message'];
            return $status;
        }
    }

    foreach($containers as $item){
        if($item['name'] !== $template['container_name']){
            continue;
        }
        $status['docker_status'] = $item['status'];
        $status['ports'] = $item['ports'];
        if(stripos($item['status'], 'Up ') === 0){
            $status['state'] = 'running';
        }elseif(stripos($item['status'], 'Exited') === 0){
            $status['state'] = 'stopped';
        }elseif(stripos($item['status'], 'Created') === 0){
            $status['state'] = 'created';
        }else{
            $status['state'] = 'present';
        }
        return $status;
    }

    $status['state'] = 'not_created';
    return $status;
}

function dockerlab_build_port_text($template){
    if(!isset($template['ports']) || !is_array($template['ports']) || count($template['ports']) < 1){
        return '无端口配置';
    }

    $items = array();
    foreach($template['ports'] as $port){
        $items[] = $port['host_ip'] . ':' . $port['host_port'] . ' -> ' . $port['container_port'] . '/' . $port['protocol'];
    }
    return implode(', ', $items);
}

function dockerlab_build_entry_url($template){
    if(isset($template['entry_url']) && is_string($template['entry_url']) && $template['entry_url'] !== ''){
        return $template['entry_url'];
    }
    return '';
}

function dockerlab_state_text($state){
    $map = array(
        'running' => '运行中',
        'stopped' => '已停止',
        'created' => '已创建',
        'present' => '已存在',
        'not_created' => '未运行',
        'unknown' => '未知',
        'error' => '状态异常'
    );
    return isset($map[$state]) ? $map[$state] : '未知';
}
?>
