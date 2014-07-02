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
class MFormModel
{
	// Базовая модель для генератора форм / взамен MModel
	public function rules() {
		return array();
	}
	public function validate() {
		// проверка опций по правилам
		// получаем массив правил в форыч
		// проверяем первый массив - первое поле имплод (проверяемые правила)
		// второе имя валидатора
		// остальные параметры
	}

	public function attributeLabels() {
		return array();
	}
	public function getLabel($property) {
		$elements = $this->attributeLabels();
		return (isset($elements[$property])) ? $elements[$property] : null;
	}
}