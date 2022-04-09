<?php
include 'lib/abstractModel.php';

class StaticModel extends AbstractModel {

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

}
