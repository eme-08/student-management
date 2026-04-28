<?php
function getStudents($conn, $id = null){
if($id) {
    $stmt=mysqli_prepare($conn,"SELECT s.*, u.username, u.password FROM tbl_student s LEFT JOIN tbl_users u ON s.student_id = u.user_id WHERE s.student_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result=mysqli_stmt_get_result($stmt);
} else {
    $result=mysqli_query($conn,"SELECT s.*, u.username, u.password FROM tbl_student s LEFT JOIN tbl_users u ON s.student_id = u.user_id");
}
$data=[];
while($row=mysqli_fetch_assoc($result)){
$data[]=$row;
}
return $data;
}
?>