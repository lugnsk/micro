<?php

class MValidator
{
	private $rule=array();
	public $errors=array();

	public $elements=array();
	public $params=array();

	public function __construct($rule=array()) {
		$this->rule = $rule;
	}
	protected function isEmpty($value,$trim=false)
	{
		return $value===null || $value===array() || $value==='' || $trim && is_scalar($value) && trim($value)==='';
	}
	public function getErrors() {
		return $this->errors;
	}

	public function run($model, $client=false) {
		$elements = explode(',', str_replace(' ', '', array_shift($this->rule)));
		$name = array_shift($this->rule);

		if (file_exists(Micro::getInstance()->config['MicroDir'].'/validators/'.$this->validators[$name].'.php')) {
			include_once Micro::getInstance()->config['MicroDir'].'/validators/'.$this->validators[$name].'.php';
		} elseif (file_exists(Micro::getInstance()->config['AppDir'].'/validators/'.$name.'.php')) {
			include_once Micro::getInstance()->config['AppDir'].'/validators/'.$name.'.php';
		}

		$valid = new $this->validators[$name];
		$valid->elements = $elements;
		$valid->params = $this->rule;
		if ($client) {
			$result = $valid->client($model);
		} else {
			$result = $valid->validate($model);
		}

		if ($valid->errors) {
			$this->errors[] = $valid->errors;
		}
		return $result;
	}
	protected $validators=array(
		'string'=>'MStringValidator',
		//		'required'=>'CRequiredValidator',
		//		'filter'=>'CFilterValidator',
		//		'match'=>'CRegularExpressionValidator',
		//		'email'=>'CEmailValidator',
		//		'url'=>'CUrlValidator',
		//		'unique'=>'CUniqueValidator',
		//		'compare'=>'CCompareValidator',
		//		'length'=>'CStringValidator',
		//		'in'=>'CRangeValidator',
		//		'numerical'=>'CNumberValidator',
		//		'captcha'=>'CCaptchaValidator',
		//		'type'=>'CTypeValidator',
		//		'file'=>'CFileValidator',
		//		'default'=>'CDefaultValueValidator',
		//		'exist'=>'CExistValidator',
		//		'boolean'=>'CBooleanValidator',
		//		'safe'=>'CSafeValidator',
		//		'unsafe'=>'CUnsafeValidator',
		//		'date'=>'CDateValidator',
	);
}