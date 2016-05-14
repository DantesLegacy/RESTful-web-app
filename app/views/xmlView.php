<?php

/*
	Demo RESTful Web Application
    Copyright (C) 2016  Joseph McNally

	This file is part of RESTful-web-app

    RESTful-web-app is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    RESTful-web-app is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

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