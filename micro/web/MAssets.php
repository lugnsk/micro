<?php

/**
 * MAssets class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license http://opensource.org/licenses/MIT
 * @package micro
 * @subpackage web
 * @version 1.0
 * @since 1.0
 */
class MAssets
{
	/** @var string $assetDir */
	private $assetDir = 'assets';

	/**
	 * Publish dir or file for user
	 *
	 * @access public
	 * @param string $dir
	 * @return void
	 */
	public function publish($dir) {
		$hashDir = $this->getPublishDir($dir);

		if (!file_exists($hashDir)) {
			@mkdir($hashDir, 0777);
		}

		if (is_dir($dir)) {
			MFile::recurseCopyIfEdited($dir, $hashDir);
		} else {
			if (filemtime($dir) != filemtime($hashDir)) {
				copy($dir, $hashDir);
				@chmod($hashDir.$dir, 0666);
			}
		}
	}

	/**
	 * Get publish dir
	 *
	 * @param string $dir
	 * @return string
	 */
	public function getPublishDir($dir) {
		return $this->assetDir . DIRECTORY_SEPARATOR . md5($dir);
	}
}