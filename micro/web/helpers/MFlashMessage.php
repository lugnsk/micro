<?php

class MFlashMessage
{
	const TYPE_SUCCESS = 1;
	const TYPE_NOTICE = 2;
	const TYPE_ERROR = 3;

	public function __construct() {
		try {
			MRegistry::get('session')->flash = array();
		} catch (MException $e) {
			die('Механизм сессий не активирован: ' . $e->getMessage());
		}
	}
	// push a new flash
	public function push($type = MFlashMessage::TYPE_SUCCESS, $title = '', $description = '') {
		MRegistry::get('session')->flash[] = array(
			'type'=> $type,
			'title'=> $title,
			'description'=> $description
		);
	}
	// has flashes by type
	public function has($type = MFlashMessage::TYPE_SUCCESS) {
		foreach (MRegistry::get('session')->flash AS $element) {
			if (isset($element['type']) && $element['type'] == $type) {
				return true;
			}
		}
		return false;
	}
	// get flash by type
	public function get($type = MFlashMessage::TYPE_SUCCESS) {
		$result = array();
		foreach (MRegistry::get('session')->flash AS $key=>$element) {
			if (isset($element['type']) && $element['type'] == $type) {
				$result = $element;
				unset(MRegistry::get('session')->flash[$key]);
				return $result;
			}
		}
		return false;
	}
	// get all flashes
	public function getAll() {
		$result = MRegistry::get('session')->flash;
		MRegistry::get('session')->flash = array();
		return $result;
	}
}