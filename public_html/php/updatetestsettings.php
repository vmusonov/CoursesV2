<?php

session_start();

require 'db.php';

$data=json_decode(file_get_contents('php://input'),true);

$data['user']['login']= mysqli_real_escape_string($db,$data['user']['login']);
$data['user']['password']= mysqli_real_escape_string($db,$data['user']['password']);

$data['data']['active']= mysqli_real_escape_string($db,$data['data']['active']);
$data['data']['mix_q']= mysqli_real_escape_string($db,$data['data']['mix_q']);
$data['data']['mix_var']= mysqli_real_escape_string($db,$data['data']['mix_var']);
$data['data']['reload']= mysqli_real_escape_string($db,$data['data']['reload']);
$data['data']['for_course_id']= mysqli_real_escape_string($db,$data['data']['for_course_id']);
$data['data']['reload_try']= mysqli_real_escape_string($db,$data['data']['reload_try']);
$data['data']['can_pass']= mysqli_real_escape_string($db,$data['data']['can_pass']);
$data['data']['display_q']= mysqli_real_escape_string($db,$data['data']['display_q']);
$data['data']['threshold']= mysqli_real_escape_string($db,$data['data']['threshold']);
$data['data']['minute_on_pass']= mysqli_real_escape_string($db,$data['data']['minute_on_pass']);
$data['data']['id']= mysqli_real_escape_string($db,$data['data']['id']);
$data['data']['curator_id']= mysqli_real_escape_string($db,$data['data']['curator_id']);

$user= mysqli_fetch_assoc(mysqli_query($db, 'SELECT id,login,email,administrator,curator,teacher,student FROM users WHERE login=\''.$data['user']['login'].'\' AND password=\''.$data['user']['password'].'\''));
if($user['administrator']==0 and $user['curator']==0 and $user['teacher']==0){
    exit(FALSE);
}

$visit=date('Y-m-d H:i:s',time());
mysqli_query($db,'UPDATE users SET last_visit=\''.$visit.'\' WHERE login=\''.$data['user']['login'].'\'');

$filter='';
if($user['curator']==1){
    $filter=' AND id IN (SELECT id_test FROM curator_test WHERE id_curator='.$user['id'].')';
}
if($user['teacher']==1){
    $filter=' AND id IN (SELECT id FROM tests WHERE for_course_id IN (SELECT id_course FROM teacher_course WHERE id_teacher='.$user['id'].'))';
}

$query='UPDATE tests SET active='.$data['data']['active'].
        ', mix_q='.$data['data']['mix_q'].
        ', mix_var='.$data['data']['mix_var'].
        ', reload='.$data['data']['reload'].
        ', for_course_id='.$data['data']['for_course_id'].
        ', reload_try='.$data['data']['reload_try'].
        ', can_pass='.$data['data']['can_pass'].
        ', display_q='.$data['data']['display_q'].
        ', threshold='.$data['data']['threshold'].
        ', minute_on_pass='.$data['data']['minute_on_pass'].' WHERE id='.$data['data']['id'].''.$filter.'';

error_log($query);

$res=mysqli_query($db,$query);

mysqli_query($db,'DELETE FROM curator_test WHERE id_test='.$data['data']['id'].'');
mysqli_query($db,'INSERT INTO curator_test(id_curator,id_test) VALUES('.$data['data']['curator_id'].','.$data['data']['id'].')');

exit();

?>

