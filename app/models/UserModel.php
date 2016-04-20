<?php
require_once "DB/pdoDbManager.php";
require_once "DB/DAO/UsersDAO.php";
require_once "Validation.php";
class UserModel {
	private $UsersDAO; // list of DAOs used by this model
	private $dbmanager; // dbmanager
	public $apiResponse; // api response
	private $validationSuite; // contains functions for validating inputs
	public function __construct() {
		$this->dbmanager = new pdoDbManager ();
		$this->UsersDAO = new UsersDAO ( $this->dbmanager );
		$this->dbmanager->openConnection ();
		$this->validationSuite = new Validation ();
	}
	public function getUsers() {
		return ($this->UsersDAO->get ());
	}
	public function getUser($userID) {
		if (is_numeric ( $userID ))
			return ($this->UsersDAO->get ( $userID ));
		
		return false;
	}
	/**
	 *
	 * @param array $UserRepresentation:
	 *        	an associative array containing the detail of the new user
	 */
	public function createNewUser($newUser) {
		// validation of the values of the new user
		// compulsory values
		if (! empty ( $newUser [COLUMN_NAME] ) && ! empty ( $newUser [COLUMN_SURNAME] )
			&& ! empty ( $newUser [COLUMN_EMAIL] ) && ! empty ( $newUser [COLUMN_PASSWORD] )) {
			/*
			 * the model knows the representation of a user in the database and this is:
			 * name: varchar(25) surname: varchar(25) email: varchar(50) password: varchar(40)
			 */
			if (($this->validationSuite->isLengthStringValid ( $newUser [COLUMN_NAME], TABLE_USER_NAME_LENGTH ))
				&& ($this->validationSuite->isLengthStringValid ( $newUser [COLUMN_SURNAME], TABLE_USER_SURNAME_LENGTH ))
				&& ($this->validationSuite->isLengthStringValid ( $newUser [COLUMN_EMAIL], TABLE_USER_EMAIL_LENGTH ))
				&& ($this->validationSuite->isLengthStringValid ( $newUser [COLUMN_PASSWORD], TABLE_USER_PASSWORD_LENGTH ))) {
				if ($newId = $this->UsersDAO->insert ( $newUser ))
					return ($newId);
			}
		}
		// if validation fails or insertion fails
		return (false);
	}
	public function updateUsers($userID, $userNewRepresentation) {
		/* Validate the fields being entered for update of user */
		/* If field is not empty and does not fit DB limits */
		if ((!empty($userNewRepresentation[COLUMN_NAME])
				&& (!$this->validationSuite->isLengthStringValid($userNewRepresentation[COLUMN_NAME], TABLE_USER_NAME_LENGTH)))
			|| (!empty($userNewRepresentation[COLUMN_SURNAME])
				&& (!$this->validationSuite->isLengthStringValid($userNewRepresentation[COLUMN_SURNAME], TABLE_USER_SURNAME_LENGTH)))
			|| (!empty($userNewRepresentation[COLUMN_EMAIL])
				&& (!$this->validationSuite->isLengthStringValid($userNewRepresentation[COLUMN_EMAIL], TABLE_USER_EMAIL_LENGTH))
				/* NOTE: Checking the validity of the email address in the Model Layer
				 * as we don't want invalid data entering the database. It does not fit
				 * in the Controller Layer as the format of the data is not it's concern */
				&& (!$this->validationSuite->isEmailValid($userNewRepresentation[COLUMN_EMAIL])))
			|| (!empty($userNewRepresentation[COLUMN_PASSWORD])
				&& (!$this->validationSuite->isLengthStringValid($userNewRepresentation[COLUMN_PASSWORD], TABLE_USER_PASSWORD_LENGTH))))
			return false;
			
		return ($this->UsersDAO->update($userID, $userNewRepresentation));
	}
	public function searchUsers($searchUserStr) {
		/* If field is not empty and does not fit DB limits */
		if ((!empty($searchUserStr[COLUMN_NAME])
				&& (!$this->validationSuite->isLengthStringValid($userNewRepresentation[COLUMN_NAME], TABLE_USER_NAME_LENGTH)))
			|| (!empty($userNewRepresentation[COLUMN_SURNAME])
				&& (!$this->validationSuite->isLengthStringValid($userNewRepresentation[COLUMN_SURNAME], TABLE_USER_SURNAME_LENGTH))))
			return false;	
			
		return ($this->UsersDAO->search($searchUserStr));
	}
	public function deleteUser($userID) {
		return ($this->UsersDAO->delete($userID));
	}
	public function __destruct() {
		$this->UsersDAO = null;
		$this->dbmanager->closeConnection ();
	}
}
?>