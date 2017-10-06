<?php

function query($query, $verbose=false){
    global $mysqli;
    if($verbose){ echo $query."\n"; }
    $result = $mysqli->query($query);
    if($result){
        return $mysqli->affected_rows;
    } else { 
        if($verbose){ echo "(" . $mysqli->errno . ") " . $mysqli->error."\n"; }
        return -1;
    }
}

function booleanQuery($query, $verbose=false){
    global $mysqli;
    if($verbose){ echo $query."\n"; }
    $result = $mysqli->query($query);
    if($result){
        return true;
    } else { 
        if($verbose){ echo "(" . $mysqli->errno . ") " . $mysqli->error."\n"; }
        return false;
    }
}

function insertQuery($query, $verbose=false){
    global $mysqli;
    if($verbose){ echo $query."\n"; }
    $result = $mysqli->query($query);
    if($result){
        if($mysqli->affected_rows > 0){
            return $mysqli->insert_id;
        } else { return 0; }
    } else { 
        if($verbose){ echo "(" . $mysqli->errno . ") " . $mysqli->error."\n"; }
        return -1;
    }
}

function selectQuery($query, $verbose=false){
    global $mysqli;
    if($verbose){ echo $query."\n"; }
    $result = $mysqli->query($query);
    if($result){
        if($result->num_rows > 0){
            $tuple = $result->fetch_array();
            return $tuple;
        } else { return 0; }
    } else { 
        if($verbose){ echo "(" . $mysqli->errno . ") " . $mysqli->error."\n"; }
        return -1;
    }
}

function selectArrayQuery($query, $verbose=false){
    global $mysqli;
    if($verbose){ echo $query."\n"; }
    $result = $mysqli->query($query);
    if($result){
        if($result->num_rows > 0){
            $return_array = array();
            while($tuple = $result->fetch_array()){
                array_push($return_array, $tuple);
            }
            if($verbose){ echo "size: ".count($return_array)."\n"; }
            return $return_array;
        } else { return 0; }
    } else { 
        if($verbose){ echo "(" . $mysqli->errno . ") " . $mysqli->error."\n"; }
        return -1;
    }
}

?>