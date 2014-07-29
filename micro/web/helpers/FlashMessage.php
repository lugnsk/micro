<?php /** MicroFlashMessage */

namespace Micro\web\helpers;

use Micro\base\Registry;
use Micro\base\Exception AS MException;

/**
 * FlashMessage is a flash messenger.
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
class FlashMessage
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
     * @global Registry
     * @result void
     * @catch MException
     */
    public function __construct()
    {
        if (Registry::get('session') != NULL) {
            Registry::get('session')->flash = [];
        } else {
            throw new MException('Механизм сессий не активирован.');
        }
    }

    /**
     * Push a new flash
     *
     * @access public
     * @global Registry
     * @param int $type
     * @param string $title
     * @param string $description
     * @return void
     */
    public function push($type = FlashMessage::TYPE_SUCCESS, $title = '', $description = '')
    {
        Registry::get('session')->flash[] = [
            'type' => $type,
            'title' => $title,
            'description' => $description
        ];
    }

    /**
     * Has flashes by type
     *
     * @access public
     * @global Registry
     * @param int $type
     * @return bool
     */
    public function has($type = FlashMessage::TYPE_SUCCESS)
    {
        foreach (Registry::get('session')->flash AS $element) {
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
     * @global Registry
     * @param int $type
     * @return array|bool
     */
    public function get($type = FlashMessage::TYPE_SUCCESS)
    {
        foreach (Registry::get('session')->flash AS $key => $element) {
            if (isset($element['type']) && $element['type'] == $type) {
                $result = $element;
                unset(Registry::get('session')->flash[$key]);
                return $result;
            }
        }
        return false;
    }

    /**
     * Get all flashes
     *
     * @access public
     * @global Registry
     * @return mixed
     */
    public function getAll()
    {
        $result = Registry::get('session')->flash;
        Registry::get('session')->flash = [];
        return $result;
    }
}