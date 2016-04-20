<?php
/*
 * TODO: Create baseController class that extends this class in order to refactor code.
 */
class UserController {
	private $slimApp;
	private $model;
	private $requestBody;
	public function __construct($model, $action = null, $slimApp, $parameters = null) {
		$this->model = $model;
		$this->slimApp = $slimApp;
		$this->requestBody = json_decode ( $this->slimApp->request->getBody (), true ); // this must contain the representation of the new user
		
		if (! empty ( $parameters [COLUMN_ID] ))
			$id = $parameters [COLUMN_ID];
		
		switch ($action) {
			case ACTION_GET_USER :
				$this->getUser ( $id );
				break;
			case ACTION_GET_USERS :
				$this->getUsers ();
				break;
			case ACTION_UPDATE_USER :
				$this->updateUser ( $id, $this->requestBody );
				break;
			case ACTION_CREATE_USER :
				$this->createNewUser ( $this->requestBody );
				break;
			case ACTION_DELETE_USER :
				$this->deleteUser ( $id );
				break;
			case ACTION_SEARCH_USERS :
				$string = $parameters[COLUMN_SEARCHSTRING];
				$this->searchUsers($string);
				break;
			case null :
				$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
				$Message = array (
						GENERAL_MESSAGE_LABEL => GENERAL_CLIENT_ERROR 
				);
				$this->model->apiResponse = $Message;
				break;
		}
	}
	private function getUsers() {
		$answer = $this->model->getUsers ();
		if ($answer != null) {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$this->model->apiResponse = $answer;
		} else {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_NOCONTENT );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_NOCONTENT_MESSAGE 
			);
			$this->model->apiResponse = $Message;
		}
	}
	
	private function getUser($userID) {
		$answer = $this->model->getUser ( $userID );
		if ($answer != null) {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$this->model->apiResponse = $answer;
		} else {
			
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_NOCONTENT );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_NOCONTENT_MESSAGE 
			);
			$this->model->apiResponse = $Message;
		}
	}
	
	private function createNewUser($newUser) {
		if ($newID = $this->model->createNewUser ( $newUser )) {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_CREATED );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_RESOURCE_CREATED,
					COLUMN_ID => "$newID" 
			);
			$this->model->apiResponse = $Message;
		} else {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_INVALIDBODY 
			);
			$this->model->apiResponse = $Message;
		}
	}
	private function deleteUser($userId) {
		$answer = $this->model->deleteUser($userId);
		if ($answer != NULL) {
			/* Valid status codes for this are 200 or 204 */
			$this->slimApp->response()->setStatus(HTTPSTATUS_OK);
			$Message = array(
				GENERAL_SUCCESS_MESSAGE => GENERAL_RESOURCE_DELETED
			);
			$this->model->apiResponse = $Message;
		} else {
			$this->slimAppresponse()->setStatus(HTTPSTATUS_BADREQUEST);
			$Message = array(
				GENERAL_ERROR_MESSAGE => GENERAL_DELETE_ERROR
			);
			$this->model->apiResponse = $Message;
		}
	}
	
	private function updateUser($userId, $userNewRepresentation) {
		$answer = $this->model->updateUsers($userId, $userNewRepresentation);
		if ($answer != NULL) {
			/* Valid status codes for this are 200 or 204 */
			$this->slimApp->response()->setStatus(HTTPSTATUS_OK);
			$Message = array(
				GENERAL_SUCCESS_MESSAGE => GENERAL_RESOURCE_UPDATED
			);
			$this->model->apiResponse = $Message;
		} else {
			$this->slimApp->response()->setStatus(HTTPSTATUS_BADREQUEST);
			$Message = array(
				GENERAL_ERROR_MESSAGE => GENERAL_UPDATE_ERROR
			);
			$this->model->apiResponse = $Message;
		}
	}
	
	private function searchUsers($string) {
		$answer = $this->model->searchUsers($string);
		if ($answer != null) {
			$this->slimApp->response()->setStatus(HTTPSTATUS_OK);
			$this->model->apiResponse = $answer;
		} else {
			$this->slimApp->response()->setStatus(HTTPSTATUS_NOCONTENT);
			$Message = array(
				GENERAL_ERROR_MESSAGE => GENERAL_SEARCH_ERROR
			);
			$this->model->apiResponse = $Message;
		}
	}
}
?>