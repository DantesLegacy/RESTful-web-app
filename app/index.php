<?php
require_once "../Slim/Slim.php";
Slim\Slim::registerAutoloader ();

$app = new \Slim\Slim (); // slim run-time object

require_once "conf/config.inc.php";

function authenticate(\Slim\Route $route) {
	$app = \Slim\Slim::getInstance();
	$httpMethod = $app->request->getMethod();
	$headers = $app->request->headers;
	
	include_once "models/" . USER_MODEL . ".php";
	include_once "controllers/" . USER_CONTROLLER . ".php";
	include_once "views/" . USER_VIEW . ".php";
	
	echo "TEST";
	var_dump($headers);
	
	if ((!empty($headers[HEADER_NAME])) && (!empty($headers[HEADER_PASSWORD]))) {
		switch ($httpMethod) {
			case "GET" :
			case "POST" :
			case "PUT" :
			case "DELETE" :
				$action = ACTION_AUTH_USER;
				break;
		}
	}

	
	$model = new UserModel (); // common model
	$controller = new UserController ( $model, $action, $app, $headers );
	$view = new jsonView ( $controller, $model, $app, $app->request->headers ); // common view
	//TODO: Read username and password from headers
	
	//authenticate the user
	
	//if user is authenticated
//	return true;
	
	//else if not
	return false;
}

//$app->map ( "/users(/:id)", "authenticate", function ($userID = null) use($app) {
$app->map ( "/users(/:id)", function ($userID = null) use($app) {
	
	$body = $app->request->getBody(); // get the body of the HTTP request (from client)
	$parameters = json_decode($body, true); // this transform the string into an associative array
	$httpMethod = $app->request->getMethod();
	$action = null;
	$parameters[COLUMN_ID] = $userID; // prepare parameters to be passed to the controller (example: ID)

	if (($userID == null) or is_numeric ( $userID )) {
		switch ($httpMethod) {
			case "GET" :
				if ($userID != null)
					$action = ACTION_GET_USER;
				else
					$action = ACTION_GET_USERS;
				break;
			case "POST" :
				$action = ACTION_CREATE_USER;
				break;
			case "PUT" :
				$action = ACTION_UPDATE_USER;
				break;
			case "DELETE" :
				$action = ACTION_DELETE_USER;
				break;
			default :
		}
	}
	return new loadRunMVCComponents ( USER_MODEL, USER_CONTROLLER, USER_VIEW, $action, $app, $parameters );
} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/search/users/:string", function ($searchString = null) use($app) {
	
	$httpMethod = $app->request->getMethod();
	$action = null;
	$parameters[COLUMN_SEARCHSTRING] = $searchString; // prepare parameters to be passed to the controller

	if (is_string($searchString)) {
		switch ($httpMethod) {
			case "GET" :
				$action = ACTION_SEARCH_USERS;
				break;
			default :
		}
	}
	return new loadRunMVCComponents ( USER_MODEL, USER_CONTROLLER, USER_VIEW, $action, $app, $parameters );
} )->via ( "GET" );

$app->map ( "/search/users/username/:string", function ($searchString = null) use($app) {
	
	$httpMethod = $app->request->getMethod();
	$action = null;
	$parameters[COLUMN_SEARCHSTRING] = $searchString; // prepare parameters to be passed to the controller

	if (is_string($searchString)) {
		switch ($httpMethod) {
			case "GET" :
				$action = ACTION_SEARCH_USERS_BY_USERNAME;
				break;
			default :
		}
	}
	return new loadRunMVCComponents ( USER_MODEL, USER_CONTROLLER, USER_VIEW, $action, $app, $parameters );
} )->via ( "GET" );

$app->map ( "/search/users/name/:string", function ($searchString = null) use($app) {
	
	$httpMethod = $app->request->getMethod();
	$action = null;
	$parameters[COLUMN_SEARCHSTRING] = $searchString; // prepare parameters to be passed to the controller

	if (is_string($searchString)) {
		switch ($httpMethod) {
			case "GET" :
				$action = ACTION_SEARCH_USERS_BY_NAME;
				break;
			default :
		}
	}
	return new loadRunMVCComponents ( USER_MODEL, USER_CONTROLLER, USER_VIEW, $action, $app, $parameters );
} )->via ( "GET" );

$app->map ( "/search/users/surname/:string", function ($searchString = null) use($app) {
	
	$httpMethod = $app->request->getMethod();
	$action = null;
	$parameters[COLUMN_SEARCHSTRING] = $searchString; // prepare parameters to be passed to the controller

	if (is_string($searchString)) {
		switch ($httpMethod) {
			case "GET" :
				$action = ACTION_SEARCH_USERS_BY_SURNAME;
				break;
			default :
		}
	}
	return new loadRunMVCComponents ( USER_MODEL, USER_CONTROLLER, USER_VIEW, $action, $app, $parameters );
} )->via ( "GET" );

$app->map ( "/search/users/email/:string", function ($searchString = null) use($app) {
	
	$httpMethod = $app->request->getMethod();
	$action = null;
	$parameters[COLUMN_SEARCHSTRING] = $searchString; // prepare parameters to be passed to the controller

	if (is_string($searchString)) {
		switch ($httpMethod) {
			case "GET" :
				$action = ACTION_SEARCH_USERS_BY_EMAIL;
				break;
			default :
		}
	}
	return new loadRunMVCComponents ( USER_MODEL, USER_CONTROLLER, USER_VIEW, $action, $app, $parameters );
} )->via ( "GET" );

$app->run ();
class loadRunMVCComponents {
	public $model, $controller, $view;
	public function __construct($modelName, $controllerName, $viewName, $action, $app, $parameters = null) {
		include_once "models/" . USER_MODEL . ".php";
		include_once "controllers/" . USER_CONTROLLER . ".php";
		include_once "views/" . USER_VIEW . ".php";
		
		$model = new $modelName (); // common model
		$controller = new $controllerName ( $model, $action, $app, $parameters );
		$view = new $viewName ( $controller, $model, $app, $app->headers ); // common view
		$view->output (); // this returns the response to the requesting client
	}
}

?>