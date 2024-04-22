<?php 

function trimPhone($phone) : string {
    $length = strlen($phone);

    $phone = substr($phone, ($length - 9), 9);

    return $phone;
}