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
	/** @property array $errors */
	protected $errors=array();


	/**
	 * Define rules for validation
	 *
	 * @access public
	 * @return array
	 */
	public function rules() {
		return array();
	}
	/**
	 * Run validation
	 *
	 * @access public
	 * @return bool
	 */
	public function validate() {
		foreach ($this->rules() AS $rule) {
			$validator = new MValidator($rule);

			if (!$validator->run($this) AND $validator->errors) {
				$this->errors[] = $validator->errors;
			}
		}
		return true;
	}
	/**
	 * Add error model
	 *
	 * @access public
	 * @param string $description
	 * @return void
	 */
	public function addError($description) {
		$this->errors[] = $description;
	}
	/**
	 * Get errors after validation
	 *
	 * @access public
	 * @return array
	 */
	public function getErrors() {
		return $this->convertMultiArrayToArray($this->errors);
	}
	/**
	 * Convert results array to single array
	 *
	 * @access protected
	 * @param array $errors
	 * @return array
	 */
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
	/**
	 * Define labels for elements
	 *
	 * @access public
	 * @return array
	 */
	public function attributeLabels() {
		return array();
	}
	/**
	 * Get element label
	 *
	 * @access public
	 * @param $property
	 * @return null
	 */
	public function getLabel($property) {
		$elements = $this->attributeLabels();
		return (isset($elements[$property])) ? $elements[$property] : null;
	}
}