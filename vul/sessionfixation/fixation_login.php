<?php
/**
 * Created by runner.han
 * There is nothing new under the sun
 */

$PIKA_ROOT_DIR = "../../";

if(isset($_GET['sid']) && preg_match('/^[a-zA-Z0-9_-]{4,64}$/', $_GET['sid'])){
    session_id($_GET['sid']);
}

include_once $PIKA_ROOT_DIR . 'inc/config.inc.php';
include_once $PIKA_ROOT_DIR . 'inc/mysql.inc.php';
include_once $PIKA_ROOT_DIR . 'inc/function.php';

$ACTIVE = array_fill(0, 150, '');
$ACTIVE[128] = 'active open';
$ACTIVE[130] = 'active';

$link = connect();
$html = '';

if(isset($_GET['logout']) && $_GET['logout'] == '1'){
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();

    header('location:fixation_login.php');
    exit();
}

if(isset($_POST['submit'])){
    if($_POST['username'] != null && $_POST['password'] != null){
        $username = escape($link, $_POST['username']);
        $password = escape($link, $_POST['password']);
        $query = "select * from users where username='$username' and password=md5('$password')";
        $result = execute($link, $query);
        if(mysqli_num_rows($result) == 1){
            $data = mysqli_fetch_assoc($result);
            $_SESSION['sessionfixation'] = array(
                'username' => $data['username'],
                'level' => $data['level'],
                'login_time' => date('Y-m-d H:i:s')
            );
            header('location:fixation_profile.php');
            exit();
        }else{
            $html .= "<p class='notice'>login failed</p>";
        }
    }else{
        $html .= "<p class='notice'>username and password can not be empty</p>";
    }
}

$current_sid = session_id();

include_once $PIKA_ROOT_DIR . 'header.php';
?>

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="sessionfixation.php">Session Fixation</a>
                </li>
                <li class="active">Fixation Login</li>
            </ul>
            <a href="#" style="float:right" data-container="body" data-toggle="popover" data-placement="bottom" title="tips"
               data-content="Visit fixation_login.php?sid=pika123456 first, then login, and check whether the Session ID stays the same after login.">
                point
            </a>
        </div><!-- /.breadcrumb -->
        <div class="page-content">
            <div class="op_form">
                <div class="op_form_main">
                    <h4 class="header blue lighter bigger">
                        <i class="ace-icon fa fa-coffee green"></i>
                        Please Enter Your Information
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
                            <a style="margin-left:15px;" href="fixation_login.php?logout=1">logout demo session</a>
                        </div>
                    </form>
                    <p class="notice">Current Session ID: <code><?php echo htmlspecialchars($current_sid);?></code></p>
                    <p class="notice">Fixed SID example: <a href="fixation_login.php?sid=pika123456">fixation_login.php?sid=pika123456</a></p>
                    <p class="notice">Demo accounts: admin / 123456 , pikachu / 000000</p>
                    <?php echo $html;?>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php
include_once $PIKA_ROOT_DIR . 'footer.php';
?>
