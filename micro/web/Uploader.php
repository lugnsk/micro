<?php /** MicroUploader */

namespace Micro\web;

/**
 * Uploader class file.
 *
 * Interface for upload files
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage web
 * @version 1.0
 * @since 1.0
 */
class Uploader
{
    /** @var array $files upload files */
    public $files = [];

    /**
     * Constructor uploads
     *
     * @access public
     * @result void
     */
    public function __construct()
    {
        if (null !== $_FILES) {
            if (!empty($_FILES['name'])) {
                $sumFiles = count($_FILES['name']);
                for ($i = 0; $i < $sumFiles; $i++) {
                    if (empty($_FILES['name'][$i])) {
                        break;
                    }
                    $this->files[] = [
                        'name'     => $_FILES['name'][$i],
                        'type'     => $_FILES['type'][$i],
                        'error'    => $_FILES['error'][$i],
                        'tmp_name' => $_FILES['tmp_name'][$i],
                        'size'     => $_FILES['size'][$i]
                    ];
                }
            } else {
                $this->files[] = $_FILES;
            }
        }
    }
}