<?php

namespace TE\Db\Query;

/**
 * AbstractQuery 
 * 
 * @abstract
 * @copyright Copyright (c) 2012 Typecho Team. (http://typecho.org)
 * @author Joyqi <magike.net@gmail.com> 
 * @license GNU General protected License 2.0
 */
abstract class AbstractQuery
{
    /**
     * _query  
     * 
     * @var array
     * @access private
     */
    private $_query = array();

    /**
     * _prefix  
     * 
     * @var mixed
     * @access private
     */
    private $_prefix;

    /**
     * __construct  
     * 
     * @param mixed $prefix 
     * @access public
     * @return void
     */
    public function __construct($prefix)
    {
        $this->_prefix = $prefix;
        if (method_exists($this, 'init')) {
            $args = func_get_args();
            array_shift($args);
            call_user_func_array(array($this, 'init'), $args);
        }
    }
    
    /**
     * applyPrefix  
     * 
     * @param mixed $value 
     * @access protected
     * @return void
     */
    protected function applyPrefix($value)
    {
        if (is_array($value)) {
            $result = array();

            foreach ($value as $key => $val) {
                $result[$this->applyPrefix($key)] = $this->applyPrefix($val);
            }

            return $result;
        } else if (is_string($value)) {
            return str_replace('@', $this->_prefix, $value);
        }

        return $value;
    }

    /**
     * setQuery  
     * 
     * @param mixed $name 
     * @param mixed $value 
     * @access protected
     * @return void
     */
    protected function setQuery($name, $value)
    {
        $this->_query[$name] = $value;
    }

    /**
     * pushQuery  
     * 
     * @param mixed $name 
     * @param mixed $value 
     * @access protected
     * @return void
     */
    protected function pushQuery($name, $value)
    {
        if (!isset($this->_query[$name])) {
            $this->_query[$name] = array();
        }

        $this->_query[$name][] = $value;
    }

    /**
     * getQuery  
     * 
     * @param mixed $name 
     * @access protected
     * @return public
     */
    public function getQuery($name)
    {
        return isset($this->_query[$name]) ? $this->_query[$name] : NULL;
    }

    /**
     * where  
     * 
     * @param mixed $condition 
     * @access public
     * @return void
     */
    public function where($condition)
    {
        $args = func_get_args();
        $this->pushQuery('where', array('AND', $args));
    }

    /**
     * orWhere  
     * 
     * @param mixed $condition 
     * @access public
     * @return void
     */
    public function orWhere($condition)
    {
        $args = func_get_args();
        $this->pushQuery('where', array('OR', $args));
    }
}

