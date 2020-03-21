<?php
namespace S;
//header("Content-type:text/html;charset=utf-8");
define('replace3',S_PATH.'S/Extend/');
define('replace1',S_PATH . 'S/core');
define('replace2',S_PATH . 'Application/Home/Controller');
class Config{
  private $config=[
    'default_charset'=>'utf-8',
    'default_timezone'=>'PRC',
    'namespace_map_list'=>[
      'S'=>replace1,
      'Home'=>replace2,
    ],
    'module_name'=>'m',//默认模块参数名 index.php?m=Home&c=Index&a=index
    'default_module'=>'Home',//默认模块参数值
    'controller_name'=>'c',//默认控制器参数名
    'default_controller'=>'Index',//默认控制器参数值
    'action_name'=>'a',//默认方法名
    'default_action'=>'index',//默认方法参数值
    'extend_path'=>replace3,
  ];//这个数组用来存放配置值
  private static $instance;//这个变量用来存放单例
  public static function getInstance(){//单例模式
    if(!(self::$instance instanceof self)){//判断现在的￥instance是否是自身的一个实例
      self::$instance=new self;//如果不是的话，证明这个类从来没有实例化过，那么实例化自己
    }
    return self::$instance;//如果是的话，就返回这个实例
  }
  /*
  *Config constructor构造函数，创建实例时就引入配置文件，并合并，给$config赋值
  */
  private function _construct(){//在实例化这个类的时候就会调用这个函数
    $sys_conf=[];//系统配置数组
    $user_conf=[];//用户配置数组
    //系统配置文件
    if(file_exists(SYS_CONFIG)){//如果S-Framework中定义过系统配置文件路径，其存在且有效，则把这个文件包含进来
      $sys_conf=include(SYS_CONFIG);//include函数通常情况下，包含成功返回1，包含失败返回false。但是，如果被包含文件中用return返回，那么这个值就是return的值。
    }
    //用户配置文件
    if(file_exists(USER_CONFIG)){//如果S-Framework中定义过用户配置文件路径，其存在且有效，则把这个文件包含进来
      $user_conf=include(USER_CONFIG);
    }
    return $this->config=array_merge($sys_conf,$user_conf);//把用户配置和系统配置合并在同一个数组里，并使用用户配置覆盖掉系统配置，这样就实现了用户自定义配置
    /*
array_merge() 函数把一个或多个数组合并为一个数组。

提示：您可以向函数输入一个或者多个数组。

注释：如果两个或更多个数组元素有相同的键名，则最后的元素会覆盖其他元素。

注释：如果您仅向 array_merge() 函数输入一个数组，且键名是整数，则该函数将返回带有整数键名的新数组，其键名以 0 开始进行重新索引（参见下面的实例 1）。

提示：该函数与 array_merge_recursive() 函数之间的不同是在处理两个或更多个数组元素有相同的键名的情况。array_merge_recursive() 不会进行键名覆盖，而是将多个相同键名的值递归组成一个数组。
  */
  }
  /*
  *@return array 获取config文件中的数据
  */
  public function get($parm=null){
    $value=[];
    if(isset($this->config)&&empty($parm)){//如果没有参数传进来就返回整个config数组（config数组在构造函数中获得过值）
      return $this->config;
    }
    if(isset($this->config[$parm])){//如果有参数传进来，就在config数组中寻找相应的键值对，并将其返回
      return $this->config[$parm];
    }else {
      echo 'config参数错误';
    }
  }
  public function setAll($arr){//批量设置配置项
    if(is_array($arr)){//如果传进来的是一个数组的话
      foreach($arr as $key=>$value){//就遍历这个数组，每遍历一个键值对，就调用一次set方法，把键与值传递进去
        $this->set($key,$value);//使每个键值对都成为一个配置项，然后返回true，证明执行成功
      }
      return true;
    }else {
      return false;//如果传进来的不是数组，就返回false，证明执行失败
    }
  }
  public function set($keys,$values){//设置配置数组的值
    $this->config[$keys]=$values;//使传进来的两个参数的键与值对应
    return true;
  }
}
 ?>
