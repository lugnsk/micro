<?php

/**
 * MAutoload class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license http://opensource.org/licenses/MIT
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class MAutoload
{
	/**
	 * Autoloader classes
	 *
	 * @access public
	 * @param string $className
	 * @return void
	 */
	public static function autoloaderController($className) {
		$micro = Micro::getInstance();

		if (method_exists(MController::$module, 'setImport')) {
			foreach (MController::$module->setImport() AS $path) {
				$path = DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $path);
				self::autoloader($className, $micro->config['AppDir'] . $path);
			}
		}
	}
	/**
	 * Function load files in application
	 *
	 * @access public
	 * @param  string $className
	 * @param  string $path
	 * @return bool
	 */
	public static function autoloader($className, $path = null) {
		if (!$path) {
			$config = Micro::getInstance()->config;
			if (!isset($config['MicroDir']) OR empty($config['MicroDir'])) {
				return false;
			}
			if (isset($config['AppDir']) AND !empty($config['AppDir'])) {
				if (isset($config['import']) AND !empty($config['import'])) {
					$paths = $config['import'];
					foreach ($paths AS $pat) {
						self::autoloader($className, $config['AppDir'] . DIRECTORY_SEPARATOR . $pat);
					}
				}
			}
			$path = $config['MicroDir'];
		}

		if (!file_exists($path)) {
			return false;
		}

		// search in micro dir
		if ($path = self::find($path, $className.'.php')) {
			include $path;
			return true;
		}
		return false;
	}
	/**
	 * Find file in directory
	 *
	 * @access public
	 * @param string $dir
	 * @param string $toSearch
	 * @return bool|string
	 */
	public static function find($dir, $toSearch) {
		$files = array_diff( scandir( $dir ), Array( ".", ".." ) );

		foreach( $files as $d ) {
			if( !is_dir($dir."/".$d) ) {
				if ($d == $toSearch)
					return $dir."/".$d;
			} else {
				$res = self::find($dir."/".$d, $toSearch);
				if ($res)
					return $res;
			}
		}
		return false;
	}
}