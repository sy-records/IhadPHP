<?php
namespace core\lib;

class config
{
    static public $config = array();

    static public function get($name,$file)
    {
        /**
         * 1、判断配置文件是否存在
         * 2、判断配置是否存在
         * 3、缓存配置
         */
        if(isset(self::$config[$file])){
            return self::$config[$file][$name];
        }else{
            $path = IHAD.'/core/config/'.$file.'.php';
            if(is_file($path)){
                $config = include $path;
                if(isset($config[$name])){
                    self::$config[$file] = $config;
                    return $config[$name];
                }else{
                    throw new \Exception('没有该配置项'.$name);
                }
            }else{
                throw new \Exception('找不到配置文件'.$file);
            }
        }
    }

    static public function all($file)
    {
        if(isset(self::$config[$file])){
            return self::$config[$file];
        }else{
            $path = IHAD.'/core/config/'.$file.'.php';
            if(is_file($path)){
                $config = include $path;
                self::$config[$file] = $config;
                return $config;
            }else{
                throw new \Exception('找不到配置文件'.$file);
            }
        }
    }
}