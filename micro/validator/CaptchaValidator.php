<?php /** MicroCaptchaValidator */

namespace Micro\validator;

use Micro\db\Model;

/**
 * CaptchaValidator class file.
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
class CaptchaValidator extends Validator
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
        $this->captcha = $this->container->user->getCaptcha();

        parent::__construct($params);
    }

    /**
     * Validate on server, make rule
     *
     * @access public
     * @global      Container
     *
     * @param Model $model checked model
     *
     * @return bool
     */
    public function validate($model)
    {
        foreach ($this->elements AS $element) {
            if (!$model->checkAttributeExists($element)) {
                $this->errors[] = 'Parameter ' . $element . ' not defined in class ' . get_class($model);

                return false;
            }

            $convert = $this->container->user->makeCaptcha($model->$element);
            if ($convert !== $this->captcha) {
                return false;
            }
        }

        return true;
    }
}