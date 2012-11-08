<?php
error_reporting(0);
header('Content-Type: text/html; charset=UTF-8');
require_once('../../../../../wp-config.php');?>
<?php

if($_POST["show"]=="Post"){
    $post = $_POST["id"]; ?>

    <?php
    $newsletter = new MeeNewsletter($options,$data);
    echo $newsletter->generateRow($post,$_POST['showall']);
    
    die();
}else if($_POST["show"]=="onlyText"){

    $post = design::extractPost($_POST["id"]);
    echo newsletter::generateRow($post,1,$_POST['showall']);
}else if($_POST["show"]=="changeColumn"){

    $post =  design::contructDayPost($_POST["id"]);
    if($post == ""){
        echo "There arent post in this category";
    }else{
        echo $post;
    }
    echo '<p style="width:100%"><span style="float:left;width:85%">Change to show day post <a href="javascript:changepostlast('.$_POST["id"].')";>Here</a></span></p>';
    die();
 }else if($_POST["show"]=="changeAll"){
    $post =  design::contructLastPost($_POST["id"]);
    if($post == ""){
        echo "There arent post in this category";
    }else{
        echo $post;
    }
    echo '<p style="width:100%"><span style="float:left;width:85%">Change to show day post <a href="javascript:changepostday('.$_POST["id"].')";>Here</a></span></p>';
    die();
}
if($_POST["acc"]=="showUser"){
    $iduser = $_POST["id"];
    $user = MeeUsers::getUser($iduser);
    echo $user->name.",".$user->email.",".$user->company.",".$user->direction.",".$user->id_categoria;
    die();
}

if($_POST["show"]=="GButtons"){
    $idNewsletter = $_POST["idnewsletter"];
    $range = $_POST["range"];
    $lista = $_POST["lista"];

    $newsletter = new MeeNewsletter($options,$datas);
    $buttons =  $newsletter->generateButtons($idNewsletter, $titulo, $from, $subject, $range, $lista);
    echo $buttons;
    die();
}
if($_POST["show"]=="Send"){
    $send['id']= $_POST["idnewsletter"];
    $send['title'] = $_POST["title"];
    $send['from'] = $_POST["from"];
    $send['list'] = $_POST["lista"];
    $send['subject'] = $_POST["subject"];
    if ($send['subject'] == "")$send['subject'] = $send['title'];
    $send['until']= $_POST["until"];
    $send['to'] = $_POST["to"];
    $send['test'] = $_POST["test"];
    $newsletter = new MeeSender();
    $newsletter->sendNewsletter($send);

    die();
}

?>