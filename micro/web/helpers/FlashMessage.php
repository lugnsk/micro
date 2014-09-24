<?php /** MicroFlashMessage */

namespace Micro\web\helpers;

use Micro\base\Registry;
use Micro\base\Exception;

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
    /** @property integer $TYPE_SUCCESS success */
    const TYPE_SUCCESS = 1;
    /** @property integer $TYPE_NOTICE notice */
    const TYPE_INFO = 2;
    /** @property integer $TYPE_WARNING warning */
    const TYPE_WARNING = 3;
    /** @property integer TYPE_DANGER danger */
    const TYPE_DANGER = 4;

    /** @var \Micro\web\Session $session current session */
    protected $session;

    /**
     * Constructor messenger
     *
     * @access public
     * @global Registry
     * @result void
     * @throws Exception
     */
    public function __construct()
    {
        if (Registry::get('session') != NULL) {
            $this->session = Registry::get('session');
        } else {
            throw new Exception('Sessions not activated');
        }
    }

    /**
     * Get labels for types
     *
     * @access public
     * @return array
     */
    public static function getTypeLabels() {
        return [
            self::TYPE_SUCCESS => 'success',
            self::TYPE_INFO => 'info',
            self::TYPE_WARNING => 'warning',
            self::TYPE_DANGER => 'danger'
        ];
    }

    /**
     * Get label for type by id
     *
     * @access public
     * @param int $type type id
     * @return mixed
     */
    public static function getTypeLabel($type = self::TYPE_SUCCESS) {
        return self::getTypeLabels()[$type];
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
        $flashes = $this->session->flash;
        $flashes[] = [
            'type' => $type,
            'title' => $title,
            'description' => $description
        ];
        $this->session->flash = $flashes;
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
        foreach ($this->session->flash AS $element) {
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
        foreach ($this->session->flash AS $key => $element) {
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
        $result = $this->session->flash;
        $this->session->flash = [];
        return $result;
    }
}