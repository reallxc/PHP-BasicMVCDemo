<?php
include 'lib/abstractView.php';

class AccountView extends AbstractView {

	public function prepare () {
		$content=$this->getModel()->getContent();
		$this->setTemplateField('errorMsg',$this->errorMsg);
		$this->setTemplateField('content',$content);
	}
}
