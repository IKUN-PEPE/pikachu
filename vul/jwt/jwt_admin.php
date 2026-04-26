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

if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    jwt_logout();
    header('location:jwt_login.php');
    exit();
}

$link = connect();
$payload = false;
$username = 'unknown';
$role = '';
$level = 0;

if (isset($_COOKIE['jwt_token'])) {
    $payload = jwt_decode_insecure($_COOKIE['jwt_token']);
}

if (is_array($payload)) {
    $username = isset($payload['username']) ? $payload['username'] : 'unknown';
    $role = isset($payload['role']) ? $payload['role'] : '';
    $level = isset($payload['level']) ? intval($payload['level']) : 0;
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
                <li class="active">JWT 管理后台</li>
            </ul><!-- /.breadcrumb -->
            <a href="#" style="float:right" data-container="body" data-toggle="popover" data-placement="bottom" title="tips(提示)"
               data-content="本页仍然直接信任 Token 里的 role 和 level claim。这里增加的默认值保护只用于避免字段缺失时产生 Notice，不会改变靶场漏洞逻辑。">
                点一点提示
            </a>
        </div>
        <div class="page-content">
            <?php if (!$payload) { ?>
                <p class="notice">没有可用的 Token，请先前往 <a href="jwt_login.php">jwt_login.php</a> 登录。</p>
            <?php } elseif ($role != 'admin' && $level != 1) { ?>
                <p class="notice">当前 Token 未被识别为管理员。</p>
                <p class="notice">当前用户：<?php echo htmlspecialchars($username); ?> | role=<?php echo htmlspecialchars($role); ?> | level=<?php echo htmlspecialchars($level); ?></p>
                <p class="notice">本页仍然仅根据 Token claim 做权限判断，这是保留的教学漏洞点。</p>
                <p class="notice"><a href="jwt_login.php">返回登录页</a></p>
            <?php } else { ?>
                <div id="admin_left">
                    <p class="left_title">JWT 管理后台</p>
                    <ul>
                        <li><a href="jwt_admin.php">Dashboard</a></li>
                    </ul>
                </div>

                <div id="admin_main">
                    <p class="admin_title">你好，<?php echo htmlspecialchars($username); ?> | <a style="color:blue;" href="jwt_admin.php?logout=1">退出登录</a></p>
                    <p class="notice">当前 Token role=<?php echo htmlspecialchars($role); ?>，level=<?php echo htmlspecialchars($level); ?>。访问控制仍然只依赖这些 claim。</p>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>username</td>
                            <td>sex</td>
                            <td>phone</td>
                            <td>email</td>
                            <td>address</td>
                        </tr>
                        <?php
                        $query = "select * from member";
                        $result = execute($link, $query);
                        while ($data = mysqli_fetch_assoc($result)) {
                            $member_username = htmlspecialchars($data['username']);
                            $sex = htmlspecialchars($data['sex']);
                            $phonenum = htmlspecialchars($data['phonenum']);
                            $email = htmlspecialchars($data['email']);
                            $address = htmlspecialchars($data['address']);
                            $html = <<<A
    <tr>
        <td>{$member_username}</td>
        <td>{$sex}</td>
        <td>{$phonenum}</td>
        <td>{$email}</td>
        <td>{$address}</td>
    </tr>
A;
                            echo $html;
                        }
                        ?>
                    </table>
                </div>
            <?php } ?>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
