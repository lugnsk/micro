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
    /** @var IContainer $container Container config */
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
    public function __construct(IContainer $container, $message = '', $code = 0, \Exception $previous = null)
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
