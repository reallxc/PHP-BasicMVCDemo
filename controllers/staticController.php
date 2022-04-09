<?php

/*
   A PHP framework for web sites

   Sample test controller
   ======================

   we'll work with a list of tests
   each test is defined by a file in the tests directory
*/
include 'lib/abstractController.php';
include 'models/static.php';
include 'views/static.php';

class StaticController extends AbstractController {
	private $path;
	public function __construct($context) {
		parent::__construct($context);
		$this->path=$context->getURI()->getRemainingParts();
	}

	protected function getView($isPostback) {
		$db=$this->getDB();
		$model = new StaticModel($db, $this->getContext(), $this->path);
		// create output
		$view=new StaticView();
		$view->setModel($model);
		$view->setTemplate((WEBSITE_INI['folder'] . '/masterPage.html'));
		$path=explode("/",$this->path);
		//$title=ucwords(implode(' ',$path));
		//$view->setTemplateField('pagename',$title);
		$menubar = $this->getMenubar();
		$errorMsg = '';
		if (isset($_GET["error"])) {
			$errorMsg = $this->getError($_GET["error"]);
			$view->setErrorMSG($errorMsg);
		}
		$view->setTemplateField('errorMsg',$errorMsg);
		$view->setTemplateField('menubar',$menubar);
		$view->prepare();
		return $view;
	}
}
?>
