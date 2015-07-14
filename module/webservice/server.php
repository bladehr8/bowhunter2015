<?php
$url="http://localhost/webservice/server.php?wsdl";
if (isset($_GET['wsdl'])) {
    $autodiscover = new Zend\Soap\AutoDiscover();
    $autodiscover->setClass('Serviceclass')
                 ->setUri('http://example.com/soap.php');
    echo $autodiscover->toXml();
} else {
    // pointing to the current file here
    $soap = new Zend\Soap\Server($url);
    $soap->setClass('Serviceclass');
    $soap->handle();
}
?>