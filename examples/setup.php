<?php
	include '../lib/Client.class.php';
	include '../lib/Query.class.php';
	include '../lib/ResultSet.class.php';
	include '../lib/SimpleXMLResultSet.class.php';
	include '../lib/DOMResultSet.class.php';
	
	// these are the values the class will default to, so it is entirely possible to 
	// instantiate the class with no paramaters provided
	$connConfig = array(
		'protocol'=>'http',
		'user'=>'admin',
		'password'=>'adminPasswordHere',
		'host'=>'localhost',
		'port'=>'8080',
		'path'=>'/exist/xmlrpc'
	);
	// alternatively, you can specify the URI as a whole in the form
	// $connConfig = array('uri'=>'http://user:password@host:port/path');
	
	$conn = new \ExistDB\Client($connConfig);
	$conn->createCollection('CDCatalog');
	$catalogAsSingleNode = simplexml_load_file('./xml/cd_catalog.xml');
	foreach($catalogAsSingleNode->children() as $child)
	{
		$md5able = '';
		foreach($child->children() as $property)
		{
			$md5able .= (string)$property;
		}
		$conn->storeDocument(
			'CDCatalog/'.md5($md5able).'.xml',
			$child->asXML()
		);
	}