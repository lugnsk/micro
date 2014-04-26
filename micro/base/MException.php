<?php /** MicroException */

/**
 * MException specific exception
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license http://opensource.org/licenses/MIT
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class MException extends Exception
{
	/**
	 * Magic convert object to string
	 *
	 * @access public
	 * @return string
	 */
	public function __toString() {
		return '<h1>Ошибка ' . $this->getCode() . '</h1><p>' . $this->getMessage() . '</p>';
	}
}