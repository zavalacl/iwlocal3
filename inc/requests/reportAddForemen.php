<?php
    require(realpath(__DIR__ . '/../../') . '/inc/config.php');
    require(realpath(__DIR__ . '/../../') . '/inc/authenticate.php');
        
    if(!empty($_POST['start']) && !empty($_POST['add'])){
        $start = (int) $_POST['start'];
        $add   = (int) $_POST['add'];
        $total = $start + $add;
                
        $output = '';
        
        for($i=$start; $i<$total; $i++){
            $output .= includeToVar(HOME_ROOT . 'inc/stewards_report_form_foreman.php', array('i' => $i));
        }
        
        echo $output;
    }
    