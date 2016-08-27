<?php
error_reporting(0);
function pesan($value){
        echo "[IDTookit] ".$value."\r\n";
}
function idtoolkit(){
    $update = json_decode(file_get_contents("https://bug7sec.github.io/idtoolkit/update.json"),true);
    mkdir("IDTookit");
    foreach ($update['required']['dir'] as $value) {
        pesan("Generate Dir : ".$value);
        mkdir("IDTookit/".$value);
    }
    foreach ($update['required']['file'] as $value) {
        pesan("Download : ".$value);
        file_put_contents("IDTookit/".$value, file_get_contents($update['url'].$value));
    }
    echo "DONE!!";
}
idtoolkit();
?>
