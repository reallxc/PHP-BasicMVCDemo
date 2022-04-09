<?php
include 'lib/abstractModel.php';

class AccountModel extends AbstractModel {

	private $context;
	private $content;

	public function __construct($db, $context, $path) {
		parent::__construct($db);
		$this->context=$context;
		$this->content='no content';
		$this->load($path);
	}

	private function load($path) {
		$filename=WEBSITE_INI['folder'].'/'.$path.'.html';
		$this->content=file_get_contents($filename);
	}

	public function getContent() {
		return $this->content;
	}

	public function verifyAccount($username, $login){
		$sql="select userID,password from Users where username = '$username'";
		$result=$this->getDB()->query($sql);
		if (count($result)==1) {
			if (password_verify($login, $result[0]['password'])){
				$uid=$result[0]['userID'];
				return $uid;
			} else {
				return -1;
			}
		} else {
			return 0;
		}
	}
	public function newAccount($username, $hash, $email)
	{
		$sql="insert into Users (username, password, email) values ('$username', '$hash', '$email')";
		$result=$this->getDB()->execute($sql);
		return $result;
	}
}
