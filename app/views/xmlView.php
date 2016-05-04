<?php
class xmlView
{
	private $model, $controller, $slimApp;

	public function __construct($controller, $model, $slimApp) {
		$this->controller = $controller;
		$this->model = $model;
		$this->slimApp = $slimApp;		
	}

	public function xml_encode($mixed, $domElement=null, $DOMDocument=null) {
    	if (is_null($DOMDocument)) {
        	$DOMDocument =new DOMDocument;
        	$DOMDocument->formatOutput = true;
        	$this->xml_encode($mixed, $DOMDocument, $DOMDocument);
        	return $DOMDocument->saveXML();
    	}
    	else {
        	// To cope with embedded objects 
        	if (is_object($mixed)) {
          	$mixed = get_object_vars($mixed);
        }
        if (is_array($mixed)) {
            foreach ($mixed as $index => $mixedElement) {
                if (is_int($index)) {
                    if ($index === 0) {
                        $node = $domElement;
                    }
                    else {
                       		// $node = $DOMDocument->createElement($domElement->tagName);
                        	// $domElement->parentNode->appendChild($node);
                    	}
                	}
                	else {
                    	$plural = $DOMDocument->createElement($index);
                    	$domElement->appendChild($plural);
                    	$node = $plural;
                    	if (!(rtrim($index, 's') === $index)) {
                        	$singular = $DOMDocument->createElement(rtrim($index, 's'));
                        	$plural->appendChild($singular);
                        	$node = $singular;
                    	}
                	}
                	$this->xml_encode($mixedElement, $node, $DOMDocument);
            	}
        	}
        	else {
            	$mixed = is_bool($mixed) ? ($mixed ? 'true' : 'false') : $mixed;
            	$domElement->appendChild($DOMDocument->createTextNode($mixed));
        	}
    	}
	}

	public function output(){
		//prepare xml response
//		$xml = new SimpleXMLElement('<root/>');
//		array_walk_recursive($this->model->apiResponse, array ($xml, 'addChild'));
//		$xmlResponse = $xml->asXML();
//		$this->slimApp->response->write($xmlResponse);

		$xmlResponse = $this->xml_encode(
		array ('root' => $this->model->apiResponse)
		);
		$this->slimApp->response->write($xmlResponse);
	}
}
?>