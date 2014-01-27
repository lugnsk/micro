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
	 * @param string $classname
	 * @return void
	 */
	public static function autoloaderController($classname) {
		$micro = Micro::getInstance();

		if (method_exists(MController::$module, 'setImport')) {
			foreach (MController::$module->setImport() AS $path) {
				$path = DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $path);
				self::autoloader($classname, $micro->config['AppDir'] . $path);
			}
		}
	}

	/**
	 * Function load files in application
	 *
	 * @access public
	 * @param  string classname
	 * @param  string path
	 * @return bool   status
	 */
	public static function autoloader($classname, $path = null) {
		if (!$path) {
			$config = Micro::getInstance()->config;
			if (!isset($config['MicroDir']) OR empty($config['MicroDir'])) {
				return false;
			}
			if (isset($config['AppDir']) AND !empty($config['AppDir'])) {
				if (isset($config['import']) AND !empty($config['import'])) {
					$paths = $config['import'];
					foreach ($paths AS $pat) {
						self::autoloader($classname, $config['AppDir'] . DIRECTORY_SEPARATOR . $pat);
					}
				}
			}
			$path = $config['MicroDir'];
		}

		if (!file_exists($path)) {
			return false;
		}

		// search in micro dir
		if ($path = self::find($path, $classname.'.php')) {
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
	 * @param string #tosearch
	 * @return bool|string
	 */
	public static function find($dir, $tosearch) {
		$files = array_diff( scandir( $dir ), Array( ".", ".." ) );

		foreach( $files as $d ) {
			if( !is_dir($dir."/".$d) ) {
				if ($d == $tosearch)
					return $dir."/".$d;
			} else {
				$res = self::find($dir."/".$d, $tosearch);
				if ($res)
					return $res;
			}
		}
		return false;
	}
}