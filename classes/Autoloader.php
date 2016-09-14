<?php
namespace NSclasses;

class Autoloader
{
    static function register()
      { 
        spl_autoload_register(array(__CLASS__, 'autoload'));
      }

    static function autoload ($class_name)
      {
        echo $class_name;
        $namespace = explode('\\', $class_name);      
        $test = end($namespace);
        require 'classes/'.$test . '.php';
      }
}

?>