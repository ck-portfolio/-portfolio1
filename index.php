<?php

// CSRF対策

session_start();

//DBへの接続
$db['host'] = "mysql115.phy.lolipop.lan";  // DBサーバのURL
$db['user'] = "LAA0740279";  // ユーザー名
$db['pass'] = "2eEatINC";  // ユーザー名のパスワード
$db['dbname'] = "LAA0740279-zt5jmk";  // データベース名

$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

try {
$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_EMULATE_PREPARES => false));
} catch (PDOException $e) {
 exit('データベース接続失敗。'.$e->getMessage());
}


// ログイン状態チェック
if (!isset($_SESSION["NAME"])) {
    header("Location: logout.php");
    exit;
}

function setToken(){
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;

}

function checkToken(){
    if(empty($_SESSION['token']) || ($_SESSION['token'] != $_POST['token'])){
        echo "不正なPOSTが行われました！";
        exit;
    }

}

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

#htmlからのPOST送信の確認
if ($_SERVER['REQUEST_METHOD'] == 'POST' && 
    isset($_POST['message']) &&
    isset($_POST['user'])) {

    checkToken();

    $message = trim($_POST['message']);
    $user = trim($_POST['user']);

    #データベースへの登録
    if( isset($_POST['regist']) ){
        $user = $_POST['user'];
        $message = $_POST['message'];

        $stmt = $pdo -> prepare("INSERT INTO posts (user, message) VALUES (:user, :message)");
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->execute();

    } 
}else {
    setToken();
}



#データベースからの取得
$sql = "SELECT * FROM posts";
$stmt = $pdo->query($sql);


?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>簡易掲示板</title>
        <link rel="stylesheet" href="style.css">

    </head>
    <body>
        <header>
            <div class="header-font">
                <h1>簡易掲示板</h1>
            </div>
            <ul class="header-menus">
                <li><a href="logout.php">ログアウト</a></li>
            </ul>
        </header>

        <div class="main">
            <div class="post-container">
                <form action="" method="post">
                    <fieldset class="post-form-container">
                        <legend>投稿フォーム</legend>
                        <label>ユーザー名</label>
                        <input type="text" name="user" class="post-input">
                        <br>

                        <label>メッセージ</label>
                        <textarea name="message" class="post-input"></textarea>
                        <br>

                        <input type="submit" value="投稿" name="regist" class="post-submit">
                        <input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
                        <span class="caution_message">※メッセージは255文字以内で入力ください。まだ日本語には対応できていません。 </span>
                    </fieldset>
                </form>

                <h2 class="post-list">投稿一覧</h2>
                <!-- テーブル内容の表示 -->
                <div class="main posts-index">
                    <div class="container">
                        <?php foreach ($stmt as $row) : ?>
                            <div class="posts-index-item">
                                <div class="post-user-name">
                                    <?php echo htmlspecialchars($row['user'], ENT_QUOTES|ENT_HTML5); ?>
                                    <?php echo '<br>'; ?>
                                    <span><?php echo htmlspecialchars($row['message'], ENT_QUOTES|ENT_HTML5); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>