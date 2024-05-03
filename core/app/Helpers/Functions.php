<?php 

function trimPhone($phone) : string {
    $length = strlen($phone);

    $phone = substr($phone, ($length - 9), 9);

    return $phone;
}

function isPhoneOrEmail($input){
    $input = str_replace(' ', '', $input); // remove any spaces
    // Check if input matches phone number pattern
    $phoneNumberPattern = '/^(09|9|2519|\+2519)[0-9]{8}$/';
    if (preg_match($phoneNumberPattern, $input)) {
        return 'phone';
    }
    // Check if input matches email pattern
    if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
        return 'email';
    }
    return 'invalid';
}