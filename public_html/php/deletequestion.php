<?php

session_start();

require 'db.php';

$data=json_decode(file_get_contents('php://input'),true);
$user= mysqli_fetch_assoc(mysqli_query($db, 'SELECT id,login,email,administrator,curator,teacher,student FROM users WHERE login=\''.$data['user']['login'].'\' AND password=\''.$data['user']['password'].'\''));
if($user['administrator']==0 and $user['curator']==0 and $user['teacher']==0 and $user['student']==0){
    exit(FALSE);
}

//удаляю варианты
$res= mysqli_query($db, 'SELECT * FROM variants WHERE id_question='.$data['id'].'');
while($raw_result= mysqli_fetch_assoc($res)){
    $r=mysqli_query($db,'DELETE FROM text WHERE id_text='.$raw_result['id_text'].'');
}
$res=mysqli_query($db,'DELETE FROM variants WHERE id_question='.$data['id'].'');

//удаляю вопрос
$res= mysqli_fetch_assoc(mysqli_query($db,'SELECT * FROM questions WHERE id='.$data['id'].''));
$res= mysqli_query($db, 'DELETE FROM text WHERE id_text='.$res['id_text'].'');
$res=mysqli_query($db,'DELETE FROM questions WHERE id='.$data['id']);

$res=mysqli_query($db,'SELECT * FROM questions WHERE id_test='.$data['test_id'].' ORDER BY number');
$i=1;
while($raw_result= mysqli_fetch_assoc($res)){
    $r=mysqli_query($db,'UPDATE questions SET number='.$i.' WHERE id='.$raw_result['id'].'');
    $i++;
}

exit();

?>

