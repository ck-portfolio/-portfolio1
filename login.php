<?php

// セッション開始
session_start();

$db['host'] = "mysql115.phy.lolipop.lan";  // DBサーバのURL
$db['user'] = "LAA0740279";  // ユーザー名
$db['pass'] = "2eEatINC";  // ユーザー名のパスワード
$db['dbname'] = "LAA0740279-zt5jmk";  // データベース名

// エラーメッセージの初期化
$errorMessage = "";

// ログインボタンが押された場合
if (isset($_POST["login"])) {
    // 1. ユーザIDの入力チェック
    if (empty($_POST["userid"])) {  // emptyは値が空のとき

        $errorMessage = 'ユーザーIDが未入力です。';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'パスワードが未入力です。';
    }

    if (!empty($_POST["userid"]) && !empty($_POST["password"])) {
        // 入力したユーザIDを格納
        $userid = $_POST["userid"];

        // 2. ユーザIDとパスワードが入力されていたら認証する
        $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare('SELECT * FROM userData WHERE id = ?');
            $stmt->execute(array($userid));
          
            $password = $_POST["password"];

            foreach($stmt as $row){

                    if (password_verify($password, $row['password'])) {
                        session_regenerate_id(true);

                        // 入力したIDのユーザー名を取得
                        $id = $row['id'];
                        $sql = "SELECT * FROM userData WHERE id = $id";  //入力したIDからユーザー名を取得
                        $stmt = $pdo->query($sql);
                        foreach ($stmt as $row) {
                            $row['name'];  // ユーザー名
                        }
                        $_SESSION["NAME"] = $row['name'];
                        header("Location: index.php");  // メイン画面へ遷移
                        exit();  // 処理終了
                    } else {
                        // 認証失敗
                        $errorMessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
                    }               
                }
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            //$errorMessage = $sql;
            // $e->getMessage() （デバッグ用）
            // echo $e->getMessage();
        }
    }
}
?>

<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ログイン</title>
        <link rel="stylesheet" href="style.css">

    </head>
    <body>
        <header>
            <div class="header-font">
                <h1>ログイン画面</h1>
            </div>
        </header>

        <div class="main">
            <div class="login-container">
                <form id="loginForm" name="loginForm" action="" method="POST">
                    <fieldset>
                        <legend>ログインフォーム</legend>
                        <div><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></div>
                        <label for="userid">ユーザーID</label>
                        <input type="text" id="userid" class="login-input" name="userid" value="<?php if (!empty($_POST["userid"])) {echo htmlspecialchars($_POST["userid"], ENT_QUOTES);} ?>">
                        <br>
                        <label for="password">パスワード</label>
                        <input type="password" id="password" class="login-input" name="password" value="">
                        <br>
                        <input type="submit" id="login" name="login" value="ログイン">
                    </fieldset>
                </form>
                <br>
                <form action="signup.php">
                    <input type="submit" value="新規登録の方はコチラ！" class="loginpage-signup-submit">
                </form>
            </div>
        </div>
    </body>
</html>
