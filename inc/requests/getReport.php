<?php
    require(realpath(__DIR__ . '/../../') . '/inc/config.php');
    require(realpath(__DIR__ . '/../../') . '/inc/authenticate.php');
    require(realpath(__DIR__ . '/../../') . '/inc/functions/stewards_reports.php');
        
    if(! empty($_POST['id'])){
        $report_id = (int) $_POST['id'];
        $report_info = getStewardsReport($report_id, intval($_SESSION['user_info']['local_id']));
        if($report_info > 0){
            
            // Only allow users to use their own reports
            if($report_info['user_id'] != $_SESSION['user_info']['user_id']){
                echo 0;
                exit;
            }
            
            $report_info = removeNumericKeys($report_info);
            
            // Strip certain data
            $report_info = array_diff_key($report_info, array_flip(
                array('id', 'report_id', 'user_id', 'total_hours_paid', 'total_hours_worked', 'num_accidents', 'num_photos', 'num_workers_ssn', 'num_workers_travelers', 'pay_period_start', 'pay_period_ending')
            ));
            
            // Use current "Contractors Welfare/Pension Paid Up?" date for company/employer
            $employer_info = getEmployerByIdOrName($report_info['company_id'], escapeData($report_info['company']));
            if($employer_info > 0){                
                if(new DateTime('today') <= new DateTime($employer_info['paid_through'])){
                    $report_info['contractor_welfare_paid'] = 1;
                    $report_info['contractor_welfare_paid_to_month'] = '';
                } else {
                    $report_info['contractor_welfare_paid'] = 0;
                    $report_info['contractor_welfare_paid_to_month'] = date('n', strtotime($employer_info['paid_through']));
                }
            }
            
            // Foremen
            $report_info['foremen'] = array();
            $foremen = getStewardsReportForemen($report_id);
            if($foremen > 0){
                foreach($foremen as $foreman){
                    $foreman_data = removeNumericKeys($foreman);
                    
                    $foreman_data = array_diff_key($foreman_data, array_flip(
                        array('report_id', 'foreman_id')
                    ));
                    
                    $report_info['foremen'][] = $foreman_data;
                }
            }
            
            // Accidents
            // $report_info['accidents'] = array();
            // $accidents = getStewardsReportAccidents($report_id);
            // if($accidents > 0){
            //     foreach($accidents as $accident){
            //         $report_info['accidents'][] = removeNumericKeys($accident);
            //     }
            // }
            
            // Workers
            $report_info['workers'] = array();
            $workers = getStewardsReportWorkers($report_id);
            if($workers > 0){
                foreach($workers as $worker){
                    $worker_data = removeNumericKeys($worker);
                    
                    // Strip certain data
                    $worker_data = array_diff_key($worker_data, array_flip(
                        array('report_id', 'is_ssn', 'worker_id')
                    ));
                    
                    // Replace "month_dues_paid" with most recent value (by book number)
                    $worker_data['month_dues_paid'] = getStewardsReportWorkerMostRecentMonthDuesPaid($worker_data['book_number']);
                    
                    $report_info['workers'][] = $worker_data;
                }
            }
            
            echo json_encode(array('data' => $report_info));
            exit;
        } else {
            echo 0;
            exit;
        }
    } else {
        echo 0;
        exit;
    }
