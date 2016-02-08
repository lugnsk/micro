<?php /** MicroCaptchaValidator */

namespace Micro\Validator;

use Micro\Form\IFormModel;

/**
 * CaptchaValidator class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package Micro
 * @subpackage Validator
 * @version 1.0
 * @since 1.0
 */
class CaptchaValidator extends BaseValidator
{
    /** @var string $captcha compiled captcha */
    protected $captcha = '';


    /**
     * Constructor validator
     *
     * @access public
     *
     * @param array $params Configuration array
     *
     * @result void
     */
    public function __construct(array $params)
    {
        parent::__construct($params);

        $this->captcha = $this->container->user->getCaptcha();
    }

    /**
     * @inheritdoc
     */
    public function validate(IFormModel $model)
    {
        foreach ($this->elements AS $element) {
            if (!$model->checkAttributeExists($element)) {
                $this->errors[] = 'Parameter ' . $element . ' not defined in class ' . get_class($model);

                return false;
            }

            if ($this->container->user->checkCaptcha($this->captcha)) {
                return false;
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
