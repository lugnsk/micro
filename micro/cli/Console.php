<?php /** MicroConsole */

namespace Micro\cli;

use Micro\base\Container;

/**
 * Console class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 * @abstract
 */
class Console
{
    /** @var Container $container */
    protected $container;
    /** @var string $command Parsed command */
    protected $command;
    /** @var array $args Arguments from params */
    protected $args = [];

    /**
     * Constructor command
     *
     * @access public
     *
     * @param Container $container
     *
     * @result void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        foreach ($container->request->getArguments() AS $param) {
            if ($pos = strpos($param, '=')) {
                $this->args[substr($param, 0, $pos)] = substr($param, $pos + 1);
            }
        }
    }

    /**
     * Run action of console command by name
     *
     * @access public
     *
     * @param string $name Command name
     *
     * @return bool|ConsoleCommand|string
     */
    public function action($name)
    {
        $command = '\\Micro\\consoles\\' . $name . 'ConsoleCommand';
        $command = class_exists($command) ? $command : '\\App\\consoles\\' . $name . 'ConsoleCommand';

        if (!class_exists($command)) {
            return false;
        }

        /** @var \Micro\cli\ConsoleCommand $command */
        $command = new $command (['container' => $this->container, 'args' => $this->args]);
        $command->execute();

        return $command;
    }
}