<?php
    session_start();
    
    require 'db.php';
    
    $data=json_decode(file_get_contents('php://input'),true);
    $user= mysqli_fetch_assoc(mysqli_query($db, 'SELECT id,login,email,administrator,curator,teacher,student FROM users WHERE login=\''.$data['user']['login'].'\' AND password=\''.$data['user']['password'].'\''));
    if($user['administrator']==0 and $user['curator']==0){
        exit(FALSE);
    }
    
    if($user['administrator']==1){
        $res= mysqli_query($db, 'DELETE FROM teacher_course WHERE id_teacher='.$data['teacher_id'].' AND id_course='.$data['course_id'].'');
    }
    if($user['curator']==1){
        $res= mysqli_query($db, 'DELETE FROM teacher_course WHERE id_teacher='.$data['teacher_id'].' AND id_course='.$data['course_id'].' AND id_teacher IN '
                . '(SELECT id_teacher FROM curator_teacher WHERE id_curator='.$user['id'].'');
    }
    
    exit();
    
?>

