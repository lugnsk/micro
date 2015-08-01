<?php /** MicroInterfaceModel */

namespace Micro\mvc\models;

use Micro\form\IFormModel;

interface IModel extends IFormModel
{
    /**
     * Get name of table
     *
     * @access public
     * @return string
     * @static
     */
    public static function tableName();

    /**
     * Relations for model
     *
     * @access public
     * @return IRelations
     */
    public function relations();

    /**
     * Before create actions
     *
     * @access public
     * @return boolean
     */
    public function beforeCreate();

    /**
     * After create actions
     *
     * @access public
     * @return void
     */
    public function afterCreate();

    /**
     * Before save actions
     *
     * @access public
     * @return boolean
     */
    public function beforeSave();

    /**
     * After save actions
     *
     * @access public
     * @return void
     */
    public function afterSave();

    /**
     * Before update actions
     *
     * @access public
     * @return boolean
     */
    public function beforeUpdate();

    /**
     * After update actions
     *
     * @access public
     * @return boolean
     */
    public function afterUpdate();

    /**
     * Before delete actions
     *
     * @access public
     * @return boolean
     */
    public function beforeDelete();

    /**
     * After delete actions
     *
     * @access public
     * @return void
     */
    public function afterDelete();
}
