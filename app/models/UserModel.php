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

require_once "DB/pdoDbManager.php";
require_once "DB/DAO/UsersDAO.php";
require_once "DB/DAO/AlbumsDAO.php";
require_once "DB/DAO/ArtistsDAO.php";
require_once "DB/DAO/TracksDAO.php";
require_once "Validation.php";
class UserModel {
	private $UsersDAO; // list of DAOs used by this model
	private $AlbumsDAO;
	private $ArtistsDAO;
	private $TracksDAO;
	private $dbmanager; // dbmanager
	public $apiResponse; // api response
	private $validationSuite; // contains functions for validating inputs
	public function __construct() {
		$this->dbmanager = new pdoDbManager ();
		$this->UsersDAO = new UsersDAO ( $this->dbmanager );
		$this->AlbumsDAO = new AlbumsDAO ( $this->dbmanager );
		$this->ArtistsDAO = new ArtistsDAO ( $this->dbmanager );
		$this->TracksDAO = new TracksDAO ( $this->dbmanager );
		$this->dbmanager->openConnection ();
		$this->validationSuite = new Validation ();
	}
	public function getTableEntry($tableName, $id) {
		if (is_string($tableName)) {
			switch ($tableName) {
				case USER_TABLE :
					if (is_numeric($id))
						$result = ($this->UsersDAO->get($id));
					else
						$result = ($this->UsersDAO->get());
					break;
				case ARTIST_TABLE :
					if (is_numeric($id))
						$result = ($this->ArtistsDAO->get($id));
					else
						$result = ($this->ArtistsDAO->get());
					break;
				case ALBUM_TABLE :
					if (is_numeric($id))
						$result = ($this->AlbumsDAO->get($id));
					else
						$result = ($this->AlbumsDAO->get());
					break;
				case TRACK_TABLE :
					if (is_numeric($id))
						$result = ($this->TracksDAO->get($id));
					else
						$result = ($this->TracksDAO->get());
					break;
			}
		}
		else
			$result = false;
			
		return $result;
	}

