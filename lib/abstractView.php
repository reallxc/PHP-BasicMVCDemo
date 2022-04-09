<?php
//include 'lib/interfaces.php'; // remove later

abstract class AbstractView {
	private $model;
	private $template;
	private $fields;
	protected $errorMsg = '';

	public function _construct() {
		$this->model=null;
		$this->template=null;
		$this->fields=array();
	}

	public function getModel() {
		return $this->model;
	}

	public function setModel($model) {
		$this->model=$model;
	}

	public function setTemplate($template) {
		$this->template=$template;
	}

	public function setTemplateField($name,$value){
		$this->fields[$name]=$value;
	}

	public function setTemplateFields($fields) {
		foreach ($fields as $name=>$value) {
			$this->setTemplateField($name, $value);
		}
	}

	public function setErrorMSG($errorMsg)
	{
		$this->errorMsg = '<script>alert("'.$errorMsg.'");</script>';
	}

	public function render() {
		$this->setTemplateField('css',WEBSITE_INI['css']);
		$this->setTemplateField('title',WEBSITE_INI['title']);
		$html=file_get_contents($this->template);
		//inject content first to replace key marks in contents
		$html=str_replace('##content##', $this->fields['content'], $html);
		foreach ($this->fields as $name=>$value) {
			$key='##'.$name.'##';
			$html=str_replace($key, $value, $html);
		}
		print $html;
	}

	//	expect subclass to override
	public function prepare () {
	}
}
