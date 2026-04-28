<?php
function updateStudent($conn, $old_id, $new_id, $name, $course, $year, $section, $date_of_birth, $gender, $status, $address, $religion, $phone_number, $email, $username, $password) {
    $sql = "UPDATE tbl_student SET 
        student_id=?,name=?,course=?,year=?,section=?,date_of_birth=?,gender=?,status=?,address=?,religion=?,phone_number=?,email=?,username=?,password=?
        WHERE student_id=?";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        return false;
    }

    $bindOk = mysqli_stmt_bind_param(
        $stmt,
        "isssssssssssssi",
        $new_id,
        $name,
        $course,
        $year,
        $section,
        $date_of_birth,
        $gender,
        $status,
        $address,
        $religion,
        $phone_number,
        $email,
        $username,
        $password,
        $old_id
    );

    if (!$bindOk) {
        mysqli_stmt_close($stmt);
        return false;
    }

    $executeOk = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    if($executeOk) {
        $user_sql = "UPDATE tbl_users SET user_id=?, username=?, password=? WHERE user_id=?";
        $user_stmt = mysqli_prepare($conn, $user_sql);
        if($user_stmt) {
            mysqli_stmt_bind_param($user_stmt, "issi", $new_id, $username, $password, $old_id);
            mysqli_stmt_execute($user_stmt);
            mysqli_stmt_close($user_stmt);
        }
    }
    
    return $executeOk;
}
?>