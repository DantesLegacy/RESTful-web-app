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
			/* User actions */
			case ACTION_GET_USER :
				$this->getTableEntry (USER_TABLE, $id );
				break;
			case ACTION_GET_USERS :
				$this->getAllTableEntries (USER_TABLE);
				break;
			case ACTION_UPDATE_USER :
				$this->updateTableEntry (USER_TABLE, $id, $this->requestBody );
				break;
			case ACTION_CREATE_USER :
				$this->createNewTableEntry (USER_TABLE,  $this->requestBody );
				break;
			case ACTION_DELETE_USER :
				$this->deleteTableEntry (USER_TABLE, $id );
				break;
			case ACTION_SEARCH_USERS :
				$string = $parameters[COLUMN_SEARCHSTRING];
				$this->searchUsers($string);
				break;
			case ACTION_SEARCH_USERS_BY_USERNAME :
				$string = $parameters[COLUMN_SEARCHSTRING];
				$this->searchUsersByUsername($string);
				break;
			case ACTION_SEARCH_USERS_BY_NAME :
				$string = $parameters[COLUMN_SEARCHSTRING];
				$this->searchTableByName(USER_TABLE, $string);
				break;
			case ACTION_SEARCH_USERS_BY_SURNAME :
				$string = $parameters[COLUMN_SEARCHSTRING];
				$this->searchUsersBySurname($string);
				break;
			case ACTION_SEARCH_USERS_BY_EMAIL :
				$string = $parameters[COLUMN_SEARCHSTRING];
				$this->searchUsersByEmail($string);
				break;	
			case ACTION_AUTH_USER :
				$username = $parameters[HEADER_USERNAME];
				$password = $parameters[HEADER_PASSWORD];
				$this->authUser($username, $password);
				break;
			/* Artist actions */
			case ACTION_GET_ARTIST :
				$this->getTableEntry (ARTIST_TABLE, $id );
				break;
			case ACTION_GET_ARTISTS :
				$this->getAllTableEntries (ARTIST_TABLE);
				break;
			case ACTION_UPDATE_ARTIST :
				$this->updateTableEntry (ARTIST_TABLE, $id, $this->requestBody );
				break;
			case ACTION_CREATE_ARTIST :
				$this->createNewTableEntry (ARTIST_TABLE, $this->requestBody );
				break;
			case ACTION_DELETE_ARTIST :
				$this->deleteTableEntry (ARTIST_TABLE, $id );
				break;
			case ACTION_SEARCH_ARTISTS_BY_NAME :
				$string = $parameters[COLUMN_SEARCHSTRING];
				$this->searchTableByName(ARTIST_TABLE, $string);
				break;
			/* Album actions */
			case ACTION_GET_ALBUM :
				$this->getTableEntry (ALBUM_TABLE, $id );
				break;
			case ACTION_GET_ALBUMS :
				$this->getAllTableEntries (ALBUM_TABLE);
				break;
			case ACTION_UPDATE_ALBUM :
				$this->updateTableEntry (ALBUM_TABLE, $id, $this->requestBody );
				break;
			case ACTION_CREATE_ALBUM :
				$this->createNewTableEntry (ALBUM_TABLE, $this->requestBody );
				break;
			case ACTION_DELETE_ALBUM :
				$this->deleteTableEntry (ALBUM_TABLE, $id );
				break;
			case ACTION_SEARCH_ALBUMS_BY_NAME :
				$string = $parameters[COLUMN_SEARCHSTRING];
				$this->searchTableByName(ALBUM_TABLE, $string);
				break;
			/* Track actions */
			case ACTION_GET_TRACK :
				$this->getTableEntry (TRACK_TABLE, $id );
				break;
			case ACTION_GET_TRACKS :
				$this->getAllTableEntries (TRACK_TABLE);
				break;
			case ACTION_UPDATE_TRACK :
				$this->updateTableEntry (TRACK_TABLE, $id, $this->requestBody );
				break;
			case ACTION_CREATE_TRACK :
				$this->createNewTableEntry (TRACK_TABLE, $this->requestBody );
				break;
			case ACTION_DELETE_TRACK :
				$this->deleteTableEntry (TRACK_TABLE, $id );
				break;
			case ACTION_SEARCH_TRACKS_BY_NAME :
				$string = $parameters[COLUMN_SEARCHSTRING];
				$this->searchTableByName(TRACK_TABLE, $string);
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
	
	private function set_getTableEntriesResponse($answer) {
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
	
	private function set_createNewTableEntryResponse($newID) {
		if ($newID) {
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
	
	private function set_deleteTableEntryResponse($answer) {
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
	
	private function set_updateTableEntryResponse($answer) {
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
	
	private function set_searchTableResponse($answer, $responseMessage) {
		if ($answer != NULL) {
			$this->slimApp->response()->setStatus(HTTPSTATUS_OK);
			$this->model->apiResponse = $answer;
		} else {
			$this->slimApp->response()->setStatus(HTTPSTATUS_NOCONTENT);
			$Message = array(
				GENERAL_ERROR_MESSAGE => $responseMessage
			);
			$this->model->apiResponse = $Message;
		}
	}
	
	private function getAllTableEntries($tableName) {
		$answer = $this->model->getTableEntry($tableName, NULL);
		$this->set_getTableEntriesResponse($answer);
	}
	
	private function getTableEntry($tableName, $id) {
		$answer = $this->model->getTableEntry($tableName, $id);
		$this->set_getTableEntriesResponse($answer);
	}
	
	private function createNewTableEntry($tableName, $tableEntry) {
		switch ($tableName) {
			case USER_TABLE :
				$newID = $this->model->createNewUser ($tableEntry);
				break;
			case ARTIST_TABLE :
				$newID = $this->model->createNewArtist ($tableEntry);
				break;
			case ALBUM_TABLE :
				$newID = $this->model->createNewAlbum ($tableEntry);
				break;
			case TRACK_TABLE :
				$newID = $this->model->createNewTrack ($tableEntry);
				break;
		}
		$this->set_createNewTableEntryResponse($newID);
	}

	private function deleteTableEntry($tableName, $id) {
		$answer = $this->model->deleteTableEntry($tableName, $id);
		$this->set_deleteTableEntryResponse($answer);
	}
	
	private function updateTableEntry ($tableName, $id, $entryNewRepresentation) {
		switch ($tableName) {
			case USER_TABLE :
				$answer = $this->model->updateUsers($id, $entryNewRepresentation);
				break;
			case ARTIST_TABLE :
				$answer = $this->model->updateArtists($id, $entryNewRepresentation);
				break;
			case ALBUM_TABLE :
				$answer = $this->model->updateAlbums($id, $entryNewRepresentation);
				break;
			case TRACK_TABLE :
				$answer = $this->model->updateTracks($id, $entryNewRepresentation);
				break;
		}
		$this->set_updateTableEntryResponse($answer);
	}
	
	//TODO: search for user by name, surname, etc
	//TODO: add parameter for number of results needed
	private function searchUsers($string) {
		$answer = $this->model->searchUsers($string);
		$this->set_searchTableResponse($answer, GENERAL_SEARCH_ERROR);
	}
	
	private function searchUsersByUsername($string) {
		$answer = $this->model->searchUsersByUsername($string);
		$this->set_searchTableResponse($answer, GENERAL_SEARCH_ERROR_USERNAME);
	}
	
	private function searchTableByName ($tableName, $string) {
		$answer = $this->model->searchTableByName($tableName, $string);
		$this->set_searchTableResponse($answer, GENERAL_SEARCH_ERROR_NAME);
	}
	
	private function searchUsersBySurname($string) {
		$answer = $this->model->searchUsersBySurname($string);
		$this->set_searchTableResponse($answer, GENERAL_SEARCH_ERROR_SURNAME);
	}
	
	private function searchUsersByEmail($string) {
		$answer = $this->model->searchUsersByEmail($string);
		$this->set_searchTableResponse($answer, GENERAL_SEARCH_ERROR_EMAIL);
	}
	
	private function authUser($username, $password) {
		$answer = $this->model->authUser($username, $password);
		if ($answer == true) {
			$this->slimApp->response()->setStatus(HTTPSTATUS_OK);
			$this->model->apiResponse = $answer;
		} else {
//			$this->slimApp->apiResponse()->setStatus(HTTPSTATUS_UNAUTHORIZED);
			$this->slimApp->halt(HTTPSTATUS_UNAUTHORIZED);
			$Message = array(
				GENERAL_ERROR_MESSAGE => GENERAL_UNAUTHORISED_USER
			);
		}
		echo "TEST AUTH";
	}
}
?>