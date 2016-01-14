<?php

namespace Radiant\Toolbox;

/*
 *
 */
class Config
{
    private static $active_configs = array();

    public static function loadConfigs() {
        $path = "configs/";
        $config_array = array("application", "database");

        foreach($config_array as $config) {
            if(!in_array($config, static::$active_configs)) {
                array_push(static::$active_configs, $config);
                require $path . $config . ".config.php";
            }
        }
    }


    public static function loadConfig($config) {
        if($config == null)
            return false;

        $filePath = "configs/". $config . ".config.php";

        if(is_file("configs/" . $filePath) && !in_array($config, static::$active_configs)) {
            include $filePath;

            return true;
        } else if(in_array($config, static::$active_configs)) {
            return true;
        }

        return false;
    }

}
