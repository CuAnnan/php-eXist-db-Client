<?php
namespace ExistDB;
class Query
{
	protected $xql;
	protected $options;
	protected $client;
	protected $returnType;
	protected $variables;
	
	public function __construct($xql, $client,  $options = array('indent' => 'yes','encoding' => 'UTF-8','highlight-matches' => 'none'))
	{
		$this->xql = $xql;
		$this->options = $options;
		$this->client = $client;
		$this->returnType = "string";
		$this->variables = array();
	}
	
	public function setStringReturnType()
	{
		$this->returnType = "string";
	}
	
	public function setSimpleXMLReturnType()
	{
		$this->returnType = "SimpleXML";
	}
	
	public function setDOMReturnType()
	{
		$this->returnType = "DOM";
	}
	
	public function bindVariable($variableName, $value)
	{
		$this->variables[$variableName]=$value;
	}
	
	public function setOption($option, $value)
	{
		$this->options[$option] = $value;
	}
	
	public function execute()
	{
		if($this->variables)
		{
			$this->options['variables'] = $this->variables;
		}
		$resultId = $this->client->executeQuery(
			$this->xql,
			$this->options
		);
		$result = null;
		
		switch($this->returnType)
		{
			case "DOM":
				$result = new \ExistDB\DOMResultSet(
					$this->client,
					$resultId,
					$this->options
				);
				break;
			case "SimpleXML":
				$result = new \ExistDB\SimpleXMLResultSet(
					$this->client,
					$resultId,
					$this->options
				);
				break;
			default:
				$result = new \ExistDB\ResultSet(
					$this->client,
					$resultId,
					$this->options
				);
				break;
		}
		return $result;
	}
}