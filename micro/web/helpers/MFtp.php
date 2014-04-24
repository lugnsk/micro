<?php

/**
 * Simple FTP Class
 *
 * @author Shay Anderson 05.11
 * @link shayanderson.com
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @package micro
 * @subpackage web
 * @subpackage helpers
 * @version 1.0
 */
final class MFtp {
	/**
	 * FTP host
	 *
	 * @var string $_host
	 */
	private $_host;

	/**
	 * FTP port
	 *
	 * @var int $_port
	 */
	private $_port = 21;

	/**
	 * FTP password
	 *
	 * @var string $_pwd
	 */
	private $_pwd;
	
	/**
	 * FTP stream
	 *
	 * @var resource $_id
	 */
	private $_stream;

	/**
	 * FTP timeout
	 *
	 * @var int $_timeout
	 */
	private $_timeout = 90;

	/**
	 * FTP user
	 *
	 * @var string $_user
	 */
	private $_user;

	/**
	 * Last error
	 *
	 * @var string $error
	 */
	public $error;

	/**
	 * FTP passive mode flag
	 *
	 * @var bool $passive
	 */
	public $passive = false;

	/**
	 * SSL-FTP connection flag
	 *
	 * @var bool $ssl
	 */
	public $ssl = false;

	/**
	 * System type of FTP server
	 *
	 * @var string $system_type
	 */
	public $system_type;

	/**
	 * Initialize connection params
	 *
	 * @param string $host
	 * @param string $user
	 * @param string $password
	 * @param int $port
	 * @param int $timeout (seconds)
	 */
	public function  __construct($host = null, $user = null, $password = null, $port = 21, $timeout = 90) {
		$this->_host = $host;
		$this->_user = $user;
		$this->_pwd = $password;
		$this->_port = (int)$port;
		$this->_timeout = (int)$timeout;
	}

	/**
	 * Auto close connection
	 */
	public function  __destruct() {
		$this->close();
	}

	/**
	 * Change currect directory on FTP server
	 *
	 * @param string $directory
	 * @return bool
	 */
	public function cd($directory = null) {
		// attempt to change directory
		if(ftp_chdir($this->_stream, $directory)) {
			// success
			return true;
		// fail
		} else {
			$this->error = "Failed to change directory to \"{$directory}\"";
			return false;
		}
	}

	/**
	 * Set file permissions
	 *
	 * @param int $permissions (ex: 0644)
	 * @param string $remote_file
	 * @return false
	 */
	public function chmod($permissions = 0, $remote_file = null) {
		// attempt chmod
		if(ftp_chmod($this->_stream, $permissions, $remote_file)) {
			// success
			return true;
		// failed
		} else {
			$this->error = "Failed to set file permissions for \"{$remote_file}\"";
			return false;
		}
	}

	/**
	 * Close FTP connection
	 */
	public function close() {
		// check for valid FTP stream
		if($this->_stream) {
			// close FTP connection
			ftp_close($this->_stream);

			// reset stream
			$this->_stream = false;
		}
	}

	/**
	 * Connect to FTP server
	 *
	 * @return bool
	 */
	public function connect() {
		// check if non-SSL connection
		if(!$this->ssl) {
			// attempt connection
			if(!$this->_stream = ftp_connect($this->_host, $this->_port, $this->_timeout)) {
				// set last error
				$this->error = "Failed to connect to {$this->_host}";
				return false;
			}
		// SSL connection
		} elseif(function_exists("ftp_ssl_connect")) {
			// attempt SSL connection
			if(!$this->_stream = ftp_ssl_connect($this->_host, $this->_port, $this->_timeout)) {
				// set last error
				$this->error = "Failed to connect to {$this->_host} (SSL connection)";
				return false;
			}
		// invalid connection type
		} else {
			$this->error = "Failed to connect to {$this->_host} (invalid connection type)";
			return false;
		}

		// attempt login
		if(ftp_login($this->_stream, $this->_user, $this->_pwd)) {
			// set passive mode
			ftp_pasv($this->_stream, (bool)$this->passive);

			// set system type
			$this->system_type = ftp_systype($this->_stream);

			// connection successful
			return true;
		// login failed
		} else {
			$this->error = "Failed to connect to {$this->_host} (login failed)";
			return false;
		}
	}

	/**
	 * Delete file on FTP server
	 *
	 * @param string $remote_file
	 * @return bool
	 */
	public function delete($remote_file = null) {
		// attempt to delete file
		if(ftp_delete($this->_stream, $remote_file)) {
			// success
			return true;
		// fail
		} else {
			$this->error = "Failed to delete file \"{$remote_file}\"";
			return false;
		}
	}

	/**
	 * Download file from server
	 *
	 * @param string $remote_file
	 * @param string $local_file
	 * @param int $mode
	 * @return bool
	 */
	public function get($remote_file = null, $local_file = null, $mode = FTP_ASCII) {
		// attempt download
		if(ftp_get($this->_stream, $local_file, $remote_file, $mode)) {
			// success
			return true;
		// download failed
		} else {
			$this->error = "Failed to download file \"{$remote_file}\"";
			return false;
		}
	}

	/**
	 * Get list of files/directories in directory
	 *
	 * @param string $directory
	 * @return array
	 */
	public function ls($directory = null) {
		// attempt to get list
		if($list = ftp_nlist($this->_stream, $directory)) {
			// success
			return $list;
		// fail
		} else {
			$this->error = "Failed to get directory list";
			return array();
		}
	}

	/**
	 * Create directory on FTP server
	 *
	 * @param string $directory
	 * @return bool
	 */
	public function mkdir($directory = null) {
		// attempt to create dir
		if(ftp_mkdir($this->_stream, $directory)) {
			// success
			return true;
		// fail
		} else {
			$this->error = "Failed to create directory \"{$directory}\"";
			return false;
		}
	}

	/**
	 * Upload file to server
	 *
	 * @param string $local_file
	 * @param string $remote_file
	 * @param int $mode
	 * @return bool
	 */
	public function put($local_file = null, $remote_file = null, $mode = FTP_ASCII) {
		// attempt to upload file
		if(ftp_put($this->_stream, $remote_file, $local_file, $mode)) {
			// success
			return true;
		// upload failed
		} else {
			$this->error = "Failed to upload file \"{$local_file}\"";
			return false;
		}
	}

	/**
	 * Get current directory
	 *
	 * @return string
	 */
	public function pwd() {
		return ftp_pwd($this->_stream);
	}

	/**
	 * Rename file on FTP server
	 *
	 * @param string $old_name
	 * @param string $new_name
	 * @return bool
	 */
	public function rename($old_name = null, $new_name = null) {
		// attempt rename
		if(ftp_rename($this->_stream, $old_name, $new_name)) {
			// success
			return true;
		// fail
		} else {
			$this->error = "Failed to rename file \"{$old_name}\"";
			return false;
		}
	}

	/**
	 * Remove directory on FTP server
	 *
	 * @param string $directory
	 * @return bool
	 */
	public function rmdir($directory = null) {
		// attempt remove dir
		if(ftp_rmdir($this->_stream, $directory)) {
			// success
			return true;
		// fail
		} else {
			$this->error = "Failed to remove directory \"{$directory}\"";
			return false;
		}
	}
}