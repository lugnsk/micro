<?php /** MicroFlashMessage */

namespace Micro\web\helpers;

use Micro\base\MRegistry;
use Micro\base\MException;

/**
 * MFlashMessage is a flash messenger.
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
class MFlashMessage
{
	/** @property integer $TYPE_SUCCESS */
	const TYPE_SUCCESS = 1;
	/** @property integer $TYPE_NOTICE */
	const TYPE_NOTICE = 2;
	/** @property integer $TYPE_ERROR */
	const TYPE_ERROR = 3;

	/**
	 * Constructor messenger
	 *
	 * @access public
	 * @global MRegistry
	 * @result void
	 * @catch MException
	 */
	public function __construct() {
		try {
			MRegistry::get('session')->flash = [];
		} catch (MException $e) {
			die('Механизм сессий не активирован: ' . $e->getMessage());
		}
	}

	/**
	 * Push a new flash
	 *
	 * @access public
	 * @global MRegistry
	 * @param int $type
	 * @param string $title
	 * @param string $description
	 * @return void
	 */
	public function push($type = MFlashMessage::TYPE_SUCCESS, $title = '', $description = '') {
		MRegistry::get('session')->flash[] = array(
			'type'=> $type,
			'title'=> $title,
			'description'=> $description
		);
	}
	/**
	 * Has flashes by type
	 *
	 * @access public
	 * @global MRegistry
	 * @param int $type
	 * @return bool
	 */
	public function has($type = MFlashMessage::TYPE_SUCCESS) {
		foreach (MRegistry::get('session')->flash AS $element) {
			if (isset($element['type']) && $element['type'] == $type) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get flash by type
	 *
	 * @access public
	 * @global MRegistry
	 * @param int $type
	 * @return array|bool
	 */
	public function get($type = MFlashMessage::TYPE_SUCCESS) {
		foreach (MRegistry::get('session')->flash AS $key=>$element) {
			if (isset($element['type']) && $element['type'] == $type) {
				$result = $element;
				unset(MRegistry::get('session')->flash[$key]);
				return $result;
			}
		}
		return false;
	}

	/**
	 * Get all flashes
	 *
	 * @access public
	 * @global MRegistry
	 * @return mixed
	 */
	public function getAll() {
		$result = MRegistry::get('session')->flash;
		MRegistry::get('session')->flash = [];
		return $result;
	}
}