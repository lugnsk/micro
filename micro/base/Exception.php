<?php /** MicroException */

namespace Micro\base;

/**
 * Exception specific exception
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class Exception extends \Exception
{
    protected $container;

    public function __construct($container, $message = "", $code = 0, \Exception $previous = null)
    {
        $this->container = $container;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Magic convert object to string
     *
     * @access public
     *
     * @return mixed|string
     */
    public function __toString()
    {
        if (!defined('DEBUG_MICRO') || DEBUG_MICRO === false) {
            if (ob_get_level()) {
                ob_end_clean();
            }
        }
        $this->makeErrors();


        if (php_sapi_name() === 'cli') {
            return '"Error #' . $this->getCode() . ' - ' . $this->getMessage() . '"';
        }

        if (empty($this->container->__get('errorController'))) {
            return 'Option `errorController` not configured';
        }
        if (empty($this->container->__get('errorAction'))) {
            return 'Option `errorAction` not configured';
        }

        $controller = $this->container->__get('errorController');
        $action = $this->container->__get('errorAction');

        /** @var \Micro\mvc\controllers\Controller $mvc controller */
        $mvc = new $controller;
        echo $mvc->action($action);

        error_reporting(0);
    }

    protected function makeErrors()
    {
        $errors = !empty($_POST['errors']) ? $_POST['errors'] : [];
        $errors += ['Error - ' . $this->getMessage()];

        unset($_GET, $_POST);
        $_POST['errors'] = $errors;
    }
}