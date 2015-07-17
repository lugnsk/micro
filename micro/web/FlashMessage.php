<?php /** MicroFlashMessage */

namespace Micro\web;

use Micro\base\Container;
use Micro\base\Exception;

/**
 * FlashMessage is a flash messenger.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web\helpers
 * @version 1.0
 * @since 1.0
 */
class FlashMessage
{
    /** @var integer $TYPE_SUCCESS success */
    const TYPE_SUCCESS = 1;
    /** @var integer $TYPE_NOTICE notice */
    const TYPE_INFO = 2;
    /** @var integer $TYPE_WARNING warning */
    const TYPE_WARNING = 3;
    /** @var integer TYPE_DANGER danger */
    const TYPE_DANGER = 4;


    /** @var \Micro\base\Container $container Container config */
    protected $container;


    /**
     * Constructor messenger
     *
     * @access public
     *
     * @param Container $container
     *
     * @result void
     * @throws Exception
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        if ($this->container->session === null) {
            throw new Exception($this->container, 'Sessions not activated');
        }
    }

    /**
     * Get label for type by id
     *
     * @access public
     *
     * @param int $type type id
     *
     * @return mixed
     * @static
     */
    public static function getTypeLabel($type = self::TYPE_SUCCESS)
    {
        return self::getTypeLabels()[$type];
    }

    /**
     * Get labels for types
     *
     * @access public
     * @return array
     * @static
     */
    public static function getTypeLabels()
    {
        return [
            self::TYPE_SUCCESS => 'success',
            self::TYPE_INFO => 'info',
            self::TYPE_WARNING => 'warning',
            self::TYPE_DANGER => 'danger'
        ];
    }

    /**
     * Push a new flash
     *
     * @access public
     * @global       Container
     *
     * @param int $type type id
     * @param string $title title flash
     * @param string $description description flash
     *
     * @return void
     */
    public function push($type = FlashMessage::TYPE_SUCCESS, $title = '', $description = '')
    {
        $flashes = $this->container->session->flash;
        $flashes[] = [
            'type' => $type,
            'title' => $title,
            'description' => $description
        ];
        $this->container->session->flash = $flashes;
    }

    /**
     * Has flashes by type
     *
     * @access public
     * @global    Container
     *
     * @param int $type type of flash
     *
     * @return bool
     */
    public function has($type = FlashMessage::TYPE_SUCCESS)
    {
        foreach ($this->container->session->flash AS $element) {
            if (!empty($element['type']) && $element['type'] === $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get flash by type
     *
     * @access public
     * @global    Container
     *
     * @param int $type type of flash
     *
     * @return array|bool
     */
    public function get($type = FlashMessage::TYPE_SUCCESS)
    {
        foreach ($this->container->session->flash AS $key => $element) {
            if (!empty($element['type']) && $element['type'] === $type) {
                $result = $element;
                unset($this->container->session->flash[$key]);

                return $result;
            }
        }

        return false;
    }

    /**
     * Get all flashes
     *
     * @access public
     * @global Container
     * @return mixed
     */
    public function getAll()
    {
        $result = $this->container->session->flash;
        $this->container->session->flash = [];

        return $result;
    }
}