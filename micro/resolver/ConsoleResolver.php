<?php /** MicroConsoleResolver */

namespace Micro\Resolver;

use Micro\Cli\Console;

/**
 * CLI Resolver class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Resolver
 * @version 1.0
 * @since 1.0
 */
class ConsoleResolver extends Resolver
{
    /**
     * Get instance application
     *
     * @access public
     *
     * @return \Micro\Base\Command
     */
    public function getApplication()
    {
        return new Console($this->container);
    }

    /**
     * Get action from request
     *
     * @access public
     *
     * @return string
     */
    public function getAction()
    {
        $params = $this->container->request->getArguments();
        array_shift($params);

        return ucfirst(array_shift($params));
    }
}
