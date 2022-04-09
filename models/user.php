<?php
class User {
	var $db;
	var $session;
	var $uid;
	var $name;
	var $email;
	var $isMember;
	var $isAdmin;

	function __construct ($context){
		$this->db=$context->getDB();
		$this->session=$context->getSession();
		$this->uid=null;
		$this->name='';   		// default is anonymous user
		$this->isMember=false;  // default
		$this->isAdmin=null;    // default

		if ($this->session->isKeySet('userID')) {
			$user=$this->session->get('userID');
			$sql="select username,email from Users where userID=$user";
			$result=$this->db->query($sql);
			if (count($result)==1) {
				$this->uid=$user;
				$this->isMember=true;
				$row=$result[0];
				$this->name=$row['username'];
				$this->email=$row['email'];
			}
		}

	}
	function getUserID() {
		return $this->uid;
	}
	function getName() {
		return $this->name;
	}
	function getEmail() {
		return $this->email;
	}
	function isMember() {
		return $this->isMember;
	}
	function getInfo()
	{
		$userInfo = array('username' => $this->name , 'email' => $this->email, 'isAdmin' => $this->isAdmin());
		return $userInfo;
	}
	function isAdmin() {
		if ($this->isAdmin==null) {
			$sql="select adminID from Admins where adminID=$this->uid";
			$result=$this->db->query($sql);
			$this->isAdmin=count($result)==1;
		}
		return $this->isAdmin;
	}

}
