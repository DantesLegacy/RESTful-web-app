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

/**
 * @author Luca
 * definition of the User DAO (database access object)
 */
class UsersDAO {
	private $dbManager;
	function UsersDAO($DBMngr) {
		$this->dbManager = $DBMngr;
	}
	public function hashPassword ($plaintextPassword) {
		// generate a 16-character salt string
		$salt = substr(str_replace('+','.',base64_encode(md5(mt_rand(), true))),0,16);
		// how many times the string will be hashed
		$rounds = 10000;
		// pass in the password, the number of rounds, and the salt
		// $5$ specifies SHA256-CRYPT, use $6$ if you really want SHA512
		return crypt($plaintextPassword, sprintf('$5$rounds=%d$%s$', $rounds, $salt));
		// output: $5$rounds=10000$3ES3C7XZpT7WQIuC$BEKSvZv./Y3b4ZyWLqq4BfIJzVHQweHqGBukFmo5MI8
	}
	public function optimize( $config )
	{
	  foreach ( $config as $key => $value ) 
	    if( is_array( $value ) && count( $value ) == 1 && isset( $value[0] ))
	       $config[$key] = $value[0];              
	
	  return $config;
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
		$sql = "INSERT INTO users (username, password, name, surname, email) ";
		$sql .= "VALUES (?,?,?,?,?) ";
		
		/*
		 * TODO: Check length of parameters from config file constants
		 */
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray [COLUMN_USERNAME], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $this->hashPassword($parametersArray [COLUMN_PASSWORD]), $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray [COLUMN_NAME], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray [COLUMN_SURNAME], $this->dbManager->STRING_TYPE );
		/* Hash the password */
		
