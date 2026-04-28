<?php
function insertStudent($conn,$student_id,$name,$course,$year,$section,$date_of_birth,$gender,$status,$address,$religion,$phone_number,$email,$username,$password){

$sql="INSERT INTO tbl_student 
(student_id,name,course,year,section,date_of_birth,gender,status,address,religion,phone_number,email,username,password)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt=mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"isssssssssssss",
$student_id,$name,$course,$year,$section,$date_of_birth,$gender,$status,$address,$religion,$phone_number,$email,$username,$password);

if(mysqli_stmt_execute($stmt)){
    $actual_id = $student_id ? $student_id : mysqli_insert_id($conn);
    
    $user_sql = "INSERT INTO tbl_users (user_id, username, password, user_type) VALUES (?, ?, ?, 'student') ON DUPLICATE KEY UPDATE username=VALUES(username), password=VALUES(password)";
    $user_stmt = mysqli_prepare($conn, $user_sql);
    if($user_stmt) {
        mysqli_stmt_bind_param($user_stmt, "iss", $actual_id, $username, $password);
        mysqli_stmt_execute($user_stmt);
    }
    
    return true;
}
return false;
}
?>