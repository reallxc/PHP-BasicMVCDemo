<?php
include 'lib/abstractModel.php';
include 'models/score.php';
//include 'models/country.php';

class LeaderboardModel extends AbstractModel {

	private $context;
	private $content;
	private $scores;
	private $pagesize = 10;

	//prepare a $scores for view

	public function __construct($db, $context, $path) {
		parent::__construct($db);
		$this->context=$context;
		$this->content='no content';
		if ($path!==NULL) {
			$this->loadHtml($path);
		}
	}

	private function loadHtml($path) {
		$filename=WEBSITE_INI['folder'].'/'.$path.'.html';
		$this->content=file_get_contents($filename);
	}
	//query database and return score sets as array
	private function loadScore($parameters)
	{
		$sql="select scoreID, userID, score, date, countrycode from Leaderboard$parameters";
		$rows=$this->getDB()->query($sql);
		foreach ($rows as $row){
			$scoreID=$row['scoreID'];
			$userID=$row['userID'];
			$score=$row['score'];
			$date=$row['date'];
			$country=$row['countrycode'];
			$score = new ScoreModel($this->getDB(), $scoreID, $userID, $score, $date, $country);
			$this->scores[]=$score->getScore();
		}
	}
	public function addScore($userID,$score,$countrycode)
	{
		$sql="INSERT INTO Leaderboard (userID, score, date, countrycode) VALUES ('$userID', '$score', NOW(), '$countrycode')";
		$result=$this->getDB()->execute($sql);
		return $result;
	}
	public function dropScore($scoreID)
	{
		$sql="DELETE FROM Leaderboard WHERE scoreID = $scoreID";
		$result=$this->getDB()->execute($sql);
		return $result;
	}

	//generate query with options from controller
	//amount of data, order, country
	//store pagenumber and page size in Session - GIVE UP PAGE FEATURE
	//datalimit: how many data wanted, NULL = unlimited
	//orderby: asc or desc, NULL = desc
	//countryspecific: pass countrycode, NULL = ALL
	public function prepareScore($orderby, $datalimit, $countryspecific)
	{
		$parameters = '';
		if (!empty($countryspecific)) {
			$parameters .= " WHERE countrycode = '$countryspecific'";
		}
		if (!empty($orderby)){
			$parameters .= ' ORDER BY score ' . $orderby;
		} else {
			$parameters .= ' ORDER BY score DESC';
		}
		if (!empty($datalimit)) {
			$parameters .= ' LIMIT ' . $datalimit;
		}
		$this->loadScore($parameters);
	}
	public function getContent() {
		return $this->content;
	}
	public function getScores() {
		return $this->scores;
	}

}
