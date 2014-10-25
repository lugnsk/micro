<?php /** MicroCurl */

namespace Micro\wrappers;

use Micro\base\Exception;

/**
 * Class cURL
 *
 * @author Hassan Amouhzi <http://anezi.net>
 * @link https://github.com/php-mod/curl
 * @copyright Copyright &copy; 2013 php-mod
 * @license https://github.com/php-mod/curl/blob/master/LICENSE
 * @package micro
 * @subpackage wrappers
 * @version 1.1.5
 * @since 1.0
 */
class Curl
{

    // The HTTP authentication method(s) to use.

    const AUTH_BASIC = CURLAUTH_BASIC;
    const AUTH_DIGEST = CURLAUTH_DIGEST;
    const AUTH_GSSNEGOTIATE = CURLAUTH_GSSNEGOTIATE;
    const AUTH_NTLM = CURLAUTH_NTLM;
    const AUTH_ANY = CURLAUTH_ANY;
    const AUTH_ANYSAFE = CURLAUTH_ANYSAFE;

    const USER_AGENT = 'Links (2.6; Linux 3.13.10-200.fc20.x86_64 x86_64; GNU C 4.8.1; text)';

    private $_cookies = array();
    private $_headers = array();

    public $curl;

    public $error = false;
    public $error_code = 0;
    public $error_message = null;

    public $curl_error = false;
    public $curl_error_code = 0;
    public $curl_error_message = null;

    public $http_error = false;
    public $http_status_code = 0;
    public $http_error_message = null;

    public $request_headers = null;
    public $response_headers = null;
    public $response = null;

    public function __construct()
    {

        if (!extension_loaded('curl')) {
            throw new Exception('cURL library is not loaded');
        }

        $this->curl = curl_init();
        $this->setUserAgent(self::USER_AGENT);
        $this->setopt(CURLINFO_HEADER_OUT, true);
        $this->setopt(CURLOPT_HEADER, true);
        $this->setopt(CURLOPT_RETURNTRANSFER, true);
    }

    public function get($url, $data = array())
    {
        if (count($data) > 0) {
            $this->setopt(CURLOPT_URL, $url . '?' . http_build_query($data));
        } else {
            $this->setopt(CURLOPT_URL, $url);
        }
        $this->setopt(CURLOPT_HTTPGET, true);
        $this->_exec();
    }

    public function post($url, $data = array())
    {
        $this->setopt(CURLOPT_URL, $url);
        $this->setopt(CURLOPT_POST, true);
        $data = http_build_query($data);
        $this->setopt(CURLOPT_POSTFIELDS, $data);
        $this->_exec();
    }

    public function put($url, $data = array())
    {
        $this->setopt(CURLOPT_URL, $url . '?' . http_build_query($data));
        $this->setopt(CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->_exec();
    }

    public function patch($url, $data = array())
    {
        $this->setopt(CURLOPT_URL, $url);
        $this->setopt(CURLOPT_CUSTOMREQUEST, 'PATCH');
        $this->setopt(CURLOPT_POSTFIELDS, $data);
        $this->_exec();
    }

    public function delete($url, $data = array())
    {
        $this->setopt(CURLOPT_URL, $url . '?' . http_build_query($data));
        $this->setopt(CURLOPT_CUSTOMREQUEST, 'DELETE');
        $this->_exec();
    }

    public function setBasicAuthentication($username, $password)
    {
        $this->setHttpAuth(self::AUTH_BASIC);
        $this->setopt(CURLOPT_USERPWD, $username . ':' . $password);
    }

    protected function setHttpAuth($httpauth)
    {
        $this->setOpt(CURLOPT_HTTPAUTH, $httpauth);
    }

    public function setHeader($key, $value)
    {
        $this->_headers[$key] = $key . ': ' . $value;
        $this->setopt(CURLOPT_HTTPHEADER, array_values($this->_headers));
    }

    public function setUserAgent($user_agent)
    {
        $this->setopt(CURLOPT_USERAGENT, $user_agent);
    }

    public function setReferrer($referrer)
    {
        $this->setopt(CURLOPT_REFERER, $referrer);
    }

    public function setCookie($key, $value)
    {
        $this->_cookies[$key] = $value;
        $this->setopt(CURLOPT_COOKIE, http_build_query($this->_cookies, '', '; '));
    }

    public function setOpt($option, $value)
    {
        return curl_setopt($this->curl, $option, $value);
    }

    public function verbose($on = true)
    {
        $this->setopt(CURLOPT_VERBOSE, $on);
    }

    public function close()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

    public function _exec()
    {
        $this->response = curl_exec($this->curl);
        $this->curl_error_code = curl_errno($this->curl);
        $this->curl_error_message = curl_error($this->curl);
        $this->curl_error = !($this->curl_error_code === 0);
        $this->http_status_code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $this->http_error = in_array(floor($this->http_status_code / 100), array(4, 5));
        $this->error = $this->curl_error || $this->http_error;
        $this->error_code = $this->error ? ($this->curl_error ? $this->curl_error_code : $this->http_status_code) : 0;

        $this->request_headers = preg_split('/\r\n/', curl_getinfo($this->curl, CURLINFO_HEADER_OUT), null,
            PREG_SPLIT_NO_EMPTY);
        $this->response_headers = '';
        if (!(strpos($this->response, "\r\n\r\n") === false)) {
            list($response_header, $this->response) = explode("\r\n\r\n", $this->response, 2);
            if ($response_header === 'HTTP/1.1 100 Continue') {
                list($response_header, $this->response) = explode("\r\n\r\n", $this->response, 2);
            }
            $this->response_headers = preg_split('/\r\n/', $response_header, null, PREG_SPLIT_NO_EMPTY);
        }

        $this->http_error_message = $this->error ? (isset($this->response_headers['0']) ? $this->response_headers['0'] : '') : '';
        $this->error_message = $this->curl_error ? $this->curl_error_message : $this->http_error_message;

        return $this->error_code;
    }

    public function __destruct()
    {
        $this->close();
    }
}