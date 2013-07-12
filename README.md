php-eXist-db-Client
===================

A client that abstracts out the XML RPC calls for eXist-db.


Usage
=====

$conf = array(
  'protocol'=>'http',
  'host'=>'localhost',
  'user'=>'guest',
  'password'=>'guest',
  'port'=>'8080'
  'path'=>'/exist/xmlrpc'
);

$conn = new \ExistDB\Client($conf);

$stmt = $conn->prepareQuery('for $someNode in collection("/SomeCollection")/someNodeName[./@somePredicateAttribute=$someValueToBeBound] return $someNode');

$stmt->bindVariable('someValueToBeBound', '5');

$resultPool = $stmt->execute();

$result = $resultPool->getAllResults();


Prerequisites
=============
XML_RPC2 Pear package
