<?php
namespace ExistDB;
class DOMResultSet extends ResultSet
{
	function __construct($client, $resultId, $options)
	{
		parent::__construct($client, $resultId, $options);
	}
	
	public function getNextResult()
	{
		$result = $this->client->retrieve(
				$this->resultId,
				$this->currentHit,
				$this->options
		);
		
		$this->currentHit++;
		$this->hasMoreHits = $this->currentHit < $this->hits;
		
		$doc = new \DOMDocument();
		$doc->loadXML($result->scalar);
		return $doc;
	}

	public function current()
	{
		$doc = new \DOMDocument();
		$doc->loadXML($this->retrieve());
		return $doc;
	}
}