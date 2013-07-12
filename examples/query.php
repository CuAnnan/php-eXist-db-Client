<?php
include '../lib/Client.class.php';
include '../lib/Query.class.php';
include '../lib/ResultSet.class.php';
include '../lib/SimpleXMLResultSet.class.php';
include '../lib/DOMResultSet.class.php';

$connConfig = array(
		'protocol'=>'http',
		'host'=>'localhost',
		'port'=>'8080',
		'path'=>'/exist/xmlrpc'
);

$conn = new \ExistDB\Client($connConfig);
$xql = <<<EOXQL
for \$cd in collection("/CDCatalog")/CD[./PRICE < \$priceDefinedByBindVariableMethod]
return \$cd
EOXQL;
$stmt = $conn->prepareQuery($xql);
$stmt->bindVariable('priceDefinedByBindVariableMethod', 8.7);
$resultPool = $stmt->execute();
$results = $resultPool->getAllResults();
header('Content-type: text/xml');
echo '<cdCatalog>';
foreach($results as $result)
{
	echo $result;
}
echo '</cdCatalog>';