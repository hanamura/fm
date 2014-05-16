<?php

namespace Fm;

class FmItem
{
    protected $_options = null;

    public function __get($name)
    {
        switch ($name) {
            case 'validator':
                return $this->_options['validator'];
            case 'message':
                return $this->_options['message'];
            case 'name':
                return $this->_options['name'];
            case 'data':
                return $this->_options['data'];
            default:
                return null;
        }
    }

    public function __isset($name)
    {
        switch ($name) {
            case 'validator':
            case 'message':
            case 'name':
            case 'data':
                return true;
            default:
                return false;
        }
    }

    function __construct($options)
    {
        $this->_options = array_merge(array(
            'validator' => null,
            'message' => null,
            'name' => null,
            'data' => null,
        ), $options ?: array());
    }

    public function check($value, &$error = null)
    {
        if ($this->_options['validator']) {
            try {
                $this->_options['validator']->assert($value);
            } catch (\InvalidArgumentException $e) {
                if (is_callable($this->_options['message'])) {
                    $error = call_user_func($this->_options['message'], $e);
                } elseif (is_array($this->_options['message'])) {
                    $error = $e->findMessages($this->_options['message']);
                } elseif ($this->_options['message']) {
                    $error = $this->_options['message'];
                } else {
                    $error = $e;
                }
                return false;
            }
            return true;
        } else {
            return true;
        }
    }
}
