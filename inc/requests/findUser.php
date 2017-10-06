<?php
    require('config.php');
    require('authenticate.php');
        
    if(! empty($_POST['b'])){
        $book_number = escapeData($_POST['b']);
                
        // Search by book number
        $result = selectQuery("SELECT `first_name`, `last_name`, `local_number`, `month_dues_paid`, `class` FROM `users` WHERE `username`='$book_number' LIMIT 1");
        
        // If user not found, try District Council database
        if($result <= 0){
            $url = 'https://iwdistrictcouncil.com/admin/inc/requests/findUser.php';
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('b'=>$book_number, 'auth'=>'a1095c86e052bb1402cadf7a2fd7e496') );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            
            if($output){
                $result = json_decode($output, true);
            } else {
                $result = 0;
            }
        }
        
        
        // If user was found, return information
        if($result > 0){
            
            $print  = '{';
            $print .= '"first_name": ' . json_encode($result['first_name']).', ';
            $print .= '"last_name": ' . json_encode($result['last_name']).', ';
            $print .= '"local_number": ' . json_encode($result['local_number']).', ';
            $print .= '"month_dues_paid_month": "' . ( ($result['month_dues_paid']) ? date('n', strtotime($result['month_dues_paid'])) : '').'", ';
            $print .= '"month_dues_paid_year": "' . ( ($result['month_dues_paid']) ? date('Y', strtotime($result['month_dues_paid'])) : '').'", ';
            $print .= '"type": "' . ($result['class'] ?: $result['type']).'", ';
            
            $print .= '"book_number": '.json_encode($result['username']);
            $print .= '}';
            
            echo $print;
        } else {
            print 0;
        }
    }
