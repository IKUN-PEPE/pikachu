<?php
/**
 * Docker Lab Phase 1 helper functions.
 * Phase 1 is read-only: template loading, environment checks and container status display.
 */

function dockerlab_template_dir(){
    return __DIR__ . DIRECTORY_SEPARATOR . 'templates';
}

function dockerlab_html($value){
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function dockerlab_validate_lab_id($id){
    return is_string($id) && preg_match('/^[a-z0-9-]+$/', $id) === 1;
}

function dockerlab_validate_template($template){
    $allowed_keys = array(
        'id', 'name', 'category', 'image', 'container_name', 'labels',
        'ports', 'env', 'cmd', 'entry_url', 'notes', 'enabled'
    );
    if(!is_array($template)){
        return array('ok' => false, 'error' => '模板不是有效数组');
    }

    foreach($template as $key => $value){
        if(!in_array($key, $allowed_keys, true)){
            return array('ok' => false, 'error' => '模板包含不支持字段: ' . $key);
        }
    }

    $required = array('id', 'name', 'category', 'image', 'container_name', 'labels', 'ports', 'env', 'cmd', 'entry_url', 'notes', 'enabled');
    foreach($required as $key){
        if(!array_key_exists($key, $template)){
            return array('ok' => false, 'error' => '模板缺少字段: ' . $key);
        }
    }

    if(!dockerlab_validate_lab_id($template['id'])){
        return array('ok' => false, 'error' => '模板 id 非法');
    }

    if(!is_string($template['container_name']) || strpos($template['container_name'], 'pikachu-') !== 0){
        return array('ok' => false, 'error' => 'container_name 必须以 pikachu- 开头');
    }

    if(!is_string($template['image']) || trim($template['image']) === ''){
        return array('ok' => false, 'error' => 'image 不能为空');
    }

    if(!is_array($template['labels']) || !isset($template['labels']['pikachu.lab']) || (string)$template['labels']['pikachu.lab'] !== 'true'){
        return array('ok' => false, 'error' => 'labels.pikachu.lab 必须为 true');
    }

    if(!isset($template['labels']['pikachu.template']) || (string)$template['labels']['pikachu.template'] !== (string)$template['id']){
        return array('ok' => false, 'error' => 'labels.pikachu.template 必须等于模板 id');
    }

    if(!is_array($template['ports']) || count($template['ports']) < 1){
        return array('ok' => false, 'error' => 'ports 至少包含一项');
    }

    foreach($template['ports'] as $port){
        if(!is_array($port)){
            return array('ok' => false, 'error' => 'ports 子项格式错误');
        }
        $port_required = array('host_ip', 'host_port', 'container_port', 'protocol');
        foreach($port_required as $key){
            if(!array_key_exists($key, $port)){
                return array('ok' => false, 'error' => 'ports 子项缺少字段: ' . $key);
            }
        }
        if($port['host_ip'] !== '127.0.0.1'){
            return array('ok' => false, 'error' => 'host_ip 只能为 127.0.0.1');
        }
        if(!is_int($port['host_port']) && !ctype_digit((string)$port['host_port'])){
            return array('ok' => false, 'error' => 'host_port 必须为整数');
        }
        if(!is_int($port['container_port']) && !ctype_digit((string)$port['container_port'])){
            return array('ok' => false, 'error' => 'container_port 必须为整数');
        }
        if((int)$port['host_port'] < 1 || (int)$port['host_port'] > 65535){
            return array('ok' => false, 'error' => 'host_port 超出范围');
        }
        if((int)$port['host_port'] < 1024){
            return array('ok' => false, 'error' => 'host_port 必须 >= 1024');
        }
        if((int)$port['container_port'] < 1 || (int)$port['container_port'] > 65535){
            return array('ok' => false, 'error' => 'container_port 超出范围');
        }
        if($port['protocol'] !== 'tcp'){
            return array('ok' => false, 'error' => 'protocol 仅允许 tcp');
        }
    }

    if(!is_array($template['env']) || !is_array($template['cmd'])){
        return array('ok' => false, 'error' => 'env/cmd 必须为数组');
    }

    if(!is_bool($template['enabled'])){
        return array('ok' => false, 'error' => 'enabled 必须为布尔值');
    }

    if(!is_string($template['entry_url'])){
        return array('ok' => false, 'error' => 'entry_url 必须为字符串');
    }
    if($template['entry_url'] !== ''){
        // Only allow local entry links in Phase 1.
        if(strpos($template['entry_url'], 'http://127.0.0.1') !== 0 && strpos($template['entry_url'], 'https://127.0.0.1') !== 0){
            return array('ok' => false, 'error' => 'entry_url 仅允许 127.0.0.1 本地地址');
        }
    }

    return array('ok' => true);
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
        $validation = dockerlab_validate_template($template);
        if(!$validation['ok']){
            continue;
        }
        if(!$template['enabled']){
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

function dockerlab_run_command($args, $timeout = 30){
    if(!is_array($args) || count($args) < 1){
        return array('ok' => false, 'exit_code' => 1, 'stdout' => '', 'stderr' => '命令参数无效');
    }

    $command = array_shift($args);
    if($command !== 'docker'){
        return array('ok' => false, 'exit_code' => 1, 'stdout' => '', 'stderr' => 'Phase 1 只允许固定 docker 命令');
    }

    // Phase 1 only: allow read-only subcommands.
    $subcommand = isset($args[0]) ? (string)$args[0] : '';
    if(!in_array($subcommand, array('version', 'ps'), true)){
        return array('ok' => false, 'exit_code' => 1, 'stdout' => '', 'stderr' => 'Phase 1 仅允许 docker version / docker ps');
    }

    $parts = array($command);
    foreach($args as $arg){
        $parts[] = escapeshellarg((string)$arg);
    }
    $cmd = implode(' ', $parts);

    $descriptorspec = array(
        0 => array('pipe', 'r'),
        1 => array('pipe', 'w'),
        2 => array('pipe', 'w')
    );

    $process = @proc_open($cmd, $descriptorspec, $pipes, null, null);
    if(!is_resource($process)){
        return array('ok' => false, 'exit_code' => 1, 'stdout' => '', 'stderr' => '无法启动命令进程');
    }

    fclose($pipes[0]);
    stream_set_blocking($pipes[1], false);
    stream_set_blocking($pipes[2], false);

    $stdout = '';
    $stderr = '';
    $start = time();
    $timed_out = false;

    do{
        $stdout .= stream_get_contents($pipes[1]);
        $stderr .= stream_get_contents($pipes[2]);
        $status = proc_get_status($process);
        if(!$status['running']){
            break;
        }
        if((time() - $start) >= (int)$timeout){
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
        return array('ok' => false, 'exit_code' => 124, 'stdout' => trim($stdout), 'stderr' => '命令执行超时');
    }

    return array(
        'ok' => ($exit_code === 0),
        'exit_code' => $exit_code,
        'stdout' => trim($stdout),
        'stderr' => trim($stderr)
    );
}

function dockerlab_docker_available(){
    $result = dockerlab_run_command(array('docker', 'version', '--format', '{{.Client.Version}}'), 10);
    return $result['ok'];
}

function dockerlab_check_environment(){
    $result = array(
        'docker_found' => false,
        'docker_version_ok' => false,
        'daemon_reachable' => false,
        'client_version' => '',
        'server_version' => '',
        'message' => ''
    );

    $version_client = dockerlab_run_command(array('docker', 'version', '--format', '{{.Client.Version}}'), 10);
    if(!$version_client['ok']){
        $result['message'] = $version_client['stderr'] !== '' ? $version_client['stderr'] : '未检测到可用的 docker 命令';
        return $result;
    }

    $result['docker_found'] = true;
    $result['docker_version_ok'] = true;
    $result['client_version'] = $version_client['stdout'];

    $version_server = dockerlab_run_command(array('docker', 'version', '--format', '{{.Server.Version}}'), 10);
    if($version_server['ok']){
        $result['daemon_reachable'] = true;
        $result['server_version'] = $version_server['stdout'];
        $result['message'] = 'Docker Desktop 运行正常';
        return $result;
    }

    $result['message'] = $version_server['stderr'] !== '' ? $version_server['stderr'] : 'Docker daemon 不可达';
    return $result;
}

function dockerlab_get_container_status($template){
    $status = array(
        'state' => 'unknown',
        'container_name' => isset($template['container_name']) ? $template['container_name'] : '',
        'docker_status' => '',
        'ports' => '',
        'label_ok' => false
    );

    if(!is_array($template) || !isset($template['container_name'])){
        $status['state'] = 'error';
        return $status;
    }

    // Note: docker name filter is substring match, not a strict regex. We filter by name substring
    // then select the exact name in PHP to avoid false negatives.
    $result = dockerlab_run_command(array(
        'docker', 'ps', '-a',
        '--filter', 'label=pikachu.lab=true',
        '--filter', 'name=' . $template['container_name'],
        '--format', '{{.Names}}|{{.Status}}|{{.Ports}}|{{.Labels}}'
    ), 10);

    if(!$result['ok']){
        $status['state'] = 'docker_unavailable';
        $status['docker_status'] = $result['stderr'];
        return $status;
    }

    if($result['stdout'] === ''){
        $status['state'] = 'not_created';
        return $status;
    }

    $lines = preg_split('/\r\n|\r|\n/', $result['stdout']);
    $line = '';
    foreach($lines as $candidate){
        $candidate = trim($candidate);
        if($candidate === ''){
            continue;
        }
        $parts0 = explode('|', $candidate, 2);
        $name0 = isset($parts0[0]) ? $parts0[0] : '';
        if($name0 === $template['container_name']){
            $line = $candidate;
            break;
        }
    }

    if($line === ''){
        $status['state'] = 'not_created';
        return $status;
    }

    $parts = explode('|', $line, 4);
    $status['docker_status'] = isset($parts[1]) ? $parts[1] : '';
    $status['ports'] = isset($parts[2]) ? $parts[2] : '';
    $labels = isset($parts[3]) ? $parts[3] : '';
    $status['label_ok'] = strpos($labels, 'pikachu.lab=true') !== false;

    if(!$status['label_ok']){
        $status['state'] = 'label_mismatch';
    }elseif(stripos($status['docker_status'], 'Up ') === 0){
        $status['state'] = 'running';
    }elseif(stripos($status['docker_status'], 'Exited') === 0){
        $status['state'] = 'stopped';
    }elseif(stripos($status['docker_status'], 'Created') === 0){
        $status['state'] = 'created';
    }else{
        $status['state'] = 'present';
    }

    return $status;
}

function dockerlab_state_text($state){
    $map = array(
        'running' => '运行中',
        'stopped' => '已停止',
        'created' => '已创建',
        'present' => '已存在',
        'not_created' => '未创建',
        'docker_unavailable' => 'Docker 不可用',
        'label_mismatch' => '标签不匹配',
        'error' => '状态异常',
        'unknown' => '未知'
    );
    return isset($map[$state]) ? $map[$state] : '未知';
}
?>
