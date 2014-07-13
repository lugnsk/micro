<?php /* MicroFormBuilder */

/**
 * Class MFormBuilder.
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
class MFormBuilder
{
	protected $widget;
	/** @property MForm $form */
	protected $form;

	private $config;
	private $model;

	public function __construct($config = array(), $model=null, $method='GET', $type='text/plain', $action='') {
		$this->config = $config;
		$this->model = $model;
		$this->widget = new MFormWidget(array('action'=>$action,'method'=>$method,'type'=>$type));
	}
	public function setModelData($data=array()) {
		foreach ($data AS $key=>$value) {
			$this->model->$key = $value;
		}
	}
	public function validateModel() {
		return $this->model->validate();
	}
	public  function getModelErrors() {
		return $this->model->getErrors();
	}
	public function getModel() {
		return $this->model;
	}
	public function __toString() {
		return $this->render();
	}
	public function render() {
		ob_start();

		$this->beginRender();
		$this->contentRender();
		$this->endRender();

		return ob_get_clean();
	}
	public function beginRender() {
		$this->form = $this->widget->init();
		if (isset($this->config['legend'])) {
			echo MHtml::openTag('fieldset');
			echo MHtml::legend( $this->config['legend'] );
		}
		if (isset($this->config['description'])) {
			echo MHtml::openTag('div',array('class'=>'description')), $this->config['description'], MHtml::closeTag('div');
		}
		if ($this->model) {
			if ($errors = $this->getModelErrors()) {
				echo MHtml::openTag('div',array('class'=>'errors'));
				foreach ($errors AS $error) {
					echo MHtml::openTag('div',array('class'=>'error')), $error, MHtml::closeTag('div');
				}
				echo MHtml::closeTag('div');
			}
		}
	}
	public function endRender() {
		if (isset($this->config['legend'])) {
			echo MHtml::closeTag('fieldset');
		}
		$this->widget->run();
	}
	public function contentRender($conf=null) {
		if (!$conf) {
			$conf = $this->config;
		}
		foreach ($conf['elements'] AS $key=>$value) {
			if (is_array($conf['elements'][$key])) {
				if ($value['type']=='form') {
					$subForm = new MFormBuilder($value, (isset($value['model'])) ? $value['model'] : null);
					echo $subForm;
				} elseif ($this->model) {
					echo $this->form->$value['type']($this->model,$key,(isset($value['options'])) ? $value['options'] : array());
				} else {
					echo MHtml::$value['type']($key, $value['value'], $value['options']);
				}
			} else {
				echo $conf['elements'][$key];
			}
		}
		foreach ($this->config['buttons'] AS $button) {
			$type = $button['type'].'Button';
			echo MHtml::$type($button['label'], (isset($button['options'])) ? $button['options'] : array() );
		}
	}
}