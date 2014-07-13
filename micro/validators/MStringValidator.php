<?php

class MStringValidator extends MValidator
{
	public function validate($model) {
		foreach ($this->elements AS $element) {
			$elementValue = $model->$element;

			if (isset($this->params['min']) AND !empty($this->params['min'])) {
				if ((integer)$this->params['min'] > strlen($elementValue)) {
					$this->errors[] = $element.' error: minimal characters not valid.';
					return false;
				}
			}
			if (isset($this->params['max']) AND !empty($this->params['max'])) {
				if ((integer)$this->params['max'] < strlen($elementValue)) {
					$this->errors[] = $element.' error: maximal characters not valid.';
					return false;
				}
			}
		}
		return true;
	}
	public function client($model) {
		$js='';
		return $js;
	}
}