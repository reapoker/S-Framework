<?php
namespace S;
defined('S_PATH') or define('S_PATH',dirname($_SERVER['SCRIPT_FILENAME']).'/');
//当前脚本执行的绝对路径：``S_PATH``,
defined('APP_DEBUG') or define('APP_DEBUG',false);
if(APP_DEBUG==true){
  error_reporting(E_ALL);
}else {
  error_reporting(0);
}
//是否开启调试模式``APP_DEBUG``，
define('IS_CGI',(0===strpos(PHP_SAPI,'cgi')||false!==strpos(PHP_SAPI,'fcgi'))?1:0);
//是否是CGI模式``IS_CGI``（我们需要根据CGI设置根目录），
define('IS_CLI',PHP_SAPI=='cli'?1:0);
if(!IS_CLI){

}
//是否是CLI模式``IS_CLI``
if(!defined('_PHP_FILE_')){
  if(IS_CGI){
    //CGI/FASTCGI模式下
    $_temp =explode('.php',$_SERVER['PHP_SELF']);//$_SERVER:当前正字啊执行脚本的文件名
    define('_PHP_FILE_',rtrim(str_replace($_SERVER['HTTP_HOST'],'',$_temp[0].'.php'),'/'));//$_SERVER:获取服务器地址
  }else{
    define('_PHP_FILE_',rtrim($_SERVER['SCRIPT_NAME'],'/'));
  }
}
//当前文件名``_PHP_FILE_``
if(!defined('__ROOT__')){
  $_root= rtrim(dirname(_PHP_FILE_),'/');
  define('__ROOT__',(($_root=='/'||$_root=='\\')?'':$_root.'/'));
}
//当前网站根目录``__ROOT__``
defined('APP_PATH') or define('APP_PATH',S_PATH.'Application/');
//系统应用目录（即模块所在目录）：``APP_PATH``
defined('CORE_PATH') or define('CORE_PATH',S_PATH.'S/Core/');
//运行核心目录		：``CORE_PATH``
include(CORE_PATH.'Config.php');//引入系统配置类
include(CORE_PATH.'Common/functions.php');//引入系统函数库
include(CORE_PATH.'S.php');//引入加载函数
S::run();//执行加载函数的run方法
//echo 'hello world!';
 ?>
