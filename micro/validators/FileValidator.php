<?php /** MicroFileValidator */

namespace Micro\validators;

use Micro\base\Validator;
use Micro\db\Model;
use Micro\web\Uploader;

/**
 * EmailValidator class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage validators
 * @version 1.0
 * @since 1.0
 */
class FileValidator extends Validator
{
    /**
     * Validate on server, make rule
     *
     * @access public
     * @param Model $model checked model
     * @return bool
     */
    public function validate($model)
    {
        foreach ($this->elements AS $element) {
            $files = new Uploader;
            if (isset($this->params['maxFiles']) AND (count($files->files) > $this->params['maxFiles'])) {
                $this->errors[] = 'Too many files in parameter ' . $element;
                return false;
            }
            foreach ($files->files AS $fContext) {
                if (isset($this->params['types']) AND (strpos($this->params['types'], $fContext['type'])===FALSE)) {
                    $this->errors[] = 'File ' . $fContext['name'] . ' not allowed type';
                    return false;
                }
                if (isset($this->params['minSize']) AND ($fContext['size'] < $this->params['minSize'])) {
                    $this->errors[] = 'File ' . $fContext['name'] . ' too small size';
                    return false;
                }
                if (isset($this->params['maxSize']) AND ($fContext['type'] > $this->params['maxSize'])) {
                    $this->errors[] = 'File ' . $fContext['name'] . ' too many size';
                    return false;
                }
            }
        }
        return true;
    }
}