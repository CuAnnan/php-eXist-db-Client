<?php
namespace ExistDB;
class ResultSet implements \Iterator
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
		$result = $this->retrieve();
		$this->currentHit++;
		$this->hasMoreHits = $this->currentHit < $this->hits;
		return $result->scalar;
	}
	
	protected function retrieve()
	{
		return $this->client->retrieve(
				$this->resultId,
				$this->currentHit,
				$this->options
		);
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
	
	public function rewind()
	{
		$this->currentHit = 0;
	}
	
	public function current()
	{
		$result = $this->retrieve();
		return $result->scalar;
	}
	
	public function key()
	{
		return $this->currentHit;
	}
	
	public function next()
	{
		$this->hasMoreHits = ++$this->currentHit < $this->hits;
	}
	
	function valid()
	{
		return $this->hasMoreHits;
	}
}