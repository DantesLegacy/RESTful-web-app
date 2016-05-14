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
 * definition of the Albums DAO (database access object)
 */
class AlbumsDAO {
	private $dbManager;
	function AlbumsDAO($DBMngr) {
		$this->dbManager = $DBMngr;
	}
	public function get($id = null) {
		
		$sql = "SELECT * ";
		$sql .= "FROM albums ";
		if ($id != null)
			$sql .= "WHERE albums.id=? ";
		$sql .= "ORDER BY albums.name ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $id, $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	public function insert($parametersArray) {
		// insertion assumes that all the required parameters are defined and set
		$sql = "INSERT INTO albums (name, artist_id) ";
		$sql .= "VALUES (?,?) ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray [COLUMN_NAME], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray [COLUMN_ARTIST_ID], $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		
		return ($this->dbManager->getLastInsertedID ());
	}
	public function update($albumID, $parametersArray) {
		$count = $nameCount = $artistIdCount = 0;
		/* Prepare the statement */
		$sql = "UPDATE albums SET ";
		if(array_key_exists(COLUMN_NAME, $parametersArray)) {
			if(is_string($parametersArray[COLUMN_NAME])) {
				$sql .= "name = ?";
				$count++;
				$nameCount = $count;
			}
		}
		if(array_key_exists(COLUMN_ARTIST_ID, $parametersArray)) {
			if(is_string($parametersArray[COLUMN_ARTIST_ID])) {
				if ($count > 0)
					$sql .= ", ";
				$sql .= "artist_id = ?";
				$count++;
				$artistIdCount = $count;
			}
		}

		$sql .= " WHERE id = ?";
		$stmt = $this->dbManager->prepareQuery($sql);
		
		/* Bind the values to the statement */
		if(array_key_exists(COLUMN_NAME, $parametersArray)) {
			if(is_string($parametersArray[COLUMN_NAME])) {
				$this->dbManager->bindValue($stmt, $nameCount,
					$parametersArray[COLUMN_NAME],$this->dbManager->STRING_TYPE);
			}
		}
		if(array_key_exists(COLUMN_ARTIST_ID, $parametersArray)) {
			if(is_string($parametersArray[COLUMN_ARTIST_ID])) {
				$this->dbManager->bindValue($stmt, $artistIdCount,
					$parametersArray[COLUMN_ARTIST_ID],$this->dbManager->INT_TYPE);
			}
		}
		$this->dbManager->bindValue($stmt, ($count + 1), $albumID, $this->dbManager->INT_TYPE);
		/* Execute the query */
		$this->dbManager->executeQuery($stmt);
		return ($this->dbManager->getNumberOfAffectedRows($stmt));
	}
	public function delete($id) {
		/* Prepare the query */
		$sql = "DELETE from albums WHERE id = ?";
		$stmt = $this->dbManager->prepareQuery($sql);
		/* Bind values to query */
		$this->dbManager->bindValue($stmt, 1, $id, $this->dbManager->STRING_TYPE);
		/* Execute the query */
		$this->dbManager->executeQuery($stmt);
		/* Return results */
		return ($this->dbManager->getNumberOfAffectedRows($stmt));
	}
	public function searchAlbumsByName($name) {
		/* Prepare the statement */
		$sql = "SELECT * FROM `albums` WHERE (`name` LIKE ?);";
		
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
