<?php

namespace Neos\Data;

/* CONFIG ROOT STATIC CLASS
 * 
 * Use:
  1 - class_alias('Start\Config', 'o'); - basic usage
  2 - o::load('file.ini'); >> load ini file

  //by __callStatic emulate section named function
  3 - o::section();                           >> return indicate section array
  4 - o::section('index');                    >> return indicated section and index of array
  5 - o::section(array('index'=>'newValue')); >> add/mod specific section[index] = newValue
  6 - o::noSection();                         >> return false if section not exists
 *
 *
 */

class Config {

    //Parameters
    static $user = array();
    static $system = array();

    //Load config ini file
    static function load($file = null){
        if($file != null && file_exists($file))
                return static::$user = parse_ini_file($file,true);
    }

    //Set user parameter
    static function set($index,$val){
        return static::$user[$index] = $val;
    }

    //Get user parameter
    static function get($index){
        return isset(static::$user[$index]) ? static::$user[$index] : null;
    }
    
    //Insert/add new parameter
    static function add($index, $val){
        if(is_array($val)){
            foreach ($val as $k=>$v){
                static::$user[$index][$k]=$v;
            }
        } else {
            static::$user[$index][]=$val;
        }
    }

    //StaticCall
    static function __callStatic($name,$args){
        if(!isset(static::$user[$name])) return false;
        $st = static::$user[$name];

        //SET
        if(isset($args[0]) && is_array($args[0])){
            foreach($args[0] as $k=> $v){
                $st[$k] = $v;
            }
            static::$user[$name] = $st;
        }else{
            //GET
            foreach($args as $k=> $a){
                if(isset($st[$a])) $st = $st[$a];
            }
        }
        return $st;
    }
    
     /**
     * Create ".ini" file
     *
     * @param Array $file   PAth/name for ini file | null -> return contents and not save file
     * @param String $type  indicate the type 'user' or 'system' configs
     *
     * @return Bool|String  If $file is specified, saves the file ".ini". Otherwise, returns the contents without saving the file.
     */
    
    static function save($file = null, $type = 'user'){
        if(!is_array(static::$$type)) return false;

        $o = '';
        foreach(static::$$type as $k => $v){
            $o .= '[' . $k . "]\r\n";
            //segundo nó
            if(is_array($v)){
                foreach($v as $_k => $_v){
                    //terceiro nó
                    if(is_array($_v)){
                        foreach($_v as $__k => $__v){
                            if(is_array($__v)) $__v = print_r($__v, true);
                            $o .= "\t" . $_k . '[' . $__k . '] = ' . (is_numeric($__v) ? $__v : '"' . $__v . '"') . "\r\n";
                        }
                    } else $o .= "\t" . $_k . ' = ' . (is_numeric($_v) ? $_v : '"' . $_v . '"') . "\r\n";
                }
            }
        }
        if($file != null) return file_put_contents($file, $o);
        else return $o;
    }

}