		$this->dbManager->bindValue ( $stmt, 5, $parametersArray [COLUMN_EMAIL], $this->dbManager->STRING_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		
		return ($this->dbManager->getLastInsertedID ());
	}
	public function update($userID, $parametersArray) {
		$count = $usernameCount = $nameCount = $surnameCount = $emailCount = $passwordCount = 0;
		/* Prepare the statement */
		$sql = "UPDATE users SET ";
		if(array_key_exists(COLUMN_USERNAME, $parametersArray)) {
			if(is_string($parametersArray[COLUMN_USERNAME])) {
				$sql .= "username = ?";
				$count++;
				$usernameCount = $count;
			}
		}
		if(array_key_exists(COLUMN_PASSWORD, $parametersArray)) {
			if(is_string($parametersArray[COLUMN_PASSWORD])) {
				if ($count > 0)
					$sql .= ", ";
				$sql .= "password = ?";
				$count++;
				$passwordCount = $count;
			}
		}
		if(array_key_exists(COLUMN_NAME, $parametersArray)) {
			if(is_string($parametersArray[COLUMN_NAME])) {
				if ($count > 0)
					$sql .= ", ";
				$sql .= "name = ?";
				$count++;
				$nameCount = $count;
			}
		}
		if(array_key_exists(COLUMN_SURNAME, $parametersArray)) {
			if(is_string($parametersArray[COLUMN_SURNAME])) {
				if ($count > 0)
					$sql .= ", ";
				$sql .= "surname = ?";
				$count++;
				$surnameCount = $count;
			}
		}
		if(array_key_exists(COLUMN_EMAIL, $parametersArray)) {
			if(is_string($parametersArray[COLUMN_EMAIL])) {
				if ($count > 0)
					$sql .= ", ";
				$sql .= "email = ?";
				$count++;
				$emailCount = $count;
			}
		}

		$sql .= " WHERE id = ?";
		$stmt = $this->dbManager->prepareQuery($sql);
		
		/* Bind the values to the statement */
		if(array_key_exists(COLUMN_USERNAME, $parametersArray)) {
			if(is_string($parametersArray[COLUMN_USERNAME])) {
				$this->dbManager->bindValue($stmt, $usernameCount,
					$parametersArray[COLUMN_USERNAME], $this->dbManager->STRING_TYPE);
			}
		}
		if(array_key_exists(COLUMN_PASSWORD, $parametersArray)) {
			if(is_string($parametersArray[COLUMN_PASSWORD])) {
				$this->dbManager->bindValue($stmt, $passwordCount,
					$this->hashPassword($parametersArray[COLUMN_PASSWORD]),
					$this->dbManager->STRING_TYPE);
			}
		}
		if(array_key_exists(COLUMN_NAME, $parametersArray)) {
			if(is_string($parametersArray[COLUMN_NAME])) {
				$this->dbManager->bindValue($stmt, $nameCount,
					$parametersArray[COLUMN_NAME],$this->dbManager->STRING_TYPE);
			}
		}
		if(array_key_exists(COLUMN_SURNAME, $parametersArray)) {
			if(is_string($parametersArray[COLUMN_SURNAME])) {	
				$result = $this->dbManager->bindValue($stmt, $surnameCount,
					$parametersArray[COLUMN_SURNAME], $this->dbManager->STRING_TYPE);
			}
		}
		if(array_key_exists(COLUMN_EMAIL, $parametersArray)) {
			if(is_string($parametersArray[COLUMN_EMAIL])) {
				$this->dbManager->bindValue($stmt, $emailCount,
					$parametersArray[COLUMN_EMAIL], $this->dbManager->STRING_TYPE);
			}
		}
		$this->dbManager->bindValue($stmt, ($count + 1), $userID, $this->dbManager->STRING_TYPE);
		/* Execute the query */
		$this->dbManager->executeQuery($stmt);
		return ($this->dbManager->getNumberOfAffectedRows($stmt));
	}
	public function delete($id) {
		/* Prepare the query */
		$sql = "DELETE from users WHERE id = ?";
		$stmt = $this->dbManager->prepareQuery($sql);
		/* Bind values to query */
		$this->dbManager->bindValue($stmt, 1, $id, $this->dbManager->STRING_TYPE);
		/* Execute the query */
		$this->dbManager->executeQuery($stmt);
		/* Return results */
		return ($this->dbManager->getNumberOfAffectedRows($stmt));
	}
	public function search($searchString) {
		/* Prepare the statement */
		$sql = "SELECT * FROM `users` WHERE (`username` LIKE ? OR `name` LIKE ? OR `surname` LIKE ? OR `email` LIKE ?);";
		
		$stmt = $this->dbManager->prepareQuery($sql);
		
		$bindString = "%" . $searchString . "%";
		
		$this->dbManager->bindValue($stmt, 1, $bindString, $this->dbManager->STRING_TYPE);
		$this->dbManager->bindValue($stmt, 2, $bindString, $this->dbManager->STRING_TYPE);
		$this->dbManager->bindValue($stmt, 3, $bindString, $this->dbManager->STRING_TYPE);
		$this->dbManager->bindValue($stmt, 4, $bindString, $this->dbManager->STRING_TYPE);
				
		$this->dbManager->executeQuery($stmt);
		
		/* Return results */
		$rows = $this->dbManager->fetchResults($stmt);
		
		return ($rows);
	}
	public function searchUsersByName($name) {
		/* Prepare the statement */
		$sql = "SELECT * FROM `users` WHERE (`name` LIKE ?);";
		
		$stmt = $this->dbManager->prepareQuery($sql);
		
		$bindString = "%" . $name . "%";
		
		$this->dbManager->bindValue($stmt, 1, $bindString, $this->dbManager->STRING_TYPE);
				
		$this->dbManager->executeQuery($stmt);
		
		/* Return results */
		$rows = $this->dbManager->fetchResults($stmt);
		
		return ($rows);
	}
	public function searchUsersByUsername($username) {
		/* Prepare the statement */
		$sql = "SELECT * FROM `users` WHERE (`username` LIKE ?);";
		
		$stmt = $this->dbManager->prepareQuery($sql);
		
		$bindString = "%" . $username . "%";
		
		$this->dbManager->bindValue($stmt, 1, $bindString, $this->dbManager->STRING_TYPE);
				
		$this->dbManager->executeQuery($stmt);
		
		/* Return results */
		$rows = $this->dbManager->fetchResults($stmt);
		
		return ($rows);
	}
	public function searchUsersBySurname($surname) {
		/* Prepare the statement */
		$sql = "SELECT * FROM `users` WHERE (`surname` LIKE ?);";
		
		$stmt = $this->dbManager->prepareQuery($sql);
		
		$bindString = "%" . $surname . "%";
		
		$this->dbManager->bindValue($stmt, 1, $bindString, $this->dbManager->STRING_TYPE);
				
		$this->dbManager->executeQuery($stmt);
		
		/* Return results */
		$rows = $this->dbManager->fetchResults($stmt);
		
		return ($rows);
	}
	public function searchUsersByEmail($email) {
		/* Prepare the statement */
		$sql = "SELECT * FROM `users` WHERE (`email` LIKE ?);";
		
		$stmt = $this->dbManager->prepareQuery($sql);
		
		$bindString = "%" . $email . "%";
		
		$this->dbManager->bindValue($stmt, 1, $bindString, $this->dbManager->STRING_TYPE);
				
		$this->dbManager->executeQuery($stmt);
		
		/* Return results */
		$rows = $this->dbManager->fetchResults($stmt);
		
		return ($rows);
	}
	public function authenticateUser($username, $password) {
		/* First get the password of the username */
		$sql = "SELECT password FROM `users` WHERE (username=?)";
		
		$stmt = $this->dbManager->prepareQuery($sql);
		$this->dbManager->bindValue($stmt, 1, $username, $this->dbManager->STRING_TYPE);
		$this->dbManager->executeQuery($stmt);
		$storedPassword = $this->dbManager->fetchResults($stmt);
		// extract the hashing method, number of rounds, and salt from the stored hash
		// and hash the password string accordingly
		$storedPasswordString = array_shift($storedPassword);
		$parts = explode('$', $storedPasswordString[COLUMN_PASSWORD]);
		$testHash = crypt($password, sprintf('$%s$%s$%s$', $parts[1], $parts[2], $parts[3]));

		/* Now check if that password matches */
		$sql = "SELECT * FROM `users` WHERE (username=? AND password=?)";
		
		$stmt = $this->dbManager->prepareQuery($sql);
		
		$this->dbManager->bindValue($stmt, 1, $username, $this->dbManager->STRING_TYPE);
		$this->dbManager->bindValue($stmt, 2, $testHash, $this->dbManager->STRING_TYPE);
		
		$this->dbManager->executeQuery($stmt);
		
		$rows = $this->dbManager->fetchResults($stmt);
		
		return ($rows);
	}
}
?>
