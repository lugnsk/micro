<?php /** MicroException */

namespace Micro\base;

use Micro\Micro;

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
            $errors = !empty($_POST['errors']) ? $_POST['errors'] : [];
            $errors += ['Error - ' . $this->getMessage()];

            unset($_GET, $_POST);
            $_POST['errors'] = $errors;

            $config = Micro::getInstance()->config;

            if (empty($config['errorController'])) {
                return 'Option `errorController` not configured';
            }
            if (empty($config['errorAction'])) {
                return 'Option `errorAction` not configured';
            }

            /** @var \Micro\mvc\controllers\Controller $mvc controller */
            $mvc = new $config['errorController'];
            echo $mvc->action($config['errorAction']);

            error_reporting(0);
        } else {
            return '"Error #' . $this->getCode() . ' - ' . $this->getMessage() . '"';
        }
    }
}