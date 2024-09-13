<?php
// ばしょ：/usr/share/nginx/html/index.php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['agree'] == 1) {

    // クライアントのIPアドレスを取得
    $client_ip = $_SERVER['REMOTE_ADDR'];

    // ARPテーブルからMACアドレスを取得
    $arp = `/usr/sbin/arp -n $client_ip`;
    $lines = explode("\n", $arp);

    // ARPの結果からMACアドレスを抽出
    foreach ($lines as $line) {
        if (strpos($line, $client_ip) !== false) {
            $cols = preg_split('/\s+/', trim($line));
            $mac_address = $cols[2]; // MACアドレスは通常第3列
            echo "あなたのMACアドレス: " . $mac_address;
            break;
        }
    }

    shell_exec("sudo /sbin/iptables -t nat -I PREROUTING 1 -i eth1 -m mac --mac-source $mac_address -j ACCEPT");


echo <<<EOM
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta charset="UTF-8">
<div align=center>
<h1>CAPTIVE PORTAL</h1>
<hr>
認証が完了しました
EOM;


    exit();
}

?>



<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta charset="UTF-8">
<div align=center>
<?php
var_dump($_POST);
?>
        <h1>CAPTIVE PORTAL</h1>
        <hr>
<p>同意しちゃいますか？</p>
<form action="" method="POST">
        <input type="hidden" name="agree" value="1">
        <input type="submit" value="　　　同意する　　　">
</form>
</div>
