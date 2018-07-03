<?php
namespace core;

class ihad
{
    public static $classMap = array();
    public $assign;

    static public function run()
    {
        \core\lib\log::init();
        $route =  new \core\lib\route();
        $ctrlClass = $route->ctrl;
        $action = $route->action;
        $ctrlfile = APP . '/ctrl/'.$ctrlClass.'Ctrl.php';
        $cltrlClass = '\\'.MODULE.'\ctrl\\'.$ctrlClass.'Ctrl';
        if(is_file($ctrlfile)){
            include $ctrlfile;
            $ctrl = new $cltrlClass();
            $ctrl->$action();
            \core\lib\log::log('ctrl:'.$ctrlClass.'     '.'action:'.$action);
        }else{
            throw new \Exception('找不到控制器'.$ctrlClass);
        }
    }

    static public function load($class)
    {
        //自动加载类库
        // new \ihad\route();
        // $class = '\core\route';
        //IHAD.'/core/route.php';
        if(isset($classMap[$class])){
            return true; //避免重复加载
        }else{
            str_replace('\\','/',$class);
            $file = IHAD.'/'.$class.'.php';
            if(is_file($file)){
                include $file;
                self::$classMap[$class] = $class;
            }else{
                return false;
            }
        }
    }

    public function assign($name,$value)
    {
        $this->assign[$name] = $value;
    }

    public function display($file)
    {
        $file = APP.'/views/'.$file;
        if(is_file($file)){
            extract($this->assign);

            $loader = new \Twig_Loader_Filesystem(APP.'/views');
            $twig = new \Twig_Environment($loader, array(
                'cache' => IHAD.'/log/twig',
                'debug' => DEBUG
            ));
            $template = $twig->loadTemplate('index.html');
            $template->display($this->assign?$this->assign:array());
        }
    }
}