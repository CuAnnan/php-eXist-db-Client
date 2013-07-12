<?php
namespace ExistDB;
include 'XML/RPC2/Client.php';
class Client
{
	/**
	 * 
	 * The URI to the database instance inclusive of the port
	 * @var $uri
	 */
	protected $uri;
	
	/**
	 * The xml rpc client for the instance
	 * @var $connection
	 */
	protected $connection;
	protected $options;
	
	protected $query;
	
	protected function defaultOptionValue()
	{
		
	}
	
	public function __construct($options = null)
	{
		if($options)
		{
			$defaults = array(
				'protocol'=>'http',
				'user'=>'guest',
				'password'=>'guest',
				'host'=>'localhost',
				'port'=>'8080',
				'path'=>'/exist/xmlrpc'
			);
			foreach($defaults as $part=>$value)
			{
				if(!isset($options[$part]))
				{
					$options[$part] = $value;
				}
			}
			
			if(isset($options['uri']))
			{
				$this->uri = $options['uri'];
			}
			else
			{
				$this->uri = $options['protocol'].'://'.$options['user'].':'.$options['password'].'@'.$options['host'].':'.$options['port'].$options['path'];
			}
		}
		else
		{
			$this->uri = 'http://localhost:8080/exist/xmlrpc';
		}
		$this->conn = null;
		$this->client = \XML_RPC2_Client::create(
			$this->uri,
			array(
					'encoding'=>'utf-8'
			)
		);
	}
	
	public function storeDocument($docName, $xml, $overWrite = false)
	{
		$this->client->parse($xml, $docName, $overWrite?1:0);
	}
	
	public function prepareQuery($xql)
	{
		$query = new Query($xql, $this->client);
		return $query;
	}
}