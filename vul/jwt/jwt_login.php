<?php
/**
 * Created by runner.han
 * There is nothing new under the sun
 */

$PIKA_ROOT_DIR = "../../";

include_once $PIKA_ROOT_DIR . 'inc/config.inc.php';
include_once $PIKA_ROOT_DIR . 'inc/mysql.inc.php';
include_once $PIKA_ROOT_DIR . 'inc/function.php';

$ACTIVE = array_fill(0, 150, '');
$ACTIVE[122] = 'active open';
$ACTIVE[124] = 'active';

$link = connect();
$html = '';

if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    jwt_logout();
    header('location:jwt_login.php');
    exit();
}

if (isset($_POST['submit'])) {
    if ($_POST['username'] != null && $_POST['password'] != null) {
        $username = escape($link, $_POST['username']);
        $password = escape($link, $_POST['password']);
        $query = "select * from users where username='$username' and password=md5('$password')";
        $result = execute($link, $query);
        if (mysqli_num_rows($result) == 1) {
            $data = mysqli_fetch_assoc($result);
            $role = $data['level'] == 1 ? 'admin' : 'user';
            $payload = array(
                'username' => $data['username'],
                'level' => $data['level'],
                'role' => $role,
                'iat' => time()
            );
            $token = jwt_create_token($payload);
            setcookie('jwt_token', $token, time() + 36000, '/');
            $html .= "<p class='notice'>登录成功，服务端已下发 jwt_token Cookie。</p>";
            $html .= "<p class='notice'>当前 Token：<textarea style='width:100%;height:90px;'>" . htmlspecialchars($token) . "</textarea></p>";
            $html .= "<p class='notice'><a href='jwt_admin.php'>进入 JWT 管理后台</a></p>";
        } else {
            $html .= "<p class='notice'>登录失败，用户名或密码错误。</p>";
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
                    <a href="jwt.php">JWT</a>
                </li>
                <li class="active">JWT 登录页</li>
            </ul><!-- /.breadcrumb -->
            <a href="#" style="float:right" data-container="body" data-toggle="popover" data-placement="bottom" title="tips(提示)"
               data-content="先用 pikachu/000000 登录拿到 token，再尝试修改 alg、role、level 等字段，观察 jwt_admin.php 的权限判断。">
                点一点提示
            </a>
        </div>
        <div class="page-content">
            <div class="op_form">
                <div class="op_form_main">
                    <h4 class="header blue lighter bigger">
                        <i class="ace-icon fa fa-coffee green"></i>
                        请输入登录信息
                    </h4>
                    <form method="post">
                        <label>
                            <span>
                                <input type="text" name="username" placeholder="Username" />
                                <i class="ace-icon fa fa-user"></i>
                            </span>
                        </label>
                        </br>
                        <label>
                            <span>
                                <input type="password" name="password" placeholder="Password" />
                                <i class="ace-icon fa fa-lock"></i>
                            </span>
                        </label>
                        <div class="space"></div>
                        <div class="clearfix">
                            <label><input class="submit" name="submit" type="submit" value="Login" /></label>
                            <a style="margin-left:15px;" href="jwt_login.php?logout=1">清除当前 Token</a>
                        </div>
                    </form>
                    <p class="notice">演示账号：admin / 123456，pikachu / 000000</p>
                    <?php echo $html; ?>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
