<?php /** MicroUploader */

namespace Micro\web;

/**
 * Uploader class file.
 *
 * Interface for upload files
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
class Uploader
{
    /** @var array $files */
    public $files = [];

    /**
     * Constructor uploads
     *
     * @access public
     * @result void
     */
    public function __construct()
    {
        if (isset($_FILES)) {
            if (isset($_FILES['name'])) {
                $summ = count($_FILES['name']);
                for ($i = 0; $i < $summ; $i++) {
                    if (!isset($_FILES['name'][$i])) {
                        break;
                    }
                    $this->files[] = [
                        'name' => $_FILES['name'][$i],
                        'type' => $_FILES['type'][$i],
                        'error' => $_FILES['error'][$i],
                        'tmp_name' => $_FILES['tmp_name'][$i],
                        'size' => $_FILES['size'][$i]
                    ];
                }
            } else {
                $this->files[] = $_FILES;
            }
        }
    }
}