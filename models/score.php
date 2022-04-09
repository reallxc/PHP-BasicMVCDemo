<?php
include 'models/country.php';
include 'models/player.php';

class ScoreModel extends AbstractModel {

	private $db;
	private $scoreID;
	private $userID;
	private $score;
	private $date;
	private $countrycode;

	public function __construct($db, $scoreID, $userID, $score, $date, $countrycode) {
		parent::__construct($db);
		$this->scoreID = $scoreID;
		$this->userID = $userID;
		$this->score = $score;
		$this->date = $date;
		$this->countrycode = $countrycode;
	}

	public function getScore()
	{
		$player = new PlayerModel($this->getDB(), $this->userID);
		$country = new CountryModel($this->getDB());
		return array('scoreID' => $this->scoreID, 'user' => $player->getusername(), 'score' => $this->score, 'date' => $this->date, 'country' => $country->getCountryname($this->countrycode) );
	}
	private function load() {

	}


}
