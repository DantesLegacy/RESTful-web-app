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
 * @author Joseph McNally
 * definition of the Artists DAO (database access object)
 */
class ArtistsDAO {
	private $dbManager;
	function ArtistsDAO($DBMngr) {
		$this->dbManager = $DBMngr;
	}
	public function get($id = null) {
		
		$sql = "SELECT * ";
		$sql .= "FROM artists ";
		if ($id != null)
			$sql .= "WHERE artists.id=? ";
		$sql .= "ORDER BY artists.name ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $id, $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	public function insert($parametersArray) {
		// insertion assumes that all the required parameters are defined and set
		$sql = "INSERT INTO artists (name) ";
		$sql .= "VALUES (?) ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray [COLUMN_NAME], $this->dbManager->STRING_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		
		return ($this->dbManager->getLastInsertedID ());
	}
	public function update($artistID, $parametersArray) {
		/* Prepare the statement */
		$sql = "UPDATE artists SET ";
		$sql .= "name = ?";
		$sql .= " WHERE id = ?";
		$stmt = $this->dbManager->prepareQuery($sql);
		
		/* Bind the values to the statement */
		$this->dbManager->bindValue($stmt, 1,
					$parametersArray[COLUMN_NAME],$this->dbManager->STRING_TYPE);
		$this->dbManager->bindValue($stmt, 2, $artistID, $this->dbManager->STRING_TYPE);
		/* Execute the query */
		$this->dbManager->executeQuery($stmt);
		return ($this->dbManager->getNumberOfAffectedRows($stmt));
	}
	public function delete($id) {
		/* Prepare the query */
		$sql = "DELETE from artists WHERE id = ?";
		$stmt = $this->dbManager->prepareQuery($sql);
		/* Bind values to query */
		$this->dbManager->bindValue($stmt, 1, $id, $this->dbManager->STRING_TYPE);
		/* Execute the query */
		$this->dbManager->executeQuery($stmt);
		/* Return results */
		return ($this->dbManager->getNumberOfAffectedRows($stmt));
	}
	public function searchArtistsByName($name) {
		/* Prepare the statement */
		$sql = "SELECT * FROM `artists` WHERE (`name` LIKE ?);";
		
		$stmt = $this->dbManager->prepareQuery($sql);
		
		$bindString = "%" . $name . "%";
		
		$this->dbManager->bindValue($stmt, 1, $bindString, $this->dbManager->STRING_TYPE);
				
		$this->dbManager->executeQuery($stmt);
		
		/* Return results */
		$rows = $this->dbManager->fetchResults($stmt);
		
		return ($rows);
	}
}
?>
