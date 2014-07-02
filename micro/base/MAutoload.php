<?php /** MicroAutoloader */

/**
 * MAutoload class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
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
	 * @global Micro
	 * @param string $className
	 * @return void
	 */
	public static function autoloaderController($className) {
		$micro = Micro::getInstance();

		if (method_exists(MController::$module, 'setImport')) {
			foreach (MController::$module->setImport() AS $path) {
				$path = '/' . str_replace('.', '/', $path);
				self::autoloader($className, $micro->config['AppDir'] . $path);
			}
		}
	}
	/**
	 * Function load files in application
	 *
	 * @access public
	 * @global Micro
	 * @param  string $className
	 * @param  string $path
	 * @return bool
	 */
	public static function autoloader($className, $path = null) {
		if (!$path) {
			$config = Micro::getInstance()->config;
			// Find in Micro
			if (array_key_exists($className, self::$files)) {
				include $config['MicroDir'] . self::$files[$className];
				return true;
			} else {
				$path = $config['AppDir'];
			}
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
		if (is_dir($dir)) {
			$files = array_diff( scandir( $dir ), array( '.', '..' ) );
		} else {
			$files[] = $dir.'/'.$toSearch;
		}

		foreach( $files as $d ) {
			if( !is_dir($dir.'/'.$d) ) {
				if ($d == $toSearch) {
					return $dir.'/'.$d;
				}
			} else {
				$res = self::find($dir.'/'.$d, $toSearch);
				if ($res) {
					return $res;
				}
			}
		}
		return false;
	}

	private static $files = array(
		'Micro'=>'/Micro.php',
		'MAutoload'=>'/base/MAutoload.php',
		'MCommand'=>'/base/MCommand.php',
		'MController'=>'/base/MController.php',
		'MException'=>'/base/MException.php',
		'MFile'=>'/base/MFile.php',
		'MLanguage'=>'/base/MLanguage.php',
		'MRegistry'=>'/base/MRegistry.php',
		'MSession'=>'/base/MSession.php',
		'MWidget'=>'/base/MWidget.php',
		'MDbConnection'=>'/db/MDbConnection.php',
		'MMigration'=>'/db/MMigration.php',
		'MModel'=>'/db/MModel.php',
		'MQuery'=>'/db/MQuery.php',
		'MAssets'=>'/web/MAssets.php',
		'MForm'=>'/web/MForm.php',
		'MRequest'=>'/web/MRequest.php',
		'MRouter'=>'/web/MRouter.php',
		'MFlashMessage'=>'/web/helpers/MFlashMessage.php',
		'MFtp'=>'/web/helpers/MFtp.php',
		'MHtml'=>'/web/helpers/MHtml.php',
		'MMail'=>'/web/helpers/MMail.php',
		'MUser'=>'/web/helpers/MUser.php',
		'MFormWidget'=>'/widgets/MFormWidget.php',
		'MMenuWidget'=>'/widgets/MMenuWidget.php'
	);
}