<?php /** MicroModule */

namespace Micro\Mvc;

use Micro\Base\IContainer;

/**
 * Class Module
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Mvc
 * @version 1.0
 * @since 1.0
 * @abstract
 */
abstract class Module
{
    /** @var IContainer $container */
    public $container;


    /**
     * @param IContainer $container
     */
    public function __construct(IContainer $container)
    {
        $this->container = $container;

        $path = dirname(
                str_replace(['\\', 'App'], ['/', $container->kernel->getAppDir()], get_called_class())
            ) . '/config.php';

        if (file_exists($path)) {
            $container->load($path);
        }
    }
}
