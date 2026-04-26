<?php
/**
 * Created by runner.han
 * There is nothing new under the sun
 */

$ACTIVE = array_fill(0, 150, '');
$ACTIVE[132] = 'active open';

$PIKA_ROOT_DIR = "../../";
include_once $PIKA_ROOT_DIR . 'header.php';
?>

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="cors.php">CORS Misconfiguration</a>
                </li>
                <li class="active">Attacker Template</li>
            </ul>
            <a href="#" style="float:right" data-container="body" data-toggle="popover" data-placement="bottom" title="tips(提示)"
               data-content="如果这个页面仍然从 Pikachu 同源打开，浏览器不会真的进入跨源拦截流程。把页面代码复制到其他端口或域名，再测试才是完整复现。">
                点一点提示
            </a>
        </div>
        <div class="page-content">
            <div class="vul info">
                <p>攻击者页面模板</p>
                <p>只有从<strong>不同 Origin</strong> 打开这个页面，浏览器才会真正执行 CORS 校验。当前文件放在 Pikachu 站内主要是为了预览模板和调试脚本。</p>
                <p>如果你直接从 Pikachu 同源打开它，这里只是模板预览；真正测试时，请把这段页面逻辑复制到其他端口或其他域名运行。</p>
                <p>例如：<code>http://127.0.0.1:8081/cors_attacker.php</code> 或 <code>http://evil.local/cors_attacker.php</code>。</p>
                <p>当前页面 Origin：<code id="current_origin">loading...</code></p>

                <p>
                    API 地址：
                    <input class="input_text" id="api_url" type="text" style="width:540px;" value="http://127.0.0.1/pikachu/vul/cors/cors_api.php?scenario=reflect" />
                </p>
                <p>
                    <input class="submit" type="button" id="test_reflect" value="测试 Origin 反射" />
                    <input class="submit" type="button" id="test_credential" value="测试 Credential 场景" style="margin-left:10px;" />
                </p>

                <div class="host_main">
                    <p class="notice">说明：Credential 场景会使用 <code>credentials: 'include'</code> 发起请求。</p>
                    <p class="notice">请求目标：<code id="target_url">尚未发起请求</code></p>
                    <p class="notice">跨源状态：<span id="origin_note">等待测试</span></p>
                    <p class="notice">返回内容：</p>
                    <textarea id="result_body" style="width:100%;height:180px;" readonly></textarea>
                    <p class="notice">错误信息：</p>
                    <textarea id="error_text" style="width:100%;height:120px;" readonly></textarea>
                </div>

<pre style="width:100%;margin-top:20px;">
提示：
1. 先在 Pikachu 里预览这个模板，确认目标接口地址是否正确；
2. 再把同样的 HTML/JS 复制到其他端口或其他域名；
3. 打开浏览器开发者工具，观察请求头里的 Origin 以及响应头里的 Access-Control-Allow-Origin / Access-Control-Allow-Credentials。
</pre>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<script type="text/javascript">
(function () {
    var currentOrigin = document.getElementById('current_origin');
    var apiInput = document.getElementById('api_url');
    var targetOutput = document.getElementById('target_url');
    var originNote = document.getElementById('origin_note');
    var resultBody = document.getElementById('result_body');
    var errorText = document.getElementById('error_text');

    currentOrigin.textContent = window.location.origin;

    function buildTarget(mode) {
        var raw = apiInput.value.trim();
        if (raw === '') {
            return '';
        }

        try {
            var url = new URL(raw, window.location.href);
            url.searchParams.set('scenario', mode === 'credential' ? 'credential' : 'reflect');
            return url.toString();
        } catch (e) {
            return raw;
        }
    }

    function updateOriginNote(target) {
        try {
            var targetOrigin = new URL(target, window.location.href).origin;
            if (targetOrigin === window.location.origin) {
                originNote.textContent = '当前仍是同源预览，不会真正触发浏览器的 CORS 拦截流程。';
            } else {
                originNote.textContent = '当前已经是不同 Origin，浏览器会真正执行 CORS 检查。';
            }
        } catch (e) {
            originNote.textContent = '目标地址无法解析，请检查输入。';
        }
    }

    async function runScenario(mode) {
        var target = buildTarget(mode);
        targetOutput.textContent = target || '目标地址为空';
        resultBody.value = '';
        errorText.value = '';
        updateOriginNote(target);

        if (target === '') {
            errorText.value = '请先输入有效的 API 地址。';
            return;
        }

        var options = {
            method: 'GET',
            mode: 'cors'
        };

        if (mode === 'credential') {
            options.credentials = 'include';
        }

        try {
            var response = await fetch(target, options);
            var text = await response.text();
            resultBody.value = text;
            errorText.value = 'HTTP ' + response.status + ' ' + response.statusText;
        } catch (error) {
            errorText.value = error.message;
        }
    }

    document.getElementById('test_reflect').addEventListener('click', function () {
        runScenario('reflect');
    });

    document.getElementById('test_credential').addEventListener('click', function () {
        runScenario('credential');
    });
})();
</script>

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
