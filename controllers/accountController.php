<?php
include 'lib/abstractController.php';
include 'models/account.php';
include 'views/account.php';

class AccountController extends AbstractController {
	private $path;
	public function __construct($context) {
		parent::__construct($context);
		$this->path=$context->getURI()->getRemainingParts();
	}

	protected function getView($isPostback) {
		$db=$this->getDB();
		$model = new AccountModel($db, $this->getContext(), $this->path);
		$view=new AccountView();
		$view->setModel($model);
		$view->setTemplate((WEBSITE_INI['folder'] . '/masterPage.html'));
		$path=explode("/",$this->path);
		$menubar = $this->getMenubar();
		$errorMsg = '';
		if (isset($_GET["error"])) {
			$errorMsg = $this->getError($_GET["error"]);
			$view->setErrorMSG($errorMsg);
		}
		$view->setTemplateField('menubar',$menubar);

		switch ($this->path) {
			case 'account/signin':
				if ($isPostback) {
					$this->signIn($model,$_POST);
				}
				break;
			case 'account/signup':
				if ($isPostback) {
					$this->signUp($model,$_POST);
				}
				break;
			case 'account/signout':
				if ($isPostback) {
					$this->signOut();
				}
				break;
			default:
			if ($this->getContext()->getUser()->isMember()) {
				$userInfo=$this->getContext()->getUser()->getInfo();
				$view->setTemplateField('username',$userInfo['username']);
				$view->setTemplateField('email',$userInfo['username']);
				if ($userInfo['isAdmin']) {
					$view->setTemplateField('isAdmin','You are Admin');
				} else {
					$view->setTemplateField('isAdmin','');
				}
			}
				break;
		}
		return $view;
		}
		public function signOut()
		{
			$this->getContext()->getSession()->clear();
			header("location: /home?error=signoutSuc");
		}
		public function signIn($model,$postArray){
			$username = $postArray['username'];
			$password = $postArray['password'];
			if (empty($username)) {
				header("location: ?error=emptyName");
				exit();
			}
			if (empty($password)) {
				header("location: ?error=emptyPass");
				exit();
			}
			$login = $username . $password;
			$result = $model->verifyAccount($username, $login);
			if ($result==0) {
				header("location: ?error=noUser");
			} else if ($result==-1) {
				header("location: ?error=errorPass");
			} else {
				$this->getContext()->getSession()->set('userID',$result);
				header("location: /home?error=signinSuc");
			}
		}
		public function signUp($model,$postArray){
			$username = $postArray['username'];
			$password = $postArray['password'];
			$email = $postArray['email'];
			if (empty($username)) {
				header("location: ?error=emptyName");
				exit();
			}
			if (empty($password)) {
				header("location: ?error=emptyPass");
				exit();
			}
			if (empty($email)) {
				header("location: ?error=emptyEmail");
				exit();
			}
			$login = $username . $password;
			$hash = password_hash($login, PASSWORD_DEFAULT);
			$result = $model->verifyAccount($username, '');
			if ($result==0) {
				$model->newAccount($username, $hash, $email);
				header("location: /home?error=signupSuc");
			} else {
				header("location: ?error=existUser");
			}
		}
}
?>
