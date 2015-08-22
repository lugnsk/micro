<?php /** MicroFTP */

namespace Micro\web;

/**
 * Simple FTP Class
 *
 * @author Shay Anderson 05.11
 * @link shayanderson.com
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @package micro
 * @subpackage wrappers
 * @version 1.0
 * @since 1.0
 * @final
 */
final class Ftp
{
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
     * Initialize connection params
     *
     * @access public
     *
     * @param array $params
     *
     * @result void
     */
    public function  __construct(array $params = [])
    {
        $this->_host = $params['host'] ?: null;
        $this->_user = $params['user'] ?: null;
        $this->_pwd = $params['password'] ?: null;
        $this->_port = (int)$params['port'] ?: 21;
        $this->_timeout = (int)$params['timeout'] ?: 90;
    }

    /**
     * Auto close connection
     */
    public function  __destruct()
    {
        $this->close();
    }

    /**
     * Close FTP connection
     */
    public function close()
    {
        // check for valid FTP stream
        if ($this->_stream) {
            // close FTP connection
            ftp_close($this->_stream);

            // reset stream
            $this->_stream = false;
        }
    }

    /**
     * Change current directory on FTP server
     *
     * @param string $directory
     *
     * @return bool
     */
    public function cd($directory = null)
    {
        if (ftp_chdir($this->_stream, $directory)) {
            return true;
        }

        $this->error = "Failed to change directory to \"{$directory}\"";

        return false;
    }

    /**
     * Set file permissions
     *
     * @param int $permissions (ex: 0644)
     * @param string $remote_file
     *
     * @return false
     */
    public function chmod($permissions = 0, $remote_file = null)
    {
        if (ftp_chmod($this->_stream, $permissions, $remote_file)) {
            return true;
        }

        $this->error = 'Failed to set file permissions for "' . $remote_file . '"';

        return false;
    }

    /**
     * Connect to FTP server
     *
     * @return bool
     */
    public function connect()
    {
        $func = $this->ssl ? 'ftp_ssl_connect' : 'ftp_connect';
        $this->_stream = $func($this->_host, $this->_port, $this->_timeout);

        if (!$this->_stream) {
            $this->error = 'Failed to connect ' . $this->_host . '.';
            return false;
        }

        if (ftp_login($this->_stream, $this->_user, $this->_pwd)) {
            ftp_pasv($this->_stream, (bool)$this->passive);

            $this->system_type = ftp_systype($this->_stream);

            return true;
        }

        $this->error = 'Failed to connect to ' . $this->_host . ' (login failed)';

        return false;
    }

    /**
     * Delete file on FTP server
     *
     * @param string $remote_file
     *
     * @return bool
     */
    public function delete($remote_file = null)
    {
        if (ftp_delete($this->_stream, $remote_file)) {
            return true;
        }

        $this->error = 'Failed to delete file "' . $remote_file . '"';

        return false;
    }

    /**
     * Download file from server
     *
     * @param string $remote_file
     * @param string $local_file
     * @param int $mode
     *
     * @return bool
     */
    public function get($remote_file = null, $local_file = null, $mode = FTP_ASCII)
    {
        if (ftp_get($this->_stream, $local_file, $remote_file, $mode)) {
            return true;
        }

        $this->error = 'Failed to download file "' . $remote_file . '"';

        return false;
    }

    /**
     * Get list of files/directories in directory
     *
     * @param string $directory
     *
     * @return array
     */
    public function ls($directory = null)
    {
        if ($list = ftp_nlist($this->_stream, $directory)) {
            return $list;
        }

        $this->error = 'Failed to get directory list';

        return [];
    }

    /**
     * Create directory on FTP server
     *
     * @param string $directory
     *
     * @return bool
     */
    public function mkdir($directory = null)
    {
        if (ftp_mkdir($this->_stream, $directory)) {
            return true;
        }

        $this->error = 'Failed to create directory "' . $directory . '"';

        return false;
    }

    /**
     * Upload file to server
     *
     * @param string $local_file
     * @param string $remote_file
     * @param int $mode
     *
     * @return bool
     */
    public function put($local_file = null, $remote_file = null, $mode = FTP_ASCII)
    {
        if (ftp_put($this->_stream, $remote_file, $local_file, $mode)) {
            return true;
        }

        $this->error = 'Failed to upload file "' . $local_file . '"';

        return false;
    }

    /**
     * Get current directory
     *
     * @return string
     */
    public function pwd()
    {
        return ftp_pwd($this->_stream);
    }

    /**
     * Rename file on FTP server
     *
     * @param string $old_name
     * @param string $new_name
     *
     * @return bool
     */
    public function rename($old_name = null, $new_name = null)
    {
        if (ftp_rename($this->_stream, $old_name, $new_name)) {
            return true;
        }

        $this->error = 'Failed to rename file "' . $old_name . '"';

        return false;
    }

    /**
     * Remove directory on FTP server
     *
     * @param string $directory
     *
     * @return bool
     */
    public function rmdir($directory = null)
    {
        if (ftp_rmdir($this->_stream, $directory)) {
            return true;
        }

        $this->error = 'Failed to remove directory "' . $directory . '"';

        return false;
    }
}
