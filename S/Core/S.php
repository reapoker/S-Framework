<?php
namespace S;
include 'Route.php';//header("Content-type:text/html;charset=utf-8");
class S{
  private static $prefixes=[];
  public static function run(){//该函数中应做到：设置字符集，系统类映射，自动加载注册方法，实例化路由
    self::setHeader();//设置字符集，使相应的浏览器可以加载中文
    self::getMapList();
    spl_autoload_register('self::s_autoload');//注册自定义的自动加载方法，使之替换__autoload方法
    try{
      new Route();
    }catch(Exception $e){
      $e->getDetail();
    }//实例化路由类，路由机制就是把某一个特定形式的URL结构中提炼出来系统对应的参数。
    echo '欢迎使用S框架';
  }
  private static function setHeader(){
    header("Content-type:text/html;charset=".C('default_charset'));
    date_default_timezone_set(C('default_timezone'));//date_default_timezone_set() 函数设置脚本中所有日期/时间函数使用的默认时区。
  }
  public static function addNamespace($prefix,$base_dir,$prepend=false){
    //格式化命名空间前缀，以反斜杠结束（去除两侧的反斜杠，只在最后加上一个反斜杠）
    $prefix=trim($prefix,'\\').'\\';//'\\'只代表一个反斜杠，其中一个反斜杠是为了转义而存在
    //格式化基目录以以正斜杠结尾，DIRECTORY_SEPARATOR是系统常量，目录分隔符，把基目录右侧斜杠去掉，换成系统支持的斜杠，然后最后统一为正斜杠
    $base_dir=rtrim($base_dir,'/').DIRECTORY_SEPARATOR;
    $base_dir=rtrim($base_dir,DIRECTORY_SEPARATOR).'/';
    //初始化命名空间的前缀数组
    //如果前缀已存在数组中则跳过，否则存入数组
    if(isset(self::$prefixes[$prefix])===false){
      self::$prefixes[$prefix]=[];//代表前缀的变量prefix作为索引存入前缀数组
    }
    if($prepend){//命名空间前缀相同时，后增基目录，即在确定了相应的前缀后根据索引赋值（$prepend默认为false，即默认是存入数组尾部，若有特殊需求，可在传参时设为true）
      array_unshift(self::$prefixes[$prefix],$base_dir);//array_unshift()	函数用于向数组插入新元素。新数组的值将被插入到数组的开头。
    }else {
      //前增，向数组尾部增加值
      array_push(self::$prefixes[$prefix],$base_dir);//array_push() 函数向第一个参数的数组尾部添加一个或多个元素（入栈），然后返回新数组的长度。
    }
  }
  private static function getMapList(){
    //实例化config类，执行get方法，获取到namespace_map_list的值，循环更改$prefixes的值
    foreach(Config::getInstance()->get('namespace_map_list') as $key=>$value){
      self::addNamespace($key,$value);
    }
  }
  private static function s_autoload($className){
    //当前命名空间前缀
    $prefix=$className;
    //从后面遍历完全合格类名中的命名名称，来查询映射的文件名
    //strrpos获取参数2在参数1中最后出现的位置，substr截取字符串
    while(false!==$pos=strrpos($prefix,'\\')){
      //命名空间前缀
      $prefix=substr($className,0,$pos+1);
      //相对类名
      $relative_class=substr($className,$pos+1);
      //尝试加载与映射文件相对应的类
      $mapped_filed=self::loadMappedFile($prefix,$relative_class);
      //var_dump($mapped_filed);
      if($mapped_filed){
        return $mapped_filed;
      }
      //去除前缀的反斜杠
      $prefix=rtrim($prefix,'\\');
    }
    return false;
  }
  private static function loadMappedFile($prefix,$relative_class){
    //判断这个命名空间是否存在在基目录中
    if(isset(self::$prefixes[$prefix])===false){
      return false;
    }
    $relative_class=str_replace('\\','/',$relative_class);
    foreach(self::$prefixes[$prefix] as $base_dir){
      $file=$base_dir.$relative_class.'php';
      //如果映射文件存在，就加载他
      if(self::requireFile($file)){
        return true;
      }
    }
    return false;
  }
  private static function requireFile($file){
    if(file_exists($file)){
      include $file;
      return true;
    }
    return false;
  }
}
 ?>
