<html xmlns:display="http://www.w3.org/1999/xhtml">
<head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="./Admin.js"></script>
</head>
<?php
session_start(); //Запускаем сессии
/**
 * Класс для авторизации
 */
class AuthClass {
    //private $_login = "demo"; //Устанавливаем логин
    //private $_password = "demo"; //Устанавливаем пароль

    /**
     * Проверяет, авторизован пользователь или нет
     * Возвращает true если авторизован, иначе false
     * @return boolean
     */
    public function isAuth() {
        if (isset($_SESSION["is_auth"])) { //Если сессия существует
            return $_SESSION["is_auth"]; //Возвращаем значение переменной сессии is_auth (хранит true если авторизован, false если не авторизован)
        }
        else return false; //Пользователь не авторизован, т.к. переменная is_auth не создана
    }

    /**
     * Авторизация пользователя
     * @param string $login
     * @param string $passwors
     */
    public function auth($login, $passwors) {
        $sql= "SELECT id from adminusers WHERE name='".$login."' AND password='".MD5($passwors)."'";
        //echo $sql;
        $ini_array = parse_ini_file("options.ini");
        $link = mysqli_connect($ini_array["url"], $ini_array["user"], $ini_array["password"], $ini_array["database"]);
        if (!$link) {
            echo "Error: Impossible connect to MySQL." . PHP_EOL;
            echo "Error Code: " . mysqli_connect_errno() . PHP_EOL;
            echo "Details of error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
        $select=mysqli_query($link,$sql);
        if ($select->num_rows > 0) {
            $_SESSION["is_auth"] = true; //Делаем пользователя авторизованным
            $_SESSION["login"] = $login; //Записываем в сессию логин пользователя
            mysqli_close($link);
            return true;
        }
        /*if ($login == $this->_login && $passwors == $this->_password) { //Если логин и пароль введены правильно
            $_SESSION["is_auth"] = true; //Делаем пользователя авторизованным
            $_SESSION["login"] = $login; //Записываем в сессию логин пользователя
            return true;
        }*/
        else { //Логин и пароль не подошел
            $_SESSION["is_auth"] = false;
            mysqli_close($link);
            return false;
        }
    }

    /**
     * Метод возвращает логин авторизованного пользователя
     */
    public function getLogin() {
        if ($this->isAuth()) { //Если пользователь авторизован
            return $_SESSION["login"]; //Возвращаем логин, который записан в сессию
        }
    }
    public function out() {
        $_SESSION = array(); //Очищаем сессию
        session_destroy(); //Уничтожаем
    }
}

$auth = new AuthClass();

if (isset($_POST["login"]) && isset($_POST["password"])) { //Если логин и пароль были отправлены
    if (!$auth->auth($_POST["login"], $_POST["password"])) { //Если логин и пароль введен не правильно
        echo "<h2 style=\"color:red;\">Login or password is not correct!</h2>";
    }
}
if (isset($_GET["is_exit"])) { //Если нажата кнопка выхода
    if ($_GET["is_exit"] == 1) {
        $auth->out(); //Выходим
        header("Location: ?is_exit=0"); //Редирект после выхода
    }
}
?>
<?php if ($auth->isAuth()) { // Если пользователь авторизован, приветствуем:
    //echo "Hello, " . $auth->getLogin() ;
    if (isset($_POST["usersp"])) { //Choosen User
        if ($_POST["usersp"]=="user")
        header('Location: ./users.php');
        else header('Location: ./sps.php');
    } else {
        session_destroy();
        header('Location: ./index.php');
    }
    //echo "<br/><br/><a href=\"?is_exit=1\">Quit</a>"; //Показываем кнопку выхода
}
else { //Если не авторизован, показываем форму ввода логина и пароля
    ?>
 <!--   <form method="post" action="">
        Login: <input type="text" name="login" value="<?php echo (isset($_POST["login"])) ? $_POST["login"] : null; ?>" /><br/>
        Password: <input type="password" name="password" value="" /><br/>
        <input type="submit" value="Enter" />
    </form> -->
    <link rel="stylesheet" href="./style.css" type="text/css"/>
    <form class="transparent" method="post" action="">
        <div class="form-inner">
            <h3>Sign in</h3>
            <label for="login">User name</label>
            <input type="text" name="login" id="login" value="<?php echo (isset($_POST["login"])) ? $_POST["login"] : null; ?>" /><br/>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" value="" checked>
            <div>
            <input name="usersp" type="radio" id ="radio1" value="user" />
            <label for="radio1">User</label>
            </div>
            <br>
            <div>
            <input name="usersp" type="radio" id="radio2" value="sp" />
            <label for="radio2">Service Provider</label>
            </div>
            <input type="submit" value="Enter">
        </div>
    </form>
<?php } ?>