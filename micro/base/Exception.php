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
    public function __toString()
    {
        $resp = new Response();
        $resp->setStatus(500);
        $resp->setBody(
            '<h1>' . $this->message . '</h1>' .
            '<p>In ' . $this->file . ':' . $this->line . '</p>' .
            '<pre>' . $this->getTraceAsString() . '</pre>'
        );
        $resp->send();

        error_reporting(0);
        return '';
    }
}
