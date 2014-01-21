<?php

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
	 * @param  string name
	 * @param  array attributes
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
	 * @param  string name
	 * @param  array attributes
	 * @return string
	 */
	public static function openTag($name, $attributes = array()) {
		$result = '';
		foreach ($attributes AS $name => $value) {
			$result .= $name . '="' . $value . '" ';
		}
		return '<'.$name.' '.$result.'>';
	}
	/**
	 * Render close tag
	 *
	 * @access public
	 * @param  string name
	 * @return string
	 */
	public static function closeTag($name) {
		return '</'.$name.'>';
	}
	/**
	 * Base field tag
	 *
	 * @access public
	 * @param  string type
	 * @param  string name
	 * @param  string value
	 * @param  array  attributes
	 * @return string
	 */
	private static function field($type, $name, $value, $attributes=array()) {
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
	 * @param  string name
	 * @param  string url
	 * @param  array attributes
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
	 * @param  string name
	 * @param  string content
	 * @param  array attributes
	 * @return string
	 */
	public static function meta($name, $content, $attributes = array()) {
		$attributes = array('name'=>$name, 'content'=>$content);
		return self::tag('meta', $attributes);
	}
	/**
	 * Render css file
	 *
	 * @access public
	 * @param  string filename
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
	 * @param  string filename
	 * @return string
	 */
	public static function scriptFile($file) {
		$attributes = array('src'=>$file, 'type'=>'text/javascript');
		return self::openTag('script', attributes) . self::closeTag('script');
	}
	/**
	 * Render script source
	 *
	 * @access public
	 * @param  string text script
	 * @param  array attributes
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
	 * @param  string text style
	 * @param  array attributes
	 * @return string
	 */
	public static function css($text, $attributes = array()) {
		$attributes['type'] = 'text/css';
		return self::openTag('style', $attributes) . $text . self::closeTag('style');
	}
	/**
	 * Render doctype tag
	 *
	 * @access public
	 * @param  string name
	 * @return string|boolean
	 */
	public static function doctype($name) {
		$doctypes = array(
			'xhtml11'		=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">',
			'xhtml1-trans'	=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
			'xhtml1-strict'	=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
			'xhtml1-frame'	=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
			'html4-trans'	=> '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
			'html4-strict'	=> '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">',
			'html4-frame'	=> '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">',
			'html5'			=> '<!DOCTYPE html>',
		);

		if (!isset($doctypes[$name])) {
			return false;
		}
		return $doctypes[$name];
	}
	/**
	 * Render title tag
	 *
	 * @access public
	 * @param string name
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
	 * @param integer number
	 * @param array attributes
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
	 * @param  string name
	 * @param  string file
	 * @param  array attributes
	 * @return string
	 */
	public static function image($name, $file, $attributes= array()) {
		$attributes['src'] = $source;
		$attributes['alt'] = $name;
		return self::tag('img', $attributes);
	}
	/**
	 * Render Mail a tag
	 *
	 * @access public
	 * @param  string name
	 * @param  string email
	 * @param  array attributes
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
	 * @param  string number
	 * @param  string value
	 * @param  array attributes
	 * @return string
	 */
	public static function heading($num, $value, $attributes = array()) {
		return self::openTag('h'.$num, $attributes) . $value . self::closeTag('h'.$num);
	}

	// LIST Elements // marklist, numlist, deplist, applet, imgmap

	// TABLE Elements// table, caption, row (col)

	// FORM Elements// fieldset option dropdownlist listbox|multiplydropdownlist checkboxlist radiobuttonlist label
	/**
	 * Render begin form tag
	 *
	 * @access public
	 * @param  string action
	 * @param  string method
	 * @param  array attributes
	 * @return string
	 */
	public static function beginForm($action, $method, $attributes = array()) {
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
	 * @param  string text
	 * @param  array attributes
	 * @return string
	 */
	public static function button($text, $attributes = array()) {
		return self::openTag('button',$attributes) . $text . self::closeTag('button');
	}
	/**
	 * Render image button tag
	 *
	 * @access public
	 * @param  string name
	 * @param  string file
	 * @param  array attributesButton
	 * @param  array attributesImage
	 * @return string
	 */
	public static function imageButton($name, $file, $attributesButton=array(), $attributesImage = array()) {
		return self::button(self::image($name, $file, $attributesImage), $attributesButton);
	}
	/**
	 * Render textarea tag
	 *
	 * @access public
	 * @param  string name
	 * @param  string text
	 * @param  array attributes
	 * @return string
	 */
	public static function textArea($name, $text, $attributes = array()) {
		return self::openTag('textarea', $attributes) . $text . self::closeTag('textarea');
	}
	/**
	 * Render legend tag
	 *
	 * @access public
	 * @param  string text
	 * @param  array attributes
	 * @return string
	 */
	public static function legend($text, $attributes = array()) {
		return self::openTag('legend', $attributes) . $text . self::closeTag('legend');
	}
	// INPUT Elements
	/**
	 * Render reset button tag
	 *
	 * @access public
	 * @param  string label
	 * @param  array attributes
	 * @return string
	 */
	public static function resetButton($label='Reset', $attributes=array()) {
		$attributes['type'] = 'reset';
		$attributes['value'] = $label;
		return self::tag('input', $attributes);
	}
	/**
	 * Render submit button tag
	 *
	 * @access public
	 * @param  string label
	 * @param  array attributes
	 * @return string
	 */
	public static function submitButton($label='Submit', $attributes=array()) {
		$attributes['type'] = 'submit';
		$attributes['value'] = $label;
		return self::tag('input', $attributes);
	}
	/**
	 * Render input button tag
	 *
	 * @access public
	 * @param  string name
	 * @param  string value
	 * @param  array attributes
	 * @return string
	 */
	public static function buttonField($name, $value, $attributes = array()) {
		return self::field('button', $name, $value, $attributes);
	}
	/**
	 * Render input checkbox tag
	 *
	 * @access public
	 * @param  string name
	 * @param  string value
	 * @param  array attributes
	 * @return string
	 */
	public static function checkboxField($name, $value, $attributes = array()) {
		return self::field('checkbox', $name, $value, $attributes);
	}
	/**
	 * Render input file tag
	 *
	 * @access public
	 * @param  string name
	 * @param  string value
	 * @param  array attributes
	 * @return string
	 */
	public static function fileField($name, $value, $attributes = array()) {
		return self::field('file', $name, $value, $attributes);
	}
	/**
	 * Render input hidden tag
	 *
	 * @access public
	 * @param  string name
	 * @param  string value
	 * @param  array attributes
	 * @return string
	 */
	public static function hiddenField($name, $value, $attributes = array()) {
		return self::field('hidden', $name, $value, $attributes);
	}
	/**
	 * Render input image tag
	 *
	 * @access public
	 * @param  string name
	 * @param  string value
	 * @param  string file
	 * @param  array attributes
	 * @return string
	 */
	public static function imageField($name, $value, $file, $attributes = array()) {
		$attributes['src'] = $file;
		return self::field('image', $name, $value, $attributes);
	}
	/**
	 * Render input password tag
	 *
	 * @access public
	 * @param  string name
	 * @param  string value
	 * @param  array attributes
	 * @return string
	 */
	public static function passwordField($name, $value, $attributes = array()) {
		return self::field('password', $name, $value, $attributes);
	}
	/**
	 * Render input radio tag
	 *
	 * @access public
	 * @param  string name
	 * @param  string value
	 * @param  array attributes
	 * @return string
	 */
	public static function radioField($name, $value, $attributes = array()) {
		return self::field('radio', $name, $value, $attributes);
	}
	/**
	 * Render input text tag
	 *
	 * @access public
	 * @param  string name
	 * @param  string value
	 * @param  array attributes
	 * @return string
	 */
	public static function textField($name, $value, $attributes = array()) {
		return self::field('text', $name, $value, $attributes);
	}
	/**
	 * Render input email tag
	 *
	 * @access public
	 * @param  string name
	 * @param  string value
	 * @param  array attributes
	 * @return string
	 */
	public static function emailField($name, $value, $attributes = array()) {
		return self::field('email', $name, $value, $attributes);
	}
	/**
	 * Render input range tag
	 *
	 * @access public
	 * @param  string name
	 * @param  string value
	 * @param  array attributes
	 * @return string
	 */
	public static function rangeField($name, $value, $attributes = array()) {
		return self::field('range', $name, $value, $attributes);
	}
	/**
	 * Render input search tag
	 *
	 * @access public
	 * @param  string name
	 * @param  string value
	 * @param  array attributes
	 * @return string
	 */
	public static function searchField($name, $value, $attributes = array()) {
		return self::field('search', $name, $value, $attributes);
	}
	/**
	 * Render input tel tag
	 *
	 * @access public
	 * @param  string name
	 * @param  string value
	 * @param  array attributes
	 * @return string
	 */
	public static function telField($name, $value, $attributes = array()) {
		return self::field('tel', $name, $value, $attributes);
	}
	/**
	 * Render input url tag
	 *
	 * @access public
	 * @param  string name
	 * @param  string value
	 * @param  array attributes
	 * @return string
	 */
	public static function urlField($name, $value, $attributes = array()) {
		return self::field('url', $name, $value, $attributes);
	}
	// HTML5 Only // video, audio, canvas
	/**
	 * Render charset meta tag
	 *
	 * @access public
	 * @param  string name
	 * @return string
	 */
	public static function charset($name) {
		return self::tag('meta', array('charset'=>$name));
	}
}