<?php

function readCustomers($filename) {
    $customers = array();
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        $fields = explode(";", $line);
        $id = trim($fields[0]);
        $customers[$id] = array(
            'id'         => $id,
            'firstName'  => trim($fields[1]),
            'lastName'   => trim($fields[2]),
            'email'      => trim($fields[3]),
            'university' => trim($fields[4]),
            'address'    => trim($fields[5]),
            'city'       => trim($fields[6]),
            'state'      => trim($fields[7]),
            'country'    => trim($fields[8]),
            'zip'        => trim($fields[9]),
            'phone'      => trim($fields[10]),
            'sales'      => trim($fields[11])
        );
    }
    
    return $customers;
}

function readOrders($customer, $filename) {
    $orders = array();
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        $fields = explode(",", $line);
        $custId = trim($fields[1]);
        
        if ($custId == $customer) {
            $orders[] = array(
                'orderId'  => trim($fields[0]),
                'custId'   => $custId,
                'isbn'     => trim($fields[2]),
                'title'    => trim($fields[3]),
                'category' => trim($fields[4])
            );
        }
    }
    
    return $orders;
}

?>
