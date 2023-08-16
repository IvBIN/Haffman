<?php
require 'autoload.php';

if(!empty($_POST['message'])){
    $text = $_POST['message'];
    $coder = new Coder($text);
    $code = $coder->encode();
    $table = $coder->getTable();
    $decode = $coder->decode($code);
}
?>

<form method="post">
    <lable>
        Введите фразу:<br>
        <textarea name="message" ></textarea><br>
    </lable>
    <input type="submit">
    
</form>
<span><?= (!empty($code))? $code: ''?></span>
<span><?= (!empty($table))? $table: ''?></span>
<span><?= (!empty($decode))? $decode: ''?></span>


