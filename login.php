<?php
function loginUser($conn, $username, $password){

    $sql = "SELECT * FROM tbl_users WHERE username=? AND password=?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        error_log("Statement prepare error: " . mysqli_error($conn));
        return false;
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Statement execute error: " . mysqli_error($conn));
        return false;
    }

    $result = mysqli_stmt_get_result($stmt);
    
    if (!$result) {
        error_log("Get result error: " . mysqli_error($conn));
        return false;
    }

    if($user = mysqli_fetch_assoc($result)){
        return $user;
    }else{
        error_log("No user found for username: $username");
        return false;
    }
}
?>