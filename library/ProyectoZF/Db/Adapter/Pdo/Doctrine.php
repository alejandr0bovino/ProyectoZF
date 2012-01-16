<?php

class ProyectoZF_Db_Adapter_Pdo_Doctrine extends  Zend_Db_Adapter_Pdo_Mysql
{
	protected $_connection;
	protected $_profiler;
	public function __construct(PDO $connection){
		$this->_connection = $connection;
		$this->_profiler = new Zend_Db_Profiler();
	}
	public function setConnection(PDO $connection ){
		$this->_connection = $connection;
	}

}
