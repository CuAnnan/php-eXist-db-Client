<?php
namespace ExistDB;
class ResultSet
{
	protected $client;
	protected $hits;
	protected $currentHit;
	protected $hasMoreHits;
	protected $resultId;
	protected $options;
	
	function __construct($client, $resultId, $options)
	{
		$this->client = $client;
		$this->currentHit = 0;
		$this->hasMoreHits = true;
		$this->resultId = $resultId;
		$this->hits = $this->client->getHits($resultId);
		$this->hasMoreHits = $this->hits > 0;
		$this->options = $options;
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
		return $result->scalar;
	}
	
	public function getAllResults()
	{
		$results = array();
		while($this->hasMoreHits)
		{
			$results[] = $this->getNextResult();
		}
		return $results;
	}
}