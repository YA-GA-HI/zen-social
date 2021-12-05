<?php
foreach ($_POST as $key => $value) {
    $value = trim($value);
    $value = stripslashes($value);
    $value = htmlspecialchars($value);
    $_POST[$key] = $value ;
}