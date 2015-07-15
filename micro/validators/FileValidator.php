<?php /** MicroFileValidator */

namespace Micro\validators;

use Micro\base\Validator;
use Micro\db\Model;

/**
 * EmailValidator class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
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
     *
     * @param Model $model checked model
     *
     * @return bool
     */
    public function validate($model)
    {
        foreach ($this->elements AS $element) {
            if (!$model->checkAttributeExists($element)) {
                $files = $this->container->request->getFiles();
                if (!empty($this->params['maxFiles']) AND (count($files->files) > $this->params['maxFiles'])) {
                    $this->errors[] = 'Too many files in parameter ' . $element;

                    return false;
                }
                foreach ($files->files AS $fContext) {
                    if (!empty($this->params['types']) AND (strpos($this->params['types'],
                                $fContext['type']) === false)
                    ) {
                        $this->errors[] = 'File ' . $fContext['name'] . ' not allowed type';

                        return false;
                    }
                    if (!empty($this->params['minSize']) AND ($fContext['size'] < $this->params['minSize'])) {
                        $this->errors[] = 'File ' . $fContext['name'] . ' too small size';

                        return false;
                    }
                    if (!empty($this->params['maxSize']) AND ($fContext['type'] > $this->params['maxSize'])) {
                        $this->errors[] = 'File ' . $fContext['name'] . ' too many size';

                        return false;
                    }
                }
            }
        }

        return true;
    }
}