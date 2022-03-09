<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/eshop/core/init.php';

    $full_name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $street = sanitize($_POST['street']);
    $street2 = sanitize($_POST['street2']);
    $city = sanitize($_POST['city']);
    $state = sanitize($_POST['state']);
    $zip_code = sanitize($_POST['zip_code']);
    $errors = array();
    $required = array(
        'full_name'     => 'Celé Meno',
        'email'         => 'Email',
        'street'        => 'Ulica',
        'city'          => 'Mesto',
        'state'         => 'Štát',
        'zip_code'      => 'Smerovacie Číslo'
    );

    // check if all required fields are filled out
    foreach($required as $f => $d){
        if(empty($_POST[$f]) || $_POST[$f] == ''){
            $errors[] = 'Pole '.$d.' je požadované.';
        }
    }
    //chech if valid email Address
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = 'Prosím Vás, zadajte platný email.';
    }

    $data = array(
        'errors' => $errors,
        'success' => "Schválené!"
    );

    echo json_encode($data);



?>