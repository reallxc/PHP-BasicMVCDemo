<?php
include "lib/interfaces.php";
include "lib/database.php";
//prepare admin user account
$username = "admin";
$password = "admin";
$login = $username . $password;
$hash = password_hash($login, PASSWORD_DEFAULT);

try {
	$db = new Database ("localhost","root","","");
	$sqlList=array(
		"DROP DATABASE IF EXISTS BCDE224Sokobun;",
		"CREATE DATABASE BCDE224Sokobun",
		"USE BCDE224Sokobun;",
		"CREATE TABLE `Admins` (
		  `adminID` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
		"CREATE TABLE `Countries` (
		  `countrycode` char(3) NOT NULL,
		  `countryname` varchar(255) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
		"CREATE TABLE `Leaderboard` (
		  `scoreID` int(11) NOT NULL,
		  `userID` int(11) NOT NULL,
		  `score` int(11) NOT NULL,
		  `date` datetime NOT NULL,
		  `countrycode` char(3) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
		"CREATE TABLE `Users` (
		  `userID` int(11) NOT NULL,
		  `username` varchar(255) NOT NULL,
		  `password` varchar(255) NOT NULL,
		  `email` varchar(255) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
		"ALTER TABLE `Admins`
		  ADD KEY `adminID` (`adminID`);",
		"ALTER TABLE `Countries`
		  ADD PRIMARY KEY (`countrycode`);",
		"ALTER TABLE `Leaderboard`
		  ADD PRIMARY KEY (`scoreID`),
		  ADD KEY `UserID` (`userID`),
		  ADD KEY `CountryCode` (`countrycode`);",
		"ALTER TABLE `Users`
		  ADD PRIMARY KEY (`userID`),
		  ADD UNIQUE KEY `username` (`username`);",
		"ALTER TABLE `Leaderboard`
		  MODIFY `scoreID` int(11) NOT NULL AUTO_INCREMENT;",
		"ALTER TABLE `Users`
		  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;",
		"ALTER TABLE `Admins`
		  ADD CONSTRAINT `admin2user` FOREIGN KEY (`adminID`) REFERENCES `Users` (`userID`);",
		"ALTER TABLE `Leaderboard`
		  ADD CONSTRAINT `CountryCode` FOREIGN KEY (`countrycode`) REFERENCES `Countries` (`countrycode`),
		  ADD CONSTRAINT `UserID` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`);",
		"INSERT INTO `Users` (`userID`, `username`, `password`, `email`) VALUES
		(1, '$username', '$hash', 'admin@gmail.com');",
		"INSERT INTO `Admins` (`adminID`) VALUES
		(1);",
		"INSERT INTO `Countries` (`countrycode`, `countryname`) VALUES
		('CHN', 'China'),
		('IOT', 'India'),
		('NZL', 'New Zealand'),
		('USA', 'United States');",
		"DROP DATABASE IF EXISTS BCDE224Hindi;",
		"CREATE DATABASE BCDE224Hindi",
		"USE BCDE224Hindi;",
		"CREATE TABLE `Admins` (
		  `adminID` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
		"CREATE TABLE `Forum` (
		  `postID` int(11) NOT NULL,
		  `userID` int(11) NOT NULL,
		  `title` varchar(100) NOT NULL,
		  `content` varchar(500) NOT NULL,
		  `date` datetime NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
		"CREATE TABLE `Users` (
		  `userID` int(11) NOT NULL,
		  `username` varchar(255) NOT NULL,
		  `password` varchar(255) NOT NULL,
		  `email` varchar(255) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
		"ALTER TABLE `Admins`
		  ADD KEY `adminID` (`adminID`);",
		"ALTER TABLE `Forum`
		  ADD PRIMARY KEY (`postID`),
		  ADD KEY `post2user` (`userID`);",
		"ALTER TABLE `Users`
		  ADD PRIMARY KEY (`userID`),
		  ADD UNIQUE KEY `username` (`username`);",
		"ALTER TABLE `Forum`
		  MODIFY `postID` int(11) NOT NULL AUTO_INCREMENT;",
		"ALTER TABLE `Users`
		  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;",
		"ALTER TABLE `Admins`
		  ADD CONSTRAINT `admin2user` FOREIGN KEY (`adminID`) REFERENCES `Users` (`userID`);",
		"ALTER TABLE `Forum`
		  ADD CONSTRAINT `post2user` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`);",
		"INSERT INTO `Users` (`userID`, `username`, `password`, `email`) VALUES
		(1, '$username', '$hash', 'admin@gmail.com');",
		"INSERT INTO `Admins` (`adminID`) VALUES
		(1);");

	$nRows = $db->executeBatch($sqlList);
	echo "$nRows rows affected by schema creation<br/>";

} catch ( Exception $ex) {
	echo 'Exception: '.$ex->getMessage();
}
