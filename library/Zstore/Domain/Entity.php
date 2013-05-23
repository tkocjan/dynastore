<?php
namespace Zstore\Domain;
use Doctrine\ORM\Mapping as ORM,
    Logger;

/** @ORM\MappedSuperclass */
abstract class Entity
{
    // name property getters & setters starting with following prefixs
    // must declare getter to have setter
    // just declaring getter causes no setting or unsetting
    // declaring setter will allow unsetting
    // declare static so not returned in toArray
    static protected $_getterPrefix = '_get__';
    static protected $_setterPrefix = '_set__';
    static protected $_excludeKey = '__isInitialized__';    
    
    public function __get($prop) {
        //Logger::info(__METHOD__.': $prop='.$prop);
        $propGetter = self::$_getterPrefix.$prop;
        if (method_exists($this, $propGetter))
            return $this->$propGetter();
        return $this->$prop;
    }

    public function __set($prop, $value) {
        //Logger::info(__METHOD__.': $prop='.$prop.',$value='.$value);
        $propGetter = self::$_getterPrefix.$prop;
        if (method_exists($this, $propGetter)) {
            $propSetter = self::$_setterPrefix.$prop;
            if (method_exists($this, $propSetter)) {
                $this->$propSetter($value);
                return;
            }
            else    
                throw new \Exception("Can't set property: ".$prop);
        }
        $this->$prop = $value;
    }
    
    public function __isset($prop) {
        return __isset($this->$prop);
    }
    
    public function __unset($prop) {
        //Logger::info(__METHOD__.': $prop='.$prop.',$value='.$value);
        $propGetter = self::$_getterPrefix.$prop;
        if (method_exists($this, $propGetter)) {
            $propSetter = self::$_setterPrefix.$prop;
            if (method_exists($this, $propSetter))
                return __unset($this->$prop);
            else    
                throw new \Exception("Can't unset property: ".$prop);
        }
        return __unset($this->$prop);
    }    
    
    public function toArray() {
        $array = get_object_vars($this);
        if (array_key_exists(self::$_excludeKey, $array))
            unset($array[self::$_excludeKey]);
        $methods = get_class_methods($this);
        $prefixLength = strlen(self::$_getterPrefix);
        foreach($methods as $method) {
            if (substr($method, 0, $prefixLength) == self::$_getterPrefix) {
                $array[substr($method, $prefixLength)] = $this->$method();
            }
        }
        return $array;
    }    
}
