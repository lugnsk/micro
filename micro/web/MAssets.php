<?php /** MicroAssets */

/**
 * MAssets class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web
 * @version 1.0
 * @since 1.0
 */
class MAssets
{
	/** @var string $assetDir directory for assets */
	private $assetDir = 'assets';
	private $hash = '';
	private $directory = '';
	private $sourceDir = '';
	private $publishDir = '';


	public function __construct($directory = '') {
		$this->directory = $directory;
		$this->hash = md5($this->directory);

		$tmp = DIRECTORY_SEPARATOR . $this->assetDir . DIRECTORY_SEPARATOR . $this->hash;
		$this->publishDir = Micro::getInstance()->config['HtmlDir'] . $tmp;
		$this->sourceDir = Micro::getInstance()->config['WebDir'] . $tmp;
	}
	/**
	 * Publication directory or files
	 *
	 * @access public
	 * @param string $exclude exclude files
	 * @return void
	 */
	public function publish($exclude='.php') {
		$hashDir = $this->getSourceDir();

		if (!file_exists($hashDir)) {
			@mkdir($hashDir, 0777);
		}

		if (is_dir($this->directory)) {
			MFile::recurseCopyIfEdited($this->directory, $this->sourceDir);
		} else {
			if (substr($hashDir, strlen($hashDir)-strlen($exclude) ) != $exclude) {
				if (!file_exists($hashDir)) {
					copy($this->directory, $hashDir);
					@chmod($hashDir, 0666);
				} elseif (filemtime($this->directory) != filemtime($hashDir)) {
					copy($this->directory, $hashDir);
					@chmod($hashDir, 0666);
				}
			}
		}
	}
	/**
	 * Get publish directory
	 *
	 * @access public
	 * @return string
	 */
	public function getPublishDir() {
		return $this->publishDir;
	}
	/**
	 * Get source directory
	 *
	 * @access public
	 * @return string
	 */
	public function getSourceDir() {
		return $this->sourceDir;
	}
}