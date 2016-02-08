<?php /** MicroConsole */

namespace Micro\Cli;

use Micro\Base\IContainer;

/**
 * Console class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Cli
 * @version 1.0
 * @since 1.0
 * @abstract
 */
class Console
{
    /** @var IContainer $container */
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
     * @param IContainer $container
     *
     * @result void
     */
    public function __construct(IContainer $container)
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
        $command = '\\Micro\\Cli\\Consoles\\' . $name . 'ConsoleCommand';
        $command = class_exists($command) ? $command : '\\App\\Consoles\\' . $name . 'ConsoleCommand';

        if (!class_exists($command)) {
            return false;
        }

        /** @var ConsoleCommand $command */
        $command = new $command (['container' => $this->container, 'args' => $this->args]);
        $command->execute();

        return $command;
    }
}
