<?php

/**
 * Country class for Leaderboard
 */
class CountryModel extends abstractModel
{

  function __construct($db)
  {
    parent::__construct($db);
  }

  public function getCountryname($countrycode)
  {
    $sql="select countryname from Countries where countrycode = '$countrycode'";
		$result=$this->getDB()->query($sql);
		if (count($result)==1) {
      return $result[0]['countryname'];
		} else {
			return null;
		}
  }
  public function getCountries()
  {
    $sql="select countrycode,countryname from Countries order by countrycode asc;";
    $result=$this->getDB()->query($sql);
    return $result;
  }
}
