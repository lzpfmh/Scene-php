<?php

namespace Scene;



class SomeComponent
{
    protected $_response;

    protected $_someFlag;

    public function __construct($response, $someFlag)
    {
        $this->_response = $response;
        $this->_someFlag = $someFlag;
    }

    public function say()
    {
    	var_dump($this->_response);
    	echo $this->_someFlag;
    }
}