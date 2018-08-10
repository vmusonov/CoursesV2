<?php
    session_start();
    
    require 'db.php';
    
    $data=json_decode(file_get_contents('php://input'),true);
    $user= mysqli_fetch_assoc(mysqli_query($db, 'SELECT id,login,email,administrator,curator,teacher,student FROM users WHERE login=\''.$data['user']['login'].'\' AND password=\''.$data['user']['password'].'\''));
    if($user['administrator']==0 and $user['curator']==0 and $user['teacher']==0 and $user['student']==0){
        exit(FALSE);
    }
    
    //$coursesf= mysqli_fetch_all(mysqli_query($db,'SELECT * FROM courses'),MYSQLI_ASSOC);
    $coursesf=[];
    $r=mysqli_query($db,'SELECT * FROM courses');
    $i=0;
    while($raw_result=mysqli_fetch_assoc($r)){
            $coursesf[$i]=$raw_result;
            $i++;
    }
    $courses=array();
    
    if($user['curator']==1){
        $res_courses=mysqli_query($db,'SELECT * FROM curator_course WHERE id_curator='.$user['id'].'');
        while($raw_result=mysqli_fetch_assoc($res_courses)){
            for($i=0;$i<count($coursesf);$i++){
                if($coursesf[$i]['id']==$raw_result['id_course']){
                    $courses[count($courses)]=$coursesf[$i];
                    break;
                }
            }
        }
    }
    else if($user['student']==1){
        $res_courses=mysqli_query($db,'SELECT * FROM user_result WHERE user_id='.$user['id'].'');
        while($raw_result=mysqli_fetch_assoc($res_courses)){
            for($i=0;$i<count(coursesf);$i++){
                if($coursesf[$i]['id']==$raw_result['id_course']){
                    $courses[count($courses)]=$coursesf[$i];
                    break;
                }
            }
        }
    }
    else if($user['teacher']==1){
        $res_courses=mysqli_query($db,'SELECT * FROM teacher_course WHERE id_teacher='.$user['id'].'');
        while($raw_result=mysqli_fetch_assoc($res_courses)){
            for($i=0;$i<count(coursesf);$i++){
                if($coursesf[$i]['id']==$raw_result['id_course']){
                    $courses[count($courses)]=$coursesf[$i];
                    break;
                }
            }
        }
    }
    else{
        $courses=$coursesf;
    }
    
    
    
    for($i=0;$i<count($courses);$i++){
        $lessons=mysqli_query($db,'SELECT * FROM lessons WHERE id_course='.$courses[$i]['id'].' ORDER BY number');
        if(mysqli_num_rows($lessons)!=0){
            $max= mysqli_num_rows($lessons);
            $j=0;
            while($j<$max){
                $courses[$i]['lessons'][$j]= mysqli_fetch_assoc($lessons);
                $j++;
            }
        }
        //$courses[$i]['lessons']=$lessons;
    }
    
    exit(json_encode($courses));
?>

