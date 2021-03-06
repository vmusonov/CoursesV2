<?php
    session_start();
    
    require 'db.php';
    
    $data=json_decode(file_get_contents('php://input'),true);
    
    $data['user']['login']= mysqli_real_escape_string($db,$data['user']['login']);
    $data['user']['password']= mysqli_real_escape_string($db,$data['user']['password']);
    
    $data['teacher_id']= mysqli_real_escape_string($db,$data['teacher_id']);
    $data['course_id']= mysqli_real_escape_string($db,$data['course_id']);

    $user= mysqli_fetch_assoc(mysqli_query($db, 'SELECT id,login,email,administrator,curator,teacher,student FROM users WHERE login=\''.$data['user']['login'].'\' AND password=\''.$data['user']['password'].'\''));
    if($user['administrator']==0 and $user['curator']==0){
        exit(FALSE);
    }
    
    $visit=date('Y-m-d H:i:s',time());
    mysqli_query($db,'UPDATE users SET last_visit=\''.$visit.'\' WHERE login=\''.$data['user']['login'].'\'');
    
    if($user['administrator']==1){
        $res= mysqli_query($db, 'DELETE FROM teacher_course WHERE id_teacher='.$data['teacher_id'].' AND id_course='.$data['course_id'].'');
    }
    if($user['curator']==1){
        $res= mysqli_query($db, 'DELETE FROM teacher_course WHERE id_teacher='.$data['teacher_id'].' AND id_course='.$data['course_id'].' AND id_teacher IN '
                . '(SELECT id_teacher FROM curator_teacher WHERE id_curator='.$user['id'].'');
    }
    
    exit();
    
?>

