<?php
session_start();

if(isset($_SESSION['connecte'])){
    if($_SESSION['connecte'] == 1 || $_SESSION['connecte'] == 2){
        $xd='ok';
    }
}else{
    $xd='rate';
}

echo json_encode(['xd' => $xd]);
?>