<?php /** MicroUser */

/**
 * Micro user class file
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web\helpers
 * @version 1.0
 * @since 1.0
 */
class MUser
{
	public function isGuest() {
		return (isset($_SESSION['UserID']) AND !empty($_SESSION['UserID']));
	}
}