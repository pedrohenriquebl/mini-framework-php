<?php

namespace app\classes;

use app\core\Controller;

class Input{

    /**
     * Retorna um valor via parametro get
     * 
     * @param string $parm
     * @param int $filter
     * @return mixed
     */


    public static function get(string $param, int $filter = FILTER_SANITIZE_STRING){
    
        return filter_input(INPUT_GET, $param, $filter);
    }

    /**
     * Retorna um valor via parametro post
     * 
     * @param string $parm
     * @param int $filter
     * @return mixed
     */


    public static function post(string $param, int $filter = FILTER_SANITIZE_STRING){
    
        return filter_input(INPUT_POST, $param, $filter);
    }

}