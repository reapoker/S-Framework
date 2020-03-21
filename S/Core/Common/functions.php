<?php
function C(){
  $conf=\S\Config::getInstance();//获取配置类的单例
  $args=func_get_args();//获取函数中传进来的参数
  switch (func_num_args()) {//获取函数中传进来的参数的个数
    case 0://0个参数，读取全部配置
      return $conf->get();//获取Config对象的get方法的结果
      break;
    case 1://一个参数，则为读取配置信息的值，
      if(is_array($args[0])){//如果是数组，为动态设置配置信息的值
        return $conf->setAll($args[0]);
      }
      return $conf->get($args[0]);//如果不是数组，就获取该配置信息的值
      break;
    case 2://两个参数，为设置配置信息的值
      return $conf->set($args[0],$args[1]);
      break;
    default:
      break;
  }
}
function I($a){
  $b=array_merge($_GET,$_POST);
  return $b[$a];
}
function dump($arr){
  if(is_array($arr)){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
  }else {
    echo $arr;
  }
}
function import($str){
  $path=C('extend_path').$str;
  if(file_exists($path)){
    require $path;
    return true;
  }else {
    throw new \S\S_Exception('您要导入的文件不存在！');
  }
}
function session($parm1,$parm2=null){
  if(is_null($parm2)){
    if(isset($_SESSION[$parm1])){
      return $_SESSION[$parm1];
    }else {
      return false;
    }
  }else {
    $_SESSION[$parm1]=$parm2;
    return true;
  }
}
function redirect($url,$time=0,$msg=''){
  $url=__ROOT__.$url;
  if(empty($msg)){
    $msg="系统将在{$time}秒后自动跳转到{$url}！";
  }
  if(!headers_sent()){
    //redirect
    if(0===$time){
      header('Location:'.$url);
    }else {
      header('refresh:'.$time.';url='.$url);
      echo($msg);
    }
    exit();
  }else {
    $str="<meta http-equiv=\'Refresh\' content=\'".$time.";URL=".$url."\'>";
    if($time!=0){
      $str.=$msg;
    }exit();
  }
}
function	isAjax(){
  if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])	&&	strtolower($_SERVER['H TTP_X_REQUESTED_WITH'])	==	'xmlhttprequest'){
    return	true;
  }else{
    return	false;
  }
}
function	isGet(){
  return	$_SERVER['REQUEST_METHOD']	==	'GET'	?	true	:	false;
}
function	isPost(){
  return	($_SERVER['REQUEST_METHOD']	==	'POST'		&&	(empty($_SERVER['HTT P_REFERER'])	||	preg_replace("~https?:\/\/([^\:\/]+).*~i",	"\\1",	$_SERVER['HTTP_REFERER'])	==	preg_replace("~([^\:]+).*~",	"\\1",	$_SERVER['HTTP_ HOST'])))	?	1	:	0;
}
function M($table_name,$dsn=null){
  if(is_null($dsn)){
    $obj=\S\Model::getInstance($table_name);
  }
  return $obj;
}
 ?>
