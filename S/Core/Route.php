<?php
namespace S;
class Route{
  private $module;//当前模块
  private $controller;//当前控制器
  private $action;//当前方法
  public function _construct(){
    $this->parseUrl();//解析路由，获得模块、控制器和操作
    $this->newAction();//实例化控制器，并执行相应的方法
  }
  public function parseUrl(){
    //预定义的 $_GET 变量用于收集来自 method="get" 的表单中的值。
    $this->module=isset($_GET[C('module_name')])?$_GET[C('module_name')]:C('default_module');
    $this->controller=isset($_GET[C('controller_name')])?$_GET[C('controller_name')]:C('default_controller');
    $this->action=isset($_GET[C('action_name')])?$_GET[C('action_name')]:C('default_action');
  }
  public function newAction(){
    $path=APP_PATH.$this->module.'/Controller/'.$this->controller.'Controller.php';//把模块名和控制器名连接成字符串，作为控制器的真实路径
    if(file_exists($path)){//如果这个文件存在，就通过模块名和控制器名拼接成命名空间的路径
      $controllerName='\\'.$this->module.'\\'.$this->controller.'Controller';
    }else {
      throw new S_Exception($controllerName.'控制器类文件不存在');
    }
    if(class_exists($controllerName)){//然后判断这个命名空间下的类是否存在，存在的话，就实例化这个控制器类
      $controllerObj=new $controllerName;
    }else {
      throw new S_Exception($controllerName.'控制器类不存在，请检查类名或命名空间');
    }
    if(method_exists($controllerName,$this->action)){//判断这个控制器中是否存在这个方法，如果存在的话，就执行这个方法
      $controllerObj->{$this->action}();
    }else {
      throw new S_Exception($this->action.'方法不存在');
    }
  }
}
 ?>
