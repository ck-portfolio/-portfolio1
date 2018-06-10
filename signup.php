<?php

// セッション開始
session_start();

$db['host'] = "mysql115.phy.lolipop.lan";  // DBサーバのURL
$db['user'] = "LAA0740279";  // ユーザー名
$db['pass'] = "2eEatINC";  // ユーザー名のパスワード
$db['dbname'] = "LAA0740279-zt5jmk";  // データベース名

// エラーメッセージ、登録完了メッセージの初期化
$errorMessage = "";
$signUpMessage = "";

// ログインボタンが押された場合
if (isset($_POST["signUp"])) {
    // 1. ユーザIDの入力チェック
    if (empty($_POST["username"])) {  // 値が空のとき
        $errorMessage = 'ユーザーIDが未入力です。';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'パスワードが未入力です。';
    } else if (empty($_POST["password2"])) {
        $errorMessage = 'パスワードが未入力です。';
    }

    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && $_POST["password"] === $_POST["password2"]) {
        // 入力したユーザIDとパスワードを格納
        $username = $_POST["username"];
        $password = $_POST["password"];

        // 2. ユーザIDとパスワードが入力されていたら認証する
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare("INSERT INTO userData(name, password) VALUES (?, ?)");

            $stmt->execute(array($username, password_hash($password, PASSWORD_DEFAULT)));  // パスワードのハッシュ化
            $userid = $pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$useridに入れる

            $signUpMessage = '登録が完了しました。あなたの登録IDは '. $userid. ' です。パスワードは '. $password. ' です。';  // ログイン時に使用するIDとパスワード
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
      
	    //$e->getMessage() （デバッグ用）
            echo $e->getMessage();
        }
    } else if($_POST["password"] != $_POST["password2"]) {
        $errorMessage = 'パスワードに誤りがあります。';
    }
}
?>

<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
            <title>新規登録</title>
            <link rel="stylesheet" href="style.css">
    </head>
    <body>
         <header class="signup-header">
            <div class="header-font">
                <h1>新規登録画面</h1>
            </div>
        </header>

        <div class="main">
            <div class="signup-container">
                <form id="loginForm" name="loginForm" action="" method="POST">
                    <fieldset>
                        <legend>新規登録フォーム</legend>
                        <div><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></div>
                        <div><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></div>
                        <label for="username">ユーザー名</label>
                        <input type="text" id="username" class="login-input" name="username" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>">
                        <br>
                        <label for="password">パスワード</label>
                        <input type="password" id="password" class="login-input" name="password" value="">
                        <br>
                        <label for="password2">パスワード(確認用)</label>
                        <input type="password" id="password2" class="login-input" name="password2" value="">
                        <br>
                        <input type="submit" id="signUp" name="signUp" value="新規登録" class="signup-submit">
                    </fieldset>
                </form>
                <br>
                <form action="login.php">
                    <input type="submit" value="ログイン画面へ" class="signup-back">
                </form>
            </div>
        </div>
    </body>
</html>
