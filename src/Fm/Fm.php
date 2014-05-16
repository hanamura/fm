<?php

namespace Fm;

class Fm
{
    protected $_items = null;

    public function __get($name)
    {
        switch ($name) {
            case 'items':
                return $this->_items;
            default:
                return isset($this->_items[$name]) ? $this->_items[$name] : null;
        }
    }

    public function __call($name, $args)
    {
        return $this->_items[$name] = new FmItem(array_merge(array(
            'name' => $name
        ), $args[0] ?: array()));
    }

    function __construct($items = null)
    {
        foreach ($items ?: array() as $name => $arg) {
            $this->__call($name, array($arg));
        }
    }

    public function check($params, &$errors = null)
    {
        foreach ($this->_items as $name => $item) {
            if (!$item->check(isset($params[$name]) ? $params[$name] : null, $error)) {
                $errors[$name] = $error;
            }
        }
        return !$error;
    }
}
