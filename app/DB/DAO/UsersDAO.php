<?php
/**
 * @author Luca
 * definition of the User DAO (database access object)
 */
class UsersDAO {
	private $dbManager;
	function UsersDAO($DBMngr) {
		$this->dbManager = $DBMngr;
	}
	public function get($id = null) {
		$sql = "SELECT * ";
		$sql .= "FROM users ";
		if ($id != null)
			$sql .= "WHERE users.id=? ";
		$sql .= "ORDER BY users.name ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $id, $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	public function insert($parametersArray) {
		// insertion assumes that all the required parameters are defined and set
		$sql = "INSERT INTO users (name, surname, email, password) ";
		$sql .= "VALUES (?,?,?,?) ";
		
		/*
		 * TODO: Check length of parameters from config file constants
		 */
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray [COLUMN_NAME], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray [COLUMN_SURNAME], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray [COLUMN_EMAIL], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray [COLUMN_PASSWORD], $this->dbManager->STRING_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		
		return ($this->dbManager->getLastInsertedID ());
	}
	public function update($userID, $parametersArray) {
		/* Prepare the statement */
		// TODO: Add count
		$sql = "UPDATE users SET ";
		if(is_string($parametersArray[COLUMN_NAME]))
			$sql .= "name = ?, ";
		if(is_string($parametersArray[COLUMN_SURNAME]))
			$sql .= "surname = ?, ";
		if(is_string($parametersArray[COLUMN_EMAIL]))
			$sql .= "email = ?, ";
		if(is_string($parametersArray[COLUMN_PASSWORD]))
			$sql .= "password = ?, ";
		$sql .= "WHERE id = ?";
		$stmt = $this->dbManager->prepareQuery($sql);
		
		/* Bind the values to the statement */
		if(is_string($parametersArray[COLUMN_NAME]))
			$this->dbManager->bindValue($stmt, 1,
				$parametersArray[COLUMN_NAME],$this->dbManager->STRING_TYPE);
		if(is_string($parametersArray[COLUMN_SURNAME]))	
			$this->dbManager->bindValue($stmt, 2,
				$parametersArray[COLUMN_SURNAME], $this->dbManager->STRING_TYPE);
		if(is_string($parametersArray[COLUMN_EMAIL]))
			$this->dbManager->bindValue($stmt, 3,
				$parametersArray[COLUMN_EMAIL], $this->dbManager->STRING_TYPE);
		if(is_string($parametersArray[COLUMN_PASSWORD]))
			$this->dbManager->bindValue($stmt, 4,
				$parametersArray[COLUMN_PASSWORD], $this->dbManager->STRING_TYPE);
		$this->dbManager->bindValue($stmt, 5, $userID, $this->dbManager->STRING_TYPE);
		/* Execute the query */
		$this->dbManager->executeQuery($stmt);
		
		return ($this->dbManager->fetchResults($stmt));
	}
	public function delete($userID) {
		/* Prepare the query */
		$stmt = $this->dbManager->prepareQuery("DELETE from users WHERE id = ?");
		/* Bind value to query */
		$this->dbManager->bindValue($stmt, 1, $userID, $this->dbManager->STRING_TYPE);
		/* Execute the query */
		$this->dbManager->executeQuery($stmt);
		/* Return results */
		return ($this->dbManager->fetchResults($stmt));
	}
	public function search($parametersArray) {
		/* Prepare the statement */
		$count = 0;
		/* Start of custom search string */
		$sql = "SELECT * FROM users WHERE ";
		/* If there is a name passed in */
		if(is_string($parametersArray[COLUMN_NAME])) {
			$sql .= "(name LIKE %?%) ";
			/* Set the flag as true */
			$searchParameterActive = true;
			/* Increment the count */
			$count++;
			/* This will be used when binding values to the statement later */
			$nameCount = $count;
		}
		if(is_string($parametersArray[COLUMN_SURNAME])) {
			if ($searchParameterActive)
				$sql .= "AND ";
			$sql .= "(surname LIKE %?%) ";
			$count++;
			$surnameCount = $count;
		}
		/*
		 * Not going to initialise this. Searching by email and password is not common
		 * Maybe for when user forgets password and needs to get account? Dunno.
		 * User should not have access to search by this.
		 */
//		if(is_string($parametersArray[COLUMN_EMAIL]))	
//		if(is_string($parametersArray[COLUMN_PASSWORD]))

		if($searchParameterActive) {
			$stmt = $this->dbManager->prepareQuery($sql);
			if(is_string($parametersArray[COLUMN_NAME]))
				$this->dbManager->bindValue($stmt, $nameCount, $parametersArray[COLUMN_NAME], $this->dbManager->STRING_TYPE);
			if(is_string($parametersArray[COLUMN_SURNAME]))
				$this->dbManager->bindValue($stmt, $surnameCount, $parametersArray[COLUMN_SURNAME], $this->dbManager->STRING_TYPE);
			/* Execute the query */
			$this->dbManager->executeQuery($stmt);
		}
		/* Return results */
		return ($this->dbManager->fetchResults($stmt));
	}
}
?>
