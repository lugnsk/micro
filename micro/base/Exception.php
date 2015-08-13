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
    public function __toString()
    {
        $resp = new Response();
        $resp->setBody('<h1>' . $this->message . '</h1>' . '<p>In ' . $this->file . ':' . $this->line . '</p>');
        $resp->send();

        error_reporting(0);
        return '';
    }
}
