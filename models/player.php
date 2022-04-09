<?php

/**
 * Player class for Leaderboard
 */
class PlayerModel extends abstractModel
{
  private $userID;
  private $username;

  function __construct($db, $userID)
  {
    parent::__construct($db);
    $this->userID = $userID;
    $this->readusername();
  }

  private function readusername()
  {
    $sql="select username from Users where userID = '$this->userID'";
		$result=$this->getDB()->query($sql);
		if (count($result)==1) {
      $this->username=$result[0]['username'];
      return 1;
		} else {
			return null;
		}
  }

  public function getusername()
  {
    return $this->username;
  }
}
