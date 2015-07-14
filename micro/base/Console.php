<?php /** MicroConsole */

namespace Micro\base;

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
    /** @var string $command Parsed command */
    protected $command;
    /** @var array $args Arguments from params */
    protected $args = [];

    /**
     * Constructor command
     *
     * @access public
     *
     * @param array $params arguments command
     *
     * @result void
     */
    public function __construct(array $params = [])
    {
        array_shift($params);
        $this->command = '\\App\\consoles\\' . ucfirst(array_shift($params)) . 'ConsoleCommand';

        foreach ($params AS $param) {
            $pos = strpos($param, '=');
            $this->args[substr($param, 0, $pos)] = substr($param, $pos + 1);
        }
    }

    /**
     * Get parsed command
     *
     * @access public
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Get parsed arguments
     *
     * @access public
     * @return array
     */
    public function getParams()
    {
        return $this->args;
    }
}