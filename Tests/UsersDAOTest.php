<?php
/**
 * @author Joseph Mcnally
 * Test class to test Albums DAO functions
 */

require_once('../SimpleTest/autorun.php');
require_once "../app/conf/config.inc.php";

class UsersDAOTests extends UnitTestCase {
	private $UsersDAO;
	private $AlbumsDAO;
	private $ArtistsDAO;
	private $TracksDAO;
	private $dbmanager; // dbmanager
	private $validationSuite; // contains functions for validating inputs
	
	public function setUp(){
		require_once '../app/DB/pdoDbManager.php';
		require_once '../app/DB/DAO/UsersDAO.php';
		require_once '../app/DB/DAO/ArtistsDAO.php';
		require_once '../app/DB/DAO/AlbumsDAO.php';
		require_once '../app/DB/DAO/TracksDAO.php';
		require_once '../app/models/Validation.php';
		$this->dbmanager = new pdoDbManager ();
		$this->UsersDAO = new UsersDAO ( $this->dbmanager );
		$this->AlbumsDAO = new AlbumsDAO ( $this->dbmanager );
		$this->ArtistsDAO = new ArtistsDAO ( $this->dbmanager );
		$this->TracksDAO = new TracksDAO ( $this->dbmanager );
		$this->dbmanager->openConnection ();
		$this->validationSuite = new Validation ();
	}
	
	public function tearDown(){
		$this->dbmanager->closeConnection ();
		$this->dbmanager = NULL;
		$this->UsersDAO = NULL;
		$this->AlbumsDAO = NULL;
		$this->ArtistsDAO = NULL;
		$this->TracksDAO = NULL;
		$this->validationSuite = NULL;
	}
	
	public function testGet() {
		$this->assertNotNull($this->UsersDAO->get());
		$this->assertNotNull($this->ArtistsDAO->get());
		$this->assertNotNull($this->AlbumsDAO->get());
		$this->assertNotNull($this->TracksDAO->get());
		$this->assertNotNull($this->UsersDAO->get("3"));
		$this->assertNotNull($this->ArtistsDAO->get("2"));
		$this->assertNotNull($this->AlbumsDAO->get("2"));
		$this->assertNotNull($this->TracksDAO->get("3"));
	}
	
	
}
?>