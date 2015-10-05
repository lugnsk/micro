<?php /** MicroInterfaceContainer */

namespace Micro\base;

/**
 * Interface IContainer
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 *
 * @property string $company
 * @property string $slogan
 * @property string $lang
 * @property string $errorController
 * @property string $errorAction
 * @property string $assetsDirName
 *
 * @property \Micro\web\FlashMessage $flash
 *
 * @property \Micro\Micro $kernel
 * @property \Micro\auth\IAuth $permission
 * @property \Micro\base\IDispatcher $dispatcher
 * @property \Micro\mail\transport\ITransport $mail
 * @property \Micro\db\IConnection $db
 * @property \Micro\web\IRouter $router
 * @property \Micro\web\IRequest $request
 * @property \Micro\web\IResponse $response
 * @property \Micro\web\ICookie $cookie
 * @property \Micro\web\ISession $session
 * @property \Micro\web\IUser $user
 */
interface IContainer
{
    /**
     * Load more configs from file
     *
     * @access public
     *
     * @param string $filename
     *
     * @return void
     */
    public function load($filename);

    /**
     * Is set component or option name into Container
     *
     * @access public
     *
     * @param string $name Name attribute
     *
     * @return bool
     */
    public function __isset($name);

    /**
     * Get Container value
     *
     * @access public
     *
     * @param string $name element name
     *
     * @return mixed
     */
    public function __get($name = '');

    /**
     * Set attribute
     *
     * @access public
     *
     * @param string $name Name attribute
     * @param mixed $component Component or option
     *
     * @return void
     */
    public function __set($name, $component);

    /**
     * Get component's
     *
     * @access public
     *
     * @param string|null $name name element to initialize
     *
     * @return bool
     */
    public function configure($name = null);

    /**
     * Load component
     *
     * @access public
     *
     * @param string $name component name
     * @param array $options component configs
     *
     * @return bool
     */
    public function loadComponent($name, $options);
}
