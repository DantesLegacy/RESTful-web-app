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

$app->map ( "/artists(/:id)", function ($artistID = null) use($app) {
	
	$body = $app->request->getBody(); // get the body of the HTTP request (from client)
	$parameters = json_decode($body, true); // this transform the string into an associative array
	$httpMethod = $app->request->getMethod();
	$action = null;
	$parameters[COLUMN_ID] = $artistID; // prepare parameters to be passed to the controller (example: ID)

	if (($artistID == null) or is_numeric ( $artistID )) {
		switch ($httpMethod) {
			case "GET" :
				if ($artistID != null)
					$action = ACTION_GET_ARTIST;
				else
					$action = ACTION_GET_ARTISTS;
				break;
			case "POST" :
				$action = ACTION_CREATE_ARTIST;
				break;
			case "PUT" :
				$action = ACTION_UPDATE_ARTIST;
				break;
			case "DELETE" :
				$action = ACTION_DELETE_ARTIST;
				break;
			default :
		}
	}
	return new loadRunMVCComponents ( USER_MODEL, USER_CONTROLLER, USER_VIEW, $action, $app, $parameters );
} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/search/artists/name/:string", function ($searchString = null) use($app) {
	
	$httpMethod = $app->request->getMethod();
	$action = null;
	$parameters[COLUMN_SEARCHSTRING] = $searchString; // prepare parameters to be passed to the controller

	if (is_string($searchString)) {
		switch ($httpMethod) {
			case "GET" :
				$action = ACTION_SEARCH_ARTISTS_BY_NAME;
				break;
			default :
		}
	}
	return new loadRunMVCComponents ( USER_MODEL, USER_CONTROLLER, USER_VIEW, $action, $app, $parameters );
} )->via ( "GET" );

$app->map ( "/albums(/:id)", function ($albumID = null) use($app) {
	
	$body = $app->request->getBody(); // get the body of the HTTP request (from client)
	$parameters = json_decode($body, true); // this transform the string into an associative array
	$httpMethod = $app->request->getMethod();
	$action = null;
	$parameters[COLUMN_ID] = $albumID; // prepare parameters to be passed to the controller (example: ID)

	if (($albumID == null) or is_numeric ( $albumID )) {
		switch ($httpMethod) {
			case "GET" :
				if ($albumID != null)
					$action = ACTION_GET_ALBUM;
				else
					$action = ACTION_GET_ALBUMS;
				break;
			case "POST" :
				$action = ACTION_CREATE_ALBUM;
				break;
			case "PUT" :
				$action = ACTION_UPDATE_ALBUM;
				break;
			case "DELETE" :
				$action = ACTION_DELETE_ALBUM;
				break;
			default :
		}
	}
	return new loadRunMVCComponents ( USER_MODEL, USER_CONTROLLER, USER_VIEW, $action, $app, $parameters );
} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/search/albums/name/:string", function ($searchString = null) use($app) {
	
	$httpMethod = $app->request->getMethod();
	$action = null;
	$parameters[COLUMN_SEARCHSTRING] = $searchString; // prepare parameters to be passed to the controller

	if (is_string($searchString)) {
		switch ($httpMethod) {
			case "GET" :
				$action = ACTION_SEARCH_ALBUMS_BY_NAME;
				break;
			default :
		}
	}
	return new loadRunMVCComponents ( USER_MODEL, USER_CONTROLLER, USER_VIEW, $action, $app, $parameters );
} )->via ( "GET" );

$app->map ( "/tracks(/:id)", function ($trackID = null) use($app) {
	
	$body = $app->request->getBody(); // get the body of the HTTP request (from client)
	$parameters = json_decode($body, true); // this transform the string into an associative array
	$httpMethod = $app->request->getMethod();
	$action = null;
	$parameters[COLUMN_ID] = $trackID; // prepare parameters to be passed to the controller (example: ID)

	if (($trackID == null) or is_numeric ( $trackID )) {
		switch ($httpMethod) {
			case "GET" :
				if ($trackID != null)
					$action = ACTION_GET_TRACK;
				else
					$action = ACTION_GET_TRACKS;
				break;
			case "POST" :
				$action = ACTION_CREATE_TRACK;
				break;
			case "PUT" :
				$action = ACTION_UPDATE_TRACK;
				break;
			case "DELETE" :
				$action = ACTION_DELETE_TRACK;
				break;
			default :
		}
	}
	return new loadRunMVCComponents ( USER_MODEL, USER_CONTROLLER, USER_VIEW, $action, $app, $parameters );
} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/search/tracks/name/:string", function ($searchString = null) use($app) {
	
	$httpMethod = $app->request->getMethod();
	$action = null;
	$parameters[COLUMN_SEARCHSTRING] = $searchString; // prepare parameters to be passed to the controller

	if (is_string($searchString)) {
		switch ($httpMethod) {
			case "GET" :
				$action = ACTION_SEARCH_TRACKS_BY_NAME;
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