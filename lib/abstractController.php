<?php

abstract class AbstractController {

	private $context;
	private $redirect;

	public function __construct (IContext $context){
		$this->context=$context;
		$this->redirect=null;
	}
	protected function getContext() {
		return $this->context;
	}
	protected function getDB() {
		return $this->context->getDB();
	}
	protected function getURI() {
		return $this->context->getURI();
	}
	protected function getConfig() {
		return $this->context->getConfig();
	}

	public function process() { //final output function
		$method=$_SERVER['REQUEST_METHOD'];
		switch($method) {
			case 'GET':  	$view=$this->getView(false);	break;
			case 'POST':  	$view=$this->getView(true);		break;
			default:
				throw new InvalidRequestException ("Invalid Request verb");
		}
		if ($view!==null) {
			$view->prepare();
			// apply global template arguments
			$site=$this->getURI()->getSite();
			$view->setTemplateField('site',$site);
			$view->render();
		} elseif ($this->redirect!==null) {
			header ('Location: '.$this->redirect);
		} else {
			throw new InvalidRequestException ("View not set");
		}
	}

	protected function getMenubar() {
		$menubar = '';
		foreach (WEBSITE_INI['menu'] as $menu) {
			$menubar .= '<li><a href="/' . strtolower($menu) . '">' . $menu . '</a></li>';
		}
		if ($this->context->getUser()->isMember()) {
			$username=$this->context->getUser()->getName();
			$menubar .= '<li><a href="/account">' . $username . '</a></li><li><a href="/account/signout">Sign Out</a></li>';
		} else {
			$menubar .= '<li><a href="/account/signin">Sign In</a></li><li><a href="/account/signup">Sign Up</a></li>';
		}
		return $menubar;
	}
	protected function getError($error){
		switch ($error) {
			case 'emptyName':
				return 'Empty Username!';
			break;
			case 'emptyPass':
				return 'Empty Password!';
			break;
			case 'emptyEmail':
				return 'Empty Email!';
			break;
			case 'errorPass':
				return 'Wrong Password!';
			break;
			case 'signinSuc':
				return 'Sign in Success!';
			break;
			case 'signupSuc':
				return 'Sign up Success!';
			break;
			case 'signoutSuc':
				return 'Sign out Success!';
			break;
			case 'noUser':
				return 'Username does not exist!';
			break;
			case 'existUser':
				return 'Username is not available!';
			break;
			case 'submitScoreSuc':
				return 'Submit Score Success!';
			break;
			case 'deleteScoreSuc':
				return 'Delete Score Success!';
			break;
			case 'updateSuc':
				return 'Update Success!';
			break;
			case 'error':
				return 'Unknown error!';
			break;
			default:
				return '';
			break;
		}
	}
	// sub-controllers will override this
	protected function getView($isPostback) {
		return null;
	}

	protected function redirectTo ($page, $feedback) {
		throw new Exception ('Not yet implemented');
	}
}
?>
