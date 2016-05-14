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

/* database constants */
define("DB_HOST", "localhost" ); 		// set database host
define("DB_USER", "root" ); 			// set database user
define("DB_PASS", "" ); 				// set database password
define("DB_PORT", 3306);				// set database port (not used)
define("DB_NAME", "music_database" );	// set database name
define("DB_CHARSET", "utf8" ); 			// set database charset
define("DB_DEBUGMODE", true ); 			// set database debug mode
define("DB_VENDOR", "mysql");			// set database vendor

/* Include file names */
define("USER_MODEL", "UserModel");
define("USER_CONTROLLER", "UserController");
define("USER_VIEW_JSON", "jsonView");
define("USER_VIEW_XML", "xmlView");

/* Column Names */
define("COLUMN_ID", "id");
define("COLUMN_USERNAME", "username");
define("COLUMN_NAME", "name");
define("COLUMN_SURNAME", "surname");
define("COLUMN_EMAIL", "email");
define("COLUMN_PASSWORD", "password");
define("COLUMN_SEARCHSTRING", "SearchingString");
define("COLUMN_ARTIST_ID", "artist_id");
define("COLUMN_ALBUM_ID", "album_id");

/* Header Names */
define("HEADER_USERNAME", "username");
define("HEADER_NAME", "name");
define("HEADER_SURNAME", "surname");
define("HEADER_EMAIL", "email");
define("HEADER_PASSWORD", "password");
define("HEADER_CONTENT_TYPE", "Content-Type");

/* Response Formats */
define("RESPONSE_JSON", "json");
define("RESPONSE_XML", "xml");

/* Table names */
define("USER_TABLE", "users");
define("ARTIST_TABLE", "artists");
define("ALBUM_TABLE", "albums");
define("TRACK_TABLE", "tracks");

/* Number constants */
define("MIN_VALUE", 1);
define("MAX_VALUE", 9999);

/* actions for the USERS REST resource */
define("ACTION_GET_USER", 100);
define("ACTION_GET_USERS", 101);
define("ACTION_CREATE_USER", 102);
define("ACTION_UPDATE_USER", 103);
define("ACTION_DELETE_USER", 104);
define("ACTION_SEARCH_USERS", 105);
define("ACTION_SEARCH_USERS_BY_USERNAME", 106);
define("ACTION_SEARCH_USERS_BY_NAME", 107);
define("ACTION_SEARCH_USERS_BY_SURNAME", 108);
define("ACTION_SEARCH_USERS_BY_EMAIL", 109);

/* actions for the ARTISTS REST resource */
define("ACTION_GET_ARTIST", 200);
define("ACTION_GET_ARTISTS", 201);
define("ACTION_CREATE_ARTIST", 202);
define("ACTION_UPDATE_ARTIST", 203);
define("ACTION_DELETE_ARTIST", 204);
define("ACTION_SEARCH_ARTISTS_BY_NAME", 205);

/* actions for the ALBUMS REST resource */
define("ACTION_GET_ALBUM", 300);
define("ACTION_GET_ALBUMS", 301);
define("ACTION_CREATE_ALBUM", 302);
define("ACTION_UPDATE_ALBUM", 303);
define("ACTION_DELETE_ALBUM", 304);
define("ACTION_SEARCH_ALBUMS_BY_NAME", 305);

/* actions for the TRACKS REST resource */
define("ACTION_GET_TRACK", 400);
define("ACTION_GET_TRACKS", 401);
define("ACTION_CREATE_TRACK", 402);
define("ACTION_UPDATE_TRACK", 403);
define("ACTION_DELETE_TRACK", 404);
define("ACTION_SEARCH_TRACKS_BY_NAME", 405);

/* actions for the authorisation REST resource */
define("ACTION_AUTH_USER", 500);

/* HTTP status codes 2xx*/
define("HTTPSTATUS_OK", 200);
define("HTTPSTATUS_CREATED", 201);
define("HTTPSTATUS_NOCONTENT", 204);

/* HTTP status codes 3xx (with slim the output is not produced i.e. echo statements are not processed) */
define("HTTPSTATUS_NOTMODIFIED", 304);

/* HTTP status codes 4xx */
define("HTTPSTATUS_BADREQUEST", 400);
define("HTTPSTATUS_UNAUTHORIZED", 401);
define("HTTPSTATUS_FORBIDDEN", 403);
define("HTTPSTATUS_NOTFOUND", 404);
define("HTTPSTATUS_REQUESTTIMEOUT", 408);
define("HTTPSTATUS_TOKENREQUIRED", 499);

/* HTTP status codes 5xx */
define("HTTPSTATUS_INTSERVERERR", 500);

define("TIMEOUT_PERIOD", 120);

/* general message */
define("GENERAL_MESSAGE_LABEL", "message");
define("GENERAL_SUCCESS_MESSAGE", "success");
define("GENERAL_ERROR_MESSAGE", "error");
define("GENERAL_NOCONTENT_MESSAGE", "no-content");
define("GENERAL_NOTMODIFIED_MESSAGE", "not modified");
define("GENERAL_INTERNALAPPERROR_MESSAGE", "internal app error");
define("GENERAL_CLIENT_ERROR", "client error: modify the request");
define("GENERAL_INVALIDTOKEN_ERROR", "Invalid token");
define("GENERAL_APINOTEXISTING_ERROR", "Api is not existing");
define("GENERAL_RESOURCE_CREATED", "Resource has been created");
define("GENERAL_RESOURCE_UPDATED", "Resource has been updated");
define("GENERAL_RESOURCE_DELETED", "Resource has been deleted");
define("GENERAL_UNAUTHORISED_USER", "Unauthorised User");

define("GENERAL_FORBIDDEN", "Request is ok but action is forbidden");
define("GENERAL_INVALIDBODY", "Request is ok but transmitted body is invalid");
define("GENERAL_DELETE_ERROR", "Unable to delete resource");
define("GENERAL_UPDATE_ERROR", "Unable to update resource");
define("GENERAL_SEARCH_ERROR", "Unable to find resource");
define("GENERAL_SEARCH_ERROR_USERNAME", "Unable to find username in database");
define("GENERAL_SEARCH_ERROR_NAME", "Unable to find name in database");
define("GENERAL_SEARCH_ERROR_SURNAME", "Unable to find surname in database");
define("GENERAL_SEARCH_ERROR_EMAIL", "Unable to find email in database");

define("GENERAL_WELCOME_MESSAGE", "Welcome to DIT web-services");
define("GENERAL_INVALIDROUTE", "Requested route does not exist");


/* representation of a new user in the DB */
define("TABLE_USER_USERNAME_LENGTH", 30);
define("TABLE_USER_NAME_LENGTH", 30);
define("TABLE_USER_SURNAME_LENGTH", 30);
define("TABLE_USER_EMAIL_LENGTH", 50);
define("TABLE_USER_PASSWORD_LENGTH", 80);

/* representation of a new artist in the DB */
define("TABLE_ARTIST_NAME_LENGTH", 30);

/* representation of a new album in the DB */
define("TABLE_ALBUM_NAME_LENGTH", 30);

/* representation of a new track in the DB */
define("TABLE_TRACK_NAME_LENGTH", 30);

?>