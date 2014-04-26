<?php /** MicroHtml */

/**
 * MHtml class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/antivir88/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license http://opensource.org/licenses/MIT
 * @package micro
 * @subpackage web
 * @version 1.0
 * @since 1.0
 */
class MHtml
{
	// BASIC Elements
	/**
	 * Render close tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  array $attributes
	 * @return string
	 */
	public static function tag($name, $attributes = array()) {
		$result = '';
		foreach ($attributes AS $elem => $value) {
			$result .= $elem . '="' . $value . '" ';
		}
		return '<'.$name.' '.$result.'/>';
	}
	/**
	 * Render close tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  array $attributes
	 * @return string
	 */
	public static function openTag($name, $attributes = array()) {
		$result = '';
		foreach ($attributes AS $key => $value) {
			$result .= $key . '="' . $value . '" ';
		}
		return '<'.$name.' '.$result.'>';
	}
	/**
	 * Render close tag
	 *
	 * @access public
	 * @param  string $name
	 * @return string
	 */
	public static function closeTag($name) {
		return '</'.$name.'>';
	}
	/**
	 * Base field tag
	 *
	 * @access private
	 * @param  string $type
	 * @param  string $name
	 * @param  string $value
	 * @param  array  $attributes
	 * @return string
	 */
	private static function field($type, $name,  $value = null, $attributes = array()) {
		$attributes['type'] = $type;
		$attributes['name'] = $name;
		$attributes['value'] = $value;
		return self::tag('input', $attributes);
	}
	// HEAD Elements
	/**
	 * Render meta tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $url
	 * @param  array  $attributes
	 * @return string
	 */
	public static function link($name, $url, $attributes = array()) {
		$attributes['href'] = $url;
		return self::openTag('link', $attributes) . $name . self::closeTag('link');
	}
	/**
	 * Render meta tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $content
	 * @param  array  $attributes
	 * @return string
	 */
	public static function meta($name, $content, $attributes = array()) {
		$attributes['name'] = $name;
		$attributes['content'] = $content;
		return self::tag('meta', $attributes);
	}
	/**
	 * Render favicon file
	 *
	 * @access public
	 * @param string $url
	 * @return string
	 */
	public static function favicon($url) {
		return self::tag('link', array('href'=>$url, 'rel'=>'shortcut icon', 'type'=>'image/x-icon'));
	}
	/**
	 * Render css file
	 *
	 * @access public
	 * @param  string $file
	 * @return string
	 */
	public static function cssFile($file) {
		$attributes = array('href'=>$file, 'rel'=>'stylesheet');
		return self::tag('link', $attributes);
	}
	/**
	 * Render script file
	 *
	 * @access public
	 * @param  string $file
	 * @return string
	 */
	public static function scriptFile($file) {
		$attributes = array('src'=>$file, 'type'=>'text/javascript');
		return self::openTag('script', $attributes) . self::closeTag('script');
	}
	/**
	 * Render script source
	 *
	 * @access public
	 * @param  string $text script
	 * @param  array $attributes
	 * @return string
	 */
	public static function script($text, $attributes = array()) {
		$attributes['type'] = 'text/javascript';
		return self::openTag('script', $attributes) ."\n/*<![CDATA[*/\n".$text."\n/*]]>*/\n". self::closeTag('script');
	}
	/**
	 * Render style source
	 *
	 * @access public
	 * @param  string $text style
	 * @param  array $attributes
	 * @return string
	 */
	public static function css($text, $attributes = array()) {
		$attributes['type'] = 'text/css';
		return self::openTag('style', $attributes) . $text . self::closeTag('style');
	}
	/**
	 * Render docType tag
	 *
	 * @access public
	 * @param  string $name
	 * @return string|boolean
	 */
	public static function doctype($name) {
		$docTypes = array(
			'xhtml11'		=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">',
			'xhtml1-trans'	=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
			'xhtml1-strict'	=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
			'xhtml1-frame'	=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
			'html4-trans'	=> '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
			'html4-strict'	=> '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">',
			'html4-frame'	=> '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">',
			'html5'			=> '<!DOCTYPE html>',
		);

		if (!isset($docTypes[$name])) {
			return false;
		}
		return $docTypes[$name];
	}
	/**
	 * Render title tag
	 *
	 * @access public
	 * @param string $name
	 * @return string
	 */
	public static function title($name){
		return self::openTag('title') . $name . self::closeTag('title');
	}
	// BODY Elements
	/**
	 * Render BR tag
	 *
	 * @access public
	 * @param integer $num
	 * @param array $attributes
	 * @return string
	 */
	public static function br($num, $attributes = array()) {
		$str='';
		for ($i = 0; $i < $num; $i++) {
			$str .= self::tag('br', $attributes);
		}
		return $str;
	}
	/**
	 * Render image file
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $file
	 * @param  array $attributes
	 * @return string
	 */
	public static function image($name, $file, $attributes = array()) {
		$attributes['src'] = $file;
		$attributes['alt'] = $name;
		return self::tag('img', $attributes);
	}
	/**
	 * Render Mail a tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $email
	 * @param  array $attributes
	 * @return string
	 */
	public static function mailto($name, $email, $attributes = array()) {
		$attributes['href'] = $email;
		return self::openTag('a', $attributes) . $name . self::closeTag('a');
	}
	/**
	 * Render H{1-N} tag
	 *
	 * @access public
	 * @param  string $num
	 * @param  string $value
	 * @param  array  $attributes
	 * @return string
	 */
	public static function heading($num,  $value = null, $attributes = array()) {
		return self::openTag('h'.$num, $attributes) . $value . self::closeTag('h'.$num);
	}
	// LIST Elements // marklist, numlist, deplist, applet, imgmap, object

