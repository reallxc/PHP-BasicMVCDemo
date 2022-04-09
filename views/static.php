<?php
include 'lib/abstractView.php';

class StaticView extends AbstractView {

	public function prepare () {
		$content=$this->getModel()->getContent();
		$this->setTemplateField('errorMsg',$this->errorMsg);
		$this->setTemplateField('content',$content);
	}
}
