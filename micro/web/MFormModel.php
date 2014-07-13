<?php /* MicroFormModel */

/**
 * Class MFormModel.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web
 * @version 1.0
 * @since 1.0
 */
abstract class MFormModel
{
	protected $errors=array();

	// Базовая модель для генератора форм / взамен MModel
	public function rules() {
		return array();
	}
	public function validate() {
		// проверка опций по правилам
		foreach ($this->rules() AS $rule) {
			$validator = new MValidator($rule);

			if (!$validator->run($this) AND $validator->errors) {
				$this->errors[] = $validator->errors;
			}
		}
		return true;
	}

	public function addError($description) {
		$this->errors[] = $description;
	}
	public function getErrors() {
		return $this->convertMultiArrayToArray($this->errors);
	}
	private function convertMultiArrayToArray($errors) {
		static $result=array();
		foreach ($errors AS $error) {
			if (is_array($error)) {
				$this->convertMultiArrayToArray($error);
			} else {
				$result[] = $error;
			}
		}
		return $result;
	}
	public function attributeLabels() {
		return array();
	}
	public function getLabel($property) {
		$elements = $this->attributeLabels();
		return (isset($elements[$property])) ? $elements[$property] : null;
	}
}