	// TABLE Elements
	/**
	 * Render table element
	 *
	 * How to use $elements:
	 * array(
	 *     array( // row
	 *         'cells'=>array( // cell
	 *             'value'=>'text',
	 *             'attributes'=>array()
	 *         ),
	 *         attributes'=>array()
	 *     )
	 * )
	 * 
	 * @access public
	 * @param array $elements
	 * @param array $attributes
	 * @return string
	 */
	public static function table($elements = array(), $attributes = array()) {
		$output = null;
		foreach ($elements AS $value) {
			$output = self::tableRow(
				(isset($value['cells'])) ? $value['cells'] : null,
				(isset($value['attributes'])) ? $value['attributes'] : null
			);
		}
		return self::beginTable($attributes) . $output . self::endTable();
	}
	/**
	 * Render begin table element
	 *
	 * @access public
	 * @param array $attributes
	 * @return string
	 */
	public static function beginTable($attributes = array()) {
		return self::openTag('table', $attributes);
	}
	/**
	 * Render end table element
	 *
	 * @access public
	 * @return string
	 */
	public static function endTable() {
		return self::closeTag('table');
	}
	/**
	 * Render table caption element
	 *
	 * @access public
	 * @param string $text
	 * @param array $attributes
	 * @return string
	 */
	public static function tableCaption($text, $attributes = array()) {
		return self::openTag('caption', $attributes) . $text . self::closeTag('caption');
	}
	/**
	 * Render table row element
	 *
	 * @access public
	 * @param array $elements array(value, attributes)
	 * @param array $attributes
	 * @return string
	 */
	public static function tableRow($elements = array(),$attributes = array()) {
		$output = null;
		foreach ($elements AS $value) {
			$output = self::tableCell(
				(isset($value['value'])) ? $value['value'] : null,
				(isset($value['attributes'])) ? $value['attributes'] : null
			);
		}
		return self::openTag('tr', $attributes) . $output . self::closeTag('tr');
	}
	/**
	 * Render table cell element
	 *
	 * @access public
	 * @param string $text
	 * @param array $attributes
	 * @return string
	 */
	public static function tableCell($text, $attributes = array()) {
		return self::openTag('td', $attributes) . $text . self::closeTag('td');
	}
	// FORM Elements
	/**
	 * Render begin form tag
	 *
	 * @access public
	 * @param  string $action
	 * @param  string $method
	 * @param  array  $attributes
	 * @return string
	 */
	public static function beginForm($action, $method = 'POST', $attributes = array()) {
		$attributes['action'] = $action;
		$attributes['method'] = $method;
		return self::openTag('form', $attributes);
	}
	/**
	 * Render doctype tag
	 *
	 * @access public
	 * @return string
	 */
	public static function endForm() {
		return self::closeTag('form');
	}
	/**
	 * Render button tag
	 *
	 * @access public
	 * @param  string $text
	 * @param  array  $attributes
	 * @return string
	 */
	public static function button($text, $attributes = array()) {
		return self::openTag('button',$attributes) . $text . self::closeTag('button');
	}
	/**
	 * Render image button tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $file
	 * @param  array $attributesButton
	 * @param  array $attributesImage
	 * @return string
	 */
	public static function imageButton($name, $file, $attributesButton = array(), $attributesImage = array()) {
		return self::button(self::image($name, $file, $attributesImage), $attributesButton);
	}
	/**
	 * Render textArea tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $text
	 * @param  array  $attributes
	 * @return string
	 */
	public static function textArea($name, $text, $attributes = array()) {
		$attributes['name'] = $name;
		return self::openTag('textarea', $attributes) . $text . self::closeTag('textarea');
	}
	/**
	 * Render legend tag
	 *
	 * @access public
	 * @param  string $text
	 * @param  array  $attributes
	 * @return string
	 */
	public static function legend($text, $attributes = array()) {
		return self::openTag('legend', $attributes) . $text . self::closeTag('legend');
	}
	/**
	 * Generate label tag
	 *
	 * @access public
	 * @param string $name
	 * @param string $elemId
	 * @return string
	 */
	public static function label($name, $elemId = '') {
		$elemId = ($elemId) ? array('for'=>$elemId) : array() ;
		return self::openTag('label', $elemId) . $name . self::closeTag('label');
	}
	/**
	 * Generate option tag
	 *
	 * @access public
	 * @param string $value
	 * @param string $text
	 * @param array  $attributes
	 * @return string
	 */
	public static function option($value, $text, $attributes=array()) {
		$attributes['value'] = $value;
		return self::openTag('option', $attributes) . $text . self::closeTag('option');
	}
	/**
	 * Generate optGroup tag
	 *
	 * @access public
	 * @param string $label
	 * @param array $options format array(value, text, attributes) OR array(label, options, attributes)
	 * @param array $attributes
	 * @return string
	 */
	public static function optGroup($label, $options = array(), $attributes = array()) {
		$attributes['label'] = $label;
		$opts = '';
		foreach ($options AS $option) {
			if (isset($option['label'])) {
				$opts .= self::optgroup($option['label'], $option['options'], $option['attributes']);
			} else {
				$opts .= self::option($option['value'], $option['text'], $option['attributes']);
			}
		}
		return self::openTag('optgroup', $attributes) . $opts . self::closeTag('optgroup');
	}
	/**
	 * Generate dropDownList (select tag)
	 *
	 * @access public
	 * @param string $name
	 * @param array  $options format array(value, text, attributes) OR array(label, options, attributes)
	 * @param array  $attributes
	 * @return string
	 */
	public static function dropDownList($name, $options = array(), $attributes = array()) {
		$attributes['size'] = 1;
		return self::listbox($name, $options, $attributes);
	}
	/**
	 * Generate listBox (select tag)
	 *
	 * @access public
	 * @param string $name
	 * @param array  $options format array(value, text, attributes) OR array(label, options, attributes)
	 * @param array  $attributes
	 * @return string
	 */
	public static function listBox($name, $options = array(), $attributes = array()) {
		$attributes['name'] = $name;
		$opts = '';
		foreach ($options AS $option) {
			if (isset($option['label'])) {
				$opts .= self::optGroup($option['label'], $option['options'], $option['attributes']);
			} else {
				$opts .= self::option($option['value'], $option['text'], $option['attributes']);
			}
		}
		return self::openTag('select', $attributes) . $opts . self::closeTag('select');
	}
	/**
	 * Generate checkBoxList (input checkbox tags)
	 *
	 * @access public
	 * @param string $name
	 * @param array  $checkboxes format array(text, value, attributes)
	 * @param string $format %check% - checkbox , %text% - text
	 * @return string
	 */
	public static function checkBoxList($name, $checkboxes = array(), $format = '<p>%check% %text%</p>') {
		$checks = '';
		foreach ($checkboxes AS $checkbox) {
			$check = self::checkboxField($name, $checkbox['value'], $checkbox['attributes']);
			$checks .= str_replace('%text%', $checkbox['text'], str_replace('%check%', $check, $format));
		}
		return $checks;
	}
	/**
	 * Generate option tag
	 *
	 * @access public
	 * @param string $name
	 * @param array  $radios format array(text, value, attributes)
	 * @param string $format %radio% - radio , %text% - text
	 * @return string
	 */
	public static function radioButtonList($name, $radios = array(), $format = '<p>%radio% %text%</p>') {
		$rads = '';
		foreach ($radios AS $radio) {
			$rad = self::radioField($name, $radio['value'], $radio['attributes']);
			$rads .= str_replace('%text%', $radio['text'], str_replace('%radio%', $rad, $format));
		}
		return $rads;
	}
	// INPUT Elements
	/**
	 * Render reset button tag
	 *
	 * @access public
	 * @param  string $label
	 * @param  array  $attributes
	 * @return string
	 */
	public static function resetButton($label = 'Reset', $attributes = array()) {
		$attributes['type'] = 'reset';
		$attributes['value'] = $label;
		return self::tag('input', $attributes);
	}
	/**
	 * Render submit button tag
	 *
	 * @access public
	 * @param  string $label
	 * @param  array  $attributes
	 * @return string
	 */
	public static function submitButton($label = 'Submit', $attributes = array()) {
		$attributes['type'] = 'submit';
		$attributes['value'] = $label;
		return self::tag('input', $attributes);
	}
	/**
	 * Render input button tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $value
	 * @param  array  $attributes
	 * @return string
	 */
	public static function buttonField($name,  $value = null, $attributes = array()) {
		return self::field('button', $name, $value, $attributes);
	}
	/**
	 * Render input checkbox tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $value
	 * @param  array  $attributes
	 * @return string
	 */
	public static function checkBoxField($name,  $value = null, $attributes = array()) {
		return self::field('checkbox', $name, $value, $attributes);
	}
	/**
	 * Render input file tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $value
	 * @param  array  $attributes
	 * @return string
	 */
	public static function fileField($name,  $value = null, $attributes = array()) {
		return self::field('file', $name, $value, $attributes);
	}
	/**
	 * Render input hidden tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $value
	 * @param  array  $attributes
	 * @return string
	 */
	public static function hiddenField($name,  $value = null, $attributes = array()) {
		return self::field('hidden', $name, $value, $attributes);
	}
	/**
	 * Render input image tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $value
	 * @param  string $file
	 * @param  array  $attributes
	 * @return string
	 */
	public static function imageField($name,  $value = null, $file, $attributes = array()) {
		$attributes['src'] = $file;
		return self::field('image', $name, $value, $attributes);
	}
	/**
	 * Render input password tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $value
	 * @param  array  $attributes
	 * @return string
	 */
	public static function passwordField($name,  $value = null, $attributes = array()) {
		return self::field('password', $name, $value, $attributes);
	}
	/**
	 * Render input radio tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $value
	 * @param  array  $attributes
	 * @return string
	 */
	public static function radioField($name,  $value = null, $attributes = array()) {
		return self::field('radio', $name, $value, $attributes);
	}
	/**
	 * Render input text tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $value
	 * @param  array  $attributes
	 * @return string
	 */
	public static function textField($name,  $value = null, $attributes = array()) {
		return self::field('text', $name, $value, $attributes);
	}
	/**
	 * Render input email tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $value
	 * @param  array  $attributes
	 * @return string
	 */
	public static function emailField($name,  $value = null, $attributes = array()) {
		return self::field('email', $name, $value, $attributes);
	}
	/**
	 * Render input range tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $value
	 * @param  array  $attributes
	 * @return string
	 */
	public static function rangeField($name,  $value = null, $attributes = array()) {
		return self::field('range', $name, $value, $attributes);
	}
	/**
	 * Render input search tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $value
	 * @param  array  $attributes
	 * @return string
	 */
	public static function searchField($name,  $value = null, $attributes = array()) {
		return self::field('search', $name, $value, $attributes);
	}
	/**
	 * Render input tel tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $value
	 * @param  array  $attributes
	 * @return string
	 */
	public static function telField($name,  $value = null, $attributes = array()) {
		return self::field('tel', $name, $value, $attributes);
	}
	/**
	 * Render input url tag
	 *
	 * @access public
	 * @param  string $name
	 * @param  string $value
	 * @param  array  $attributes
	 * @return string
	 */
	public static function urlField($name,  $value = null, $attributes = array()) {
		return self::field('url', $name, $value, $attributes);
	}
	// HTML5 Only
	/**
	 * Render charset meta tag
	 *
	 * @access public
	 * @param  string $name
	 * @return string
	 */
	public static function charset($name) {
		return self::tag('meta', array('charset'=>$name));
	}
	/**
	 * Generate video tag
	 *
	 * @access public
	 * @param array $sources format type=>src
	 * @param array $tracks format array(kind, src, srclang, label)
	 * @param array $attributes
	 * @param string $noCodec text
	 * @return string
	 */
	public static function video($sources = array(), $tracks = array(), $attributes = array(), $noCodec = '') {
		$srcs = '';
		foreach ($sources AS $name => $value) {
			$srcs .= self::tag('source', array('type'=>$name, 'src'=>$value));
		}
		foreach ($tracks AS $track) {
			$srcs .= self::tag('track', array(
				'kind'=>$track['kind'],
				'src'=>$track['src'],
				'srclang'=>$track['srclang'],
				'label'=>$track['label']
			));
		}
		return self::openTag('video', $attributes) . $srcs . $noCodec . self::closeTag('video');
	}
	/**
	 * Generate audio tag
	 *
	 * @access public
	 * @param array $sources format type=>src
	 * @param array $tracks format array(kind, src, srclang, label)
	 * @param array $attributes
	 * @param string $noCodec text
	 * @return string
	 */
	public static function audio($sources = array(), $tracks = array(), $attributes = array(), $noCodec = '') {
		$srcs = '';
		foreach ($sources AS $name => $value) {
			$srcs .= self::tag('audio', array('type'=>$name, 'src'=>$value));
		}
		foreach ($tracks AS $track) {
			$srcs .= self::tag('track', array(
				'kind'=>$track['kind'],
				'src'=>$track['src'],
				'srclang'=>$track['srclang'],
				'label'=>$track['label']
			));
		}
		return self::openTag('audio', $attributes) . $srcs . $noCodec . self::closeTag('audio');
	}
	/**
	 * Generate canvas tag
	 *
	 * @access public
	 * @param array  $attributes
	 * @param string $noCodec text
	 * @return string
	 */
	public static function canvas($attributes = array(), $noCodec = '') {
		return self::openTag('canvas', $attributes) . $noCodec . self::closeTag('canvas');
	}
}