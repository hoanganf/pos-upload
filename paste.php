<?php
//$headers=getallheaders();
//print_r($_FILES);
include_once 'config.php';
include_once 'util.php';
include_once constant("LIB_DIR").'/php/dao/Data.php';
set_error_handler("errorToJson");

$request=new Data();
$login=new Login();
if(isset($_COOKIE['pos_access_token'])){
  $request->access_token=$_COOKIE['pos_access_token'];
  $loginResult=json_decode($login->login($request));
  if(!$loginResult->status){
    echo toJson('false',E_NO_LOGIN,'AccessDenied.');
  }else{
    FileUploadUtil::paste($_FILES["fileToUpload"],'files/'.$_POST['folder'].'/');
  }
}else{
  echo toJson('false',E_NO_LOGIN,'AccessDenied.');
}
?>
