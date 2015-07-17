<?php /** MicroException */

namespace Micro\base;

use Micro\web\Response;

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
    /** @var Container $container Container config */
    protected $container;

    /**
     * @access public
     *
     * @param Container $container
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     *
     * @result void
     */
    public function __construct($container, $message = '', $code = 0, \Exception $previous = null)
    {
        $this->container = $container;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Magic convert object to string
     *
     * @access public
     *
     * @return string
     * @throws Exception
     */
    public function __toString()
    {
        if (!defined('DEBUG_MICRO') || DEBUG_MICRO === false) {
            /** @noinspection NestedPositiveIfStatementsInspection */
            if (ob_get_level()) {
                ob_end_clean();
            }
        }
        $this->makeErrors();


        if (php_sapi_name() === 'cli') {
            return '"Error #' . $this->getCode() . ' - ' . $this->getMessage() . '"';
        }

        if (!$this->container->errorController) {
            return 'Option `errorController` not configured';
        }
        if (!$this->container->errorAction) {
            return 'Option `errorAction` not configured';
        }

        $controller = $this->container->errorController;
        $action = $this->container->errorAction;

        /** @var \Micro\mvc\controllers\Controller $mvc controller */
        $mvc = new $controller($this->container);

        $response = null;
        $result = $mvc->action($action);
        if ($result instanceof Response) {
            $response = $result;
        } else {
            $response = new Response;
            $response->setBody($result);
        }

        $response->send();
        error_reporting(0);

        return '';
    }

    protected function makeErrors()
    {
        $errors = array_merge(
            $this->container->request->getPostVar('errors') ?: [],
            ['Error - ' . $this->getMessage()]
        );

        $this->container->request->setPostVar('errors', $errors);
    }
}