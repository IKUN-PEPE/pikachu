<?php
/**
 * Created by runner.han
 * There is nothing new under the sun
 */

include_once 'inc/config.inc.php';

$ACTIVE = array_fill(0, 150, '');
$ACTIVE[0] = 'active open';

include 'header.php';

$html = '';
$link = @mysqli_connect(DBHOST, DBUSER, DBPW, DBNAME, DBPORT);
if(!$link){
    $html .= "<p><a href='install.php' style='color:red;'>提示：欢迎使用 Pikachu，系统尚未初始化，点击这里完成安装。</a></p>";
}else{
    @mysqli_set_charset($link, 'utf8');
    @mysqli_close($link);
}

?>

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li class="active">系统介绍</li>
            </ul>
        </div>
        <div class="page-content">
            <?php echo $html; ?>
            <div id="intro_main">
                <p class="p1">
                    Pikachu 是一个用于 Web 安全学习与漏洞练习的教学平台。它保留了故意设计的脆弱逻辑，方便在本地环境中观察漏洞成因、利用路径和错误实现方式，因此适合入门练习、课堂演示和自测复现。
                </p>

                <h2 class="v_title">Pikachu 当前包含的漏洞模块</h2>
                <ul class="vul_list_info">
                    <li>暴力破解</li>
                    <li>XSS</li>
                    <li>CSRF</li>
                    <li>SQL 注入</li>
                    <li>RCE</li>
                    <li>文件包含</li>
                    <li>不安全下载</li>
                    <li>不安全上传</li>
                    <li>越权</li>
                    <li>目录遍历</li>
                    <li>信息泄露</li>
                    <li>PHP 反序列化</li>
                    <li>XXE</li>
                    <li>URL 重定向</li>
                    <li>SSRF</li>
                    <li>JWT</li>
                    <li>Host Header</li>
                    <li>Session Fixation</li>
                    <li>CORS Misconfiguration</li>
                    <li>Clickjacking</li>
                </ul>

                <p class="p3">
                    每个模块通常会继续拆分为多个小场景，用来演示同一类漏洞在不同上下文中的表现方式。页面右上角的提示按钮会给出简短引导，帮助你快速进入练习状态，但不会替代实际分析过程。
                </p>

                <h2>如何安装和使用</h2>
                <p>
                    Pikachu 基于 PHP 和 MySQL 构建，适合部署在本地测试环境中。你只需要准备好 Web 服务和数据库环境，然后把项目放到站点目录下，按实际情况修改 <code>inc/config.inc.php</code> 中的数据库连接配置，再访问首页完成初始化即可。
                    <br />
                    --> 将下载后的 <code>pikachu</code> 目录放到 Web 服务根目录下；
                    <br />
                    --> 根据实际环境修改 <code>inc/config.inc.php</code> 中的数据库账号、密码和端口；
                    <br />
                    --> 访问 <code>http://127.0.0.1/pikachu/</code>，如出现安装提示，点击后完成初始化。
                </p>

                <p class="p4">
                    这个项目的目标是帮助学习漏洞原理，而不是提供生产级安全实现。请始终在合法、授权、隔离的测试环境中使用它，并结合抓包、日志和源码阅读去理解每个场景的行为。
                </p>

                <h2>提示</h2>
                <p>
                    少就是多，慢就是快。先理解漏洞为什么成立，再去尝试利用与修复。
                </p>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include 'footer.php';
?>
