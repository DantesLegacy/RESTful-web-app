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
 * @author Joseph Mcnally
 * Test class to test Albums DAO functions
 */

require_once('../SimpleTest/autorun.php');
require_once 'conf/config.inc.php';
require_once 'models/UserModel.php';

class UserModelTests extends UnitTestCase {
	private $model;
	
	public function setUp() {
		$this->model = new UserModel();
	}
	
	public function tearDown() {
		$this->model = NULL;
	}
	
	public function testGet() {
		/* Get all entries in tables */
		$this->assertNotNull($this->model->getTableEntry(USER_TABLE, NULL));
		$this->assertNotNull($this->model->getTableEntry(ARTIST_TABLE, NULL));
		$this->assertNotNull($this->model->getTableEntry(ALBUM_TABLE, NULL));
		$this->assertNotNull($this->model->getTableEntry(TRACK_TABLE, NULL));
		/* Get valid specific entries */ 
		$this->assertNotNull($this->model->getTableEntry(USER_TABLE, "3"));
		$this->assertNotNull($this->model->getTableEntry(ARTIST_TABLE, "2"));
		$this->assertNotNull($this->model->getTableEntry(ALBUM_TABLE, "2"));
		$this->assertNotNull($this->model->getTableEntry(TRACK_TABLE, "2"));
		/* Get out-of-bounds entries */
		$this->assertTrue(empty($this->model->getTableEntry(USER_TABLE, "999999")));
		$this->assertTrue(empty($this->model->getTableEntry(ARTIST_TABLE, "999999")));
		$this->assertTrue(empty($this->model->getTableEntry(ALBUM_TABLE, "999999")));
		$this->assertTrue(empty($this->model->getTableEntry(TRACK_TABLE, "999999")));
		/* Check invalid parameters */
		$this->assertTrue(empty($this->model->getTableEntry(USER_TABLE, 123)));
		$this->assertTrue(empty($this->model->getTableEntry(ARTIST_TABLE, 123)));
		$this->assertTrue(empty($this->model->getTableEntry(ALBUM_TABLE, 123)));
		$this->assertTrue(empty($this->model->getTableEntry(TRACK_TABLE, 123)));
	}
	
	public function testUserFunction() {
		/* Create new user and then delete */
		$newUser = array(
			COLUMN_USERNAME => "testuser",
			COLUMN_NAME => "John",
			COLUMN_SURNAME => "Smith",
			COLUMN_EMAIL => "john@smith.com",
			COLUMN_PASSWORD => "johnsmith"
		);
		$result = $this->model->createNewUser($newUser);
		$this->assertNotNull($result);
		
		/* Try input a user with bad inputs */
		$newUser = array(
			COLUMN_USERNAME => 6,
			COLUMN_NAME => 22,
			COLUMN_SURNAME => 78,
			COLUMN_EMAIL => "john#smith/com",
			COLUMN_PASSWORD => true
		);
		$this->assertFalse($this->model->createNewUser($newUser));
		
		/* Try input a user with missing inputs */
		$newUser = array(
			COLUMN_USERNAME => "testuser",
			COLUMN_NAME => "John",
			COLUMN_EMAIL => "john@smith.com",
			COLUMN_PASSWORD => "johnsmith"
		);
		$this->assertFalse($this->model->createNewUser($newUser));
		
		/* Try input a user with inputs that are too long */
		$newUser = array(
			COLUMN_USERNAME => "testuserxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
			COLUMN_NAME => "Johnxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
			COLUMN_SURNAME => "Smithxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
			COLUMN_EMAIL => "john@smith.comxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
			COLUMN_PASSWORD => "johnsmithxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
		);
		$this->assertFalse($this->model->createNewUser($newUser));
		
		/* Update a user */
		$updateUser = array(
			COLUMN_USERNAME => "testuser",
			COLUMN_NAME => "John",
			COLUMN_SURNAME => "Smith",
			COLUMN_EMAIL => "john@smith.com",
			COLUMN_PASSWORD => "johnsmith"
		);
		$this->assertNotNull($this->model->updateUsers($result, $updateUser));
		
		/* Update a user with bad inputs */
		$updateUser = array(
			COLUMN_USERNAME => 456,
			COLUMN_NAME => 123,
			COLUMN_SURNAME => 789,
			COLUMN_EMAIL => "john/smith#com",
			COLUMN_PASSWORD => 147
		);
		$this->assertFalse($this->model->updateUsers($result, $updateUser));
		
		/* Update a user with missing inputs */
		$updateUser = array(
			COLUMN_USERNAME => "testuser",
			COLUMN_NAME => "John",
			COLUMN_EMAIL => "john@smith.com",
			COLUMN_PASSWORD => "johnsmith"
		);
		$this->assertNotNull($this->model->updateUsers($result, $updateUser));
		
		/* Update a user with out-of-bounds inputs */
		$updateUser = array(
			COLUMN_USERNAME => "testuserxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
			COLUMN_NAME => "Johnxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
			COLUMN_SURNAME => "Smithxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
			COLUMN_EMAIL => "john@smith.comxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
			COLUMN_PASSWORD => "johnsmithxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
		);
		$this->assertFalse($this->model->updateUsers($result, $updateUser));
		
		/* Delete the user */
		$this->assertNotNull($this->model->deleteTableEntry(USER_TABLE, $result));
		
	}
	
//	public function testSearch() {
//		$searchString = array(
//			COLUMN_USERNAME => "test",
//			COLUMN_NAME => "Jo",
//			COLUMN_SURNAME => "Sm",
//			COLUMN_EMAIL => "jo"
//		);
//		var_dump($this->model->searchUsers($searchString));
//	}
}
?>