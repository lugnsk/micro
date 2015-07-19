<?php

namespace Micro\web;

interface IUser
{
    /**
     * Check access by current user
     *
     * @access public
     * @global       Container
     *
     * @param string $permission permission to check
     * @param array $data arguments
     *
     * @return bool
     */
    public function check($permission, array $data = []);

    /**
     * Get state user
     *
     * @access public
     * @global Container
     * @return bool
     */
    public function isGuest();

    /**
     * Get user ID
     *
     * @access public
     * @global Container
     * @return bool|integer
     */
    public function getID();

    /**
     * Login user
     *
     * @access public
     *
     * @param int|string $userId User identify
     *
     * @return void
     */
    public function login($userId);

    /**
     * Set User ID
     *
     * @access public
     * @global      Container
     *
     * @param mixed $id user id
     *
     * @return void
     */
    public function setID($id);

    /**
     * Logout user
     *
     * @access public
     *
     * @return void
     */
    public function logout();

    /**
     * Get captcha code
     *
     * @access public
     * @global Container
     * @return string
     */
    public function getCaptcha();

    /**
     * Make captcha from source
     *
     * @access public
     *
     * @param string $code source captcha
     *
     * @return void
     */
    public function setCaptcha($code);
}