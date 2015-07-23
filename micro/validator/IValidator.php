<?php

namespace Micro\validator;

use Micro\db\IModel;

interface IValidator
{
    /**
     * Validate on server, make rule
     *
     * @access public
     *
     * @param IModel $model checked model
     *
     * @return bool
     */
    public function validate($model);

    /**
     * Client-side validation, make js rule
     *
     * @access public
     *
     * @param IModel $model model from elements
     *
     * @return string
     */
    public function client($model);
}