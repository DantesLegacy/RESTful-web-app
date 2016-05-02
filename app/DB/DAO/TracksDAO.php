<?php
/**
 * @author Joseph McNally
 * definition of the Tracks DAO (database access object)
 */
class TracksDAO {
	private $dbManager;
	function TracksDAO($DBMngr) {
		$this->dbManager = $DBMngr;
	}
	public function get($id = null) {
		
		$sql = "SELECT * ";
		$sql .= "FROM tracks ";
		if ($id != null)
			$sql .= "WHERE tracks.id=? ";
		$sql .= "ORDER BY tracks.name ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $id, $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	public function insert($parametersArray) {
		// insertion assumes that all the required parameters are defined and set
		$sql = "INSERT INTO tracks (name) ";
		$sql .= "VALUES (?) ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray [COLUMN_NAME], $this->dbManager->STRING_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		
		return ($this->dbManager->getLastInsertedID ());
	}
	public function update($trackID, $parametersArray) {
		/* Prepare the statement */
		$sql = "UPDATE tracks SET ";
		$sql .= "name = ?";
		$sql .= " WHERE id = ?";
		$stmt = $this->dbManager->prepareQuery($sql);
		
		/* Bind the values to the statement */
		$this->dbManager->bindValue($stmt, 1,
					$parametersArray[COLUMN_NAME],$this->dbManager->STRING_TYPE);
		$this->dbManager->bindValue($stmt, 2, $trackID, $this->dbManager->STRING_TYPE);
		/* Execute the query */
		$this->dbManager->executeQuery($stmt);
		return ($this->dbManager->getNumberOfAffectedRows($stmt));
	}
	public function delete($id) {
		/* Prepare the query */
		$sql = "DELETE from tracks WHERE id = ?";
		$stmt = $this->dbManager->prepareQuery($sql);
		/* Bind values to query */
		$this->dbManager->bindValue($stmt, 1, $id, $this->dbManager->STRING_TYPE);
		/* Execute the query */
		$this->dbManager->executeQuery($stmt);
		/* Return results */
		return ($this->dbManager->getNumberOfAffectedRows($stmt));
	}
	public function searchTracksByName($name) {
		/* Prepare the statement */
		$sql = "SELECT * FROM `tracks` WHERE (`name` LIKE ?);";
		
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