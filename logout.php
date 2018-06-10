<?php
session_start();

if (isset($_SESSION["NAME"])) {
    $errorMessage = "ログアウトしました。";
} else {
    $errorMessage = "セッションがタイムアウトしました。";
}

// セッションの変数のクリア
$_SESSION = array();

// セッションクリア
@session_destroy();
?>

<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ログアウト</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <div class="header-font">
                <h1>ログアウト画面</h1>
            </div>
        </header>
        <div class="main">
            <div class="logout-container">
                <div class="logout-errormessage"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></div>
                <a href="login.php"><button>ログイン画面へ</button></a>
            </div>
        </div>
    </body>
</html>