	public function createNewUser($newUser) {
		// validation of the values of the new user
		// compulsory values
		if (! empty( $newUser [COLUMN_USERNAME] ) && ! empty ( $newUser [COLUMN_NAME] )
			&& ! empty ( $newUser [COLUMN_SURNAME] ) && ! empty ( $newUser [COLUMN_EMAIL] )
			&& ! empty ( $newUser [COLUMN_PASSWORD] )) {
			/*
			 * the model knows the representation of a user in the database and this is:
			 * name: varchar(25) surname: varchar(25) email: varchar(50) password: varchar(40)
			 */
			if (($this->validationSuite->isLengthStringValid ( $newUser [COLUMN_USERNAME], TABLE_USER_USERNAME_LENGTH ))
				&& ($this->validationSuite->isLengthStringValid ( $newUser [COLUMN_NAME], TABLE_USER_NAME_LENGTH ))
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
		if ((!empty($userNewRepresentation[COLUMN_USERNAME])
				&& (!$this->validationSuite->isLengthStringValid($userNewRepresentation[COLUMN_USERNAME], TABLE_USER_USERNAME_LENGTH)))
			|| (!empty($userNewRepresentation[COLUMN_PASSWORD])
				&& (!$this->validationSuite->isLengthStringValid($userNewRepresentation[COLUMN_PASSWORD], TABLE_USER_PASSWORD_LENGTH)))
			|| (!empty($userNewRepresentation[COLUMN_NAME])
				&& (!$this->validationSuite->isLengthStringValid($userNewRepresentation[COLUMN_NAME], TABLE_USER_NAME_LENGTH)))
			|| (!empty($userNewRepresentation[COLUMN_SURNAME])
				&& (!$this->validationSuite->isLengthStringValid($userNewRepresentation[COLUMN_SURNAME], TABLE_USER_SURNAME_LENGTH)))
			|| (!empty($userNewRepresentation[COLUMN_EMAIL])
				&& (!$this->validationSuite->isLengthStringValid($userNewRepresentation[COLUMN_EMAIL], TABLE_USER_EMAIL_LENGTH))
				/* NOTE: Checking the validity of the email address in the Model Layer
				 * as we don't want invalid data entering the database. It does not fit
				 * in the Controller Layer as the format of the data is not it's concern */
				&& (!$this->validationSuite->isEmailValid($userNewRepresentation[COLUMN_EMAIL]))))
			return false;
			
		return ($this->UsersDAO->update($userID, $userNewRepresentation));
	}
	public function searchUsers($searchUserStr) {
		/* If field is not empty and does not fit DB limits */
		if ((!empty($searchUserStr[COLUMN_USERNAME])
				&& (!$this->validationSuite->isLengthStringValid($searchUserStr[COLUMN_USERNAME], TABLE_USER_USERNAME_LENGTH)))
			|| (!empty($searchUserStr[COLUMN_NAME])
				&& (!$this->validationSuite->isLengthStringValid($searchUserStr[COLUMN_NAME], TABLE_USER_NAME_LENGTH)))
			|| (!empty($searchUserStr[COLUMN_SURNAME])
				&& (!$this->validationSuite->isLengthStringValid($searchUserStr[COLUMN_SURNAME], TABLE_USER_SURNAME_LENGTH)))
			|| (!empty($searchUserStr[COLUMN_EMAIL])
				&& (!$this->validationSuite->isLengthStringValid($searchUserStr[COLUMN_EMAIL], TABLE_USER_EMAIL_LENGTH)))
				/* NOTE: Checking the validity of the email address in the Model Layer
				 * as we don't want invalid data entering the database. It does not fit
				 * in the Controller Layer as the format of the data is not it's concern */
				&& (!$this->validationSuite->isEmailValid($searchUserStr[COLUMN_EMAIL])))
			return false;	
			
		return ($this->UsersDAO->search($searchUserStr));
	}
	public function searchUsersByUsername($searchStr) {
		if ((!empty($searchUserStr[COLUMN_USERNAME])
				&& (!$this->validationSuite->isLengthStringValid($searchStr[COLUMN_USERNAME], TABLE_USER_USERNAME_LENGTH))))
			return false;
		
		return ($this->UsersDAO->searchUsersByUsername($searchStr));
	}
	public function searchTableByName($tableName, $searchStr) {
		if ((!empty($searchStr[COLUMN_NAME])
				&& (!$this->validationSuite->isLengthStringValid($searchStr[COLUMN_NAME], TABLE_USER_NAME_LENGTH))))
			return false;
		
		if (is_string($tableName)) {
			switch ($tableName) {
					case USER_TABLE :
						$result = ($this->UsersDAO->searchUsersByName($searchStr));
						break;
					case ARTIST_TABLE :
						$result = ($this->ArtistsDAO->searchArtistsByName($searchStr));
						break;
					case ALBUM_TABLE :
						$result = ($this->AlbumsDAO->searchAlbumsByName($searchStr));
						break;
					case TRACK_TABLE :
						$result = ($this->TracksDAO->searchTracksByName($searchStr));
						break;
			}
		}

		return $result;
	}
	public function searchUsersBySurname($searchStr) {
		if ((!empty($searchUserStr[COLUMN_SURNAME])
				&& (!$this->validationSuite->isLengthStringValid($searchStr[COLUMN_SURNAME], TABLE_USER_SURNAME_LENGTH))))
			return false;
		
		return ($this->UsersDAO->searchUsersBySurname($searchStr));
	}
	public function searchUsersByEmail($searchStr) {
		if ((!empty($searchUserStr[COLUMN_EMAIL])
				&& (!$this->validationSuite->isLengthStringValid($searchStr[COLUMN_EMAIL], TABLE_USER_EMAIL_LENGTH)))
				/* NOTE: Checking the validity of the email address in the Model Layer
				 * as we don't want invalid data entering the database. It does not fit
				 * in the Controller Layer as the format of the data is not it's concern */
				&& (!$this->validationSuite->isEmailValid($searchStr[COLUMN_EMAIL])))
			return false;
		
		return ($this->UsersDAO->searchUsersByEmail($searchStr));
	}
	public function deleteTableEntry($tableName, $id) {
		if (is_string($tableName)) {
		switch ($tableName) {
				case USER_TABLE :
					$result = ($this->UsersDAO->delete($id));
					break;
				case ARTIST_TABLE :
					$result = ($this->ArtistsDAO->delete($id));
					break;
				case ALBUM_TABLE :
					$result = ($this->AlbumsDAO->delete($id));
					break;
				case TRACK_TABLE :
					$result = ($this->TracksDAO->delete($id));
					break;
			}
		}
		return $result;
	}
	public function createNewArtist($newArtist) {
		// validation of the values of the new artist
		// compulsory values
		if (! empty( $newArtist [COLUMN_NAME] )) {
			/*
			 * the model knows the representation of an artist in the database and this is:
			 * name: varchar(30)
			 */
			if (($this->validationSuite->isLengthStringValid ( $newArtist [COLUMN_NAME], TABLE_ARTIST_NAME_LENGTH ))) {
				if ($newId = $this->ArtistsDAO->insert ( $newArtist ))
					return ($newId);
			}
		}
		// if validation fails or insertion fails
		return (false);
	}
	public function createNewAlbum($newAlbum) {
		// validation of the values of the new album
		// compulsory values
		if (! empty( $newAlbum [COLUMN_NAME] )
			&& ( $newAlbum [COLUMN_ARTIST_ID] != NULL )) {
			/*
			 * the model knows the representation of an album in the database and this is:
			 * name: varchar(30)
			 * artist_id: int(11)
			 */
			if (($this->validationSuite->isLengthStringValid ( $newAlbum [COLUMN_NAME], TABLE_ALBUM_NAME_LENGTH ))
				&& ($this->validationSuite->isNumberInRangeValid($newAlbum[COLUMN_ARTIST_ID], MIN_VALUE, MAX_VALUE))) {
				if ($newId = $this->AlbumsDAO->insert ( $newAlbum ))
					return ($newId);
			}
		}
		// if validation fails or insertion fails
		return (false);
	}
	public function createNewTrack($newTrack) {
		// validation of the values of the new track
		// compulsory values
		if (! empty( $newTrack [COLUMN_NAME] )
			&& ( $newTrack [COLUMN_ALBUM_ID] != NULL )) {
			/*
			 * the model knows the representation of an track in the database and this is:
			 * name: varchar(30)
			 */
			if (($this->validationSuite->isLengthStringValid ( $newTrack [COLUMN_NAME], TABLE_TRACK_NAME_LENGTH ))
				&& ($this->validationSuite->isNumberInRangeValid($newTrack[COLUMN_ALBUM_ID], MIN_VALUE, MAX_VALUE))) {
				if ($newId = $this->TracksDAO->insert ( $newTrack ))
					return ($newId);
			}
		}
		// if validation fails or insertion fails
		return (false);
	}
	public function updateArtists($artistID, $artistNewRepresentation) {
		/* Validate the fields being entered for update of artist */
		/* If field is not empty and does not fit DB limits */
		if ((!empty($artistNewRepresentation[COLUMN_NAME])
				&& (!$this->validationSuite->isLengthStringValid($artistNewRepresentation[COLUMN_NAME], TABLE_ARTIST_NAME_LENGTH))))
			return false;
			
		return ($this->ArtistsDAO->update($artistID, $artistNewRepresentation));
	}
	public function updateAlbums($albumID, $albumsNewRepresentation) {
		/* Validate the fields being entered for update of album */
		/* If field is not empty and does not fit DB limits */
		if ((!empty($albumsNewRepresentation[COLUMN_NAME])
				&& (!$this->validationSuite->isLengthStringValid($albumsNewRepresentation[COLUMN_NAME], TABLE_ALBUM_NAME_LENGTH))))
			return false;
			
		return ($this->AlbumsDAO->update($albumID, $albumsNewRepresentation));
	}
	public function updateTracks($trackID, $trackNewRepresentation) {
		/* Validate the fields being entered for update of track */
		/* If field is not empty and does not fit DB limits */
		if ((!empty($trackNewRepresentation[COLUMN_NAME])
				&& (!$this->validationSuite->isLengthStringValid($trackNewRepresentation[COLUMN_NAME], TABLE_TRACK_NAME_LENGTH))))
			return false;
			
		return ($this->TracksDAO->update($trackID, $trackNewRepresentation));
	}
	
	public function authUser($username, $password) {
		if (!empty($username) && !empty($password)) {
			if (is_string($username) && is_string($password)) {
				return ($this->UsersDAO->authenticateUser($username, $password));
			}
		}
		return false;
	}
	public function __destruct() {
		$this->UsersDAO = null;
		$this->dbmanager->closeConnection ();
	}
}
?>