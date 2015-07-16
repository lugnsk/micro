<?php /** MicroConsoleResolver */

namespace Micro\resolvers;

use Micro\base\Console;

/**
 * CLI Resolver class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage resolver
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
     * @return \Micro\base\Command
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
