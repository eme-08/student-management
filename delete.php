<?php
function deleteStudent($conn,$id){
$stmt=mysqli_prepare($conn,"DELETE FROM tbl_student WHERE student_id=?");
mysqli_stmt_bind_param($stmt,"i",$id);
if(mysqli_stmt_execute($stmt)){
    $user_stmt = mysqli_prepare($conn, "DELETE FROM tbl_users WHERE user_id=?");
    if($user_stmt) {
        mysqli_stmt_bind_param($user_stmt, "i", $id);
        mysqli_stmt_execute($user_stmt);
        mysqli_stmt_close($user_stmt);
    }
    return true;
}
return false;
}
?>