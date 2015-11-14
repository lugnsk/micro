<?php /** MicroFileValidator */

namespace Micro\validator;

use Micro\form\IFormModel;

/**
 * EmailValidator class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage validator
 * @version 1.0
 * @since 1.0
 */
class FileValidator extends BaseValidator
{
    /**
     * @inheritdoc
     */
    public function validate(IFormModel $model)
    {
        foreach ($this->elements AS $element) {
            if (!$model->checkAttributeExists($element)) {
                $files = $this->container->request->getFiles();
                if (!empty($this->params['maxFiles']) && (count($files->files) > $this->params['maxFiles'])) {
                    $this->errors[] = 'Too many files in parameter ' . $element;

                    return false;
                }
                foreach ($files->files AS $fContext) {
                    if (!empty($this->params['types']) && (strpos($this->params['types'],
                                $fContext['type']) === false)
                    ) {
                        $this->errors[] = 'File ' . $fContext['name'] . ' not allowed type';

                        return false;
                    }
                    if (!empty($this->params['minSize']) && ($fContext['size'] < $this->params['minSize'])) {
                        $this->errors[] = 'File ' . $fContext['name'] . ' too small size';

                        return false;
                    }
                    if (!empty($this->params['maxSize']) && ($fContext['type'] > $this->params['maxSize'])) {
                        $this->errors[] = 'File ' . $fContext['name'] . ' too many size';

                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function client(IFormModel $model)
    {
        return '';
    }
}
