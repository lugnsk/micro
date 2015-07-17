<?php /** MicroCurl */

namespace Micro\web;

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
    /** @var integer AUTH_BASIC authentication basic */
    const AUTH_BASIC = CURLAUTH_BASIC;
    /** @var integer AUTH_DIGEST authentication digest */
    const AUTH_DIGEST = CURLAUTH_DIGEST;
    /** @var integer AUTH_GSSNEGOTIATE authentication gss negotiate */
    const AUTH_GSSNEGOTIATE = CURLAUTH_GSSNEGOTIATE;
    /** @var integer AUTH_NTLM authentication NT LM */
    const AUTH_NTLM = CURLAUTH_NTLM;
    /** @var integer AUTH_ANY authentication any */
    const AUTH_ANY = CURLAUTH_ANY;
    /** @var integer AUTH_ANYSAFE authentication any safe */
    const AUTH_ANYSAFE = CURLAUTH_ANYSAFE;

    /** @var string USER_AGENT user agent identity */
    const USER_AGENT = 'Links (2.6; Linux 3.13.10-200.fc20.x86_64 x86_64; GNU C 4.8.1; text)';
    /** @var resource $curl cURL resource */
    public $curl;
    /** @var bool $error is error */
    public $error = false;
    /** @var int $error_code error code */
    public $error_code = 0;
    /** @var null|string $error_message error message */
    public $error_message;
    /** @var bool $curl_error is cURL error */
    public $curl_error = false;
    /** @var int $curl_error_code cURL error code */
    public $curl_error_code = 0;
    /** @var null|string $curl_error_message cURL error message */
    public $curl_error_message;
    /** @var bool $http_error is HTTP error */
    public $http_error = false;
    /** @var int $http_status_code HTTP status code */
    public $http_status_code = 0;
    /** @var null $http_error_message HTTP error message */
    public $http_error_message;
    /** @var null $request_headers request headers */
    public $request_headers;
    /** @var null $response_headers response headers */
    public $response_headers;
    /** @var null $response response */
    public $response;
    /** @var array $_cookies cookies for request */
    private $_cookies = [];
    /** @var array $_headers headers for request */
    private $_headers = [];

    /**
     * Construct
     *
     * @access public
     * @result void
     * @throws Exception
     */
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

    /**
     * Set user agent
     *
     * @access public
     *
     * @param string $user_agent user agent name
     *
     * @return void
     */
    public function setUserAgent($user_agent)
    {
        $this->setopt(CURLOPT_USERAGENT, $user_agent);
    }

    /**
     * Set option
     *
     * @access public
     *
     * @param mixed $option
     * @param mixed $value
     *
     * @return bool
     */
    public function setopt($option, $value)
    {
        return curl_setopt($this->curl, $option, $value);
    }

    /**
     * Get URL
     *
     * @access public
     *
     * @param string $url URL
     * @param array $data data
     *
     * @return void
     */
    public function get($url, array $data = [])
    {
        if (count($data) > 0) {
            $this->setopt(CURLOPT_URL, $url . '?' . http_build_query($data));
        } else {
            $this->setopt(CURLOPT_URL, $url);
        }
        $this->setopt(CURLOPT_HTTPGET, true);
        $this->_exec();
    }

    /**
     * Execute
     *
     * @access public
     * @return int|mixed
     */
    public function _exec()
    {
        $this->response = curl_exec($this->curl);
        $this->curl_error_code = curl_errno($this->curl);
        $this->curl_error_message = curl_error($this->curl);
        $this->curl_error = !($this->curl_error_code === 0);
        $this->http_status_code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $this->http_error = in_array(floor($this->http_status_code / 100), array(4, 5), true);
        $this->error = $this->curl_error || $this->http_error;
        if ($this->error) {
            $this->error_code = $this->curl_error ? $this->curl_error_code : $this->http_status_code;
        }

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

        $this->http_error_message = '';
        if ($this->error) {
            $this->http_error_message = !empty($this->response_headers[0]) ? $this->response_headers[0] : '';
        }

        $this->error_message = $this->curl_error ? $this->curl_error_message : $this->http_error_message;

        return $this->error_code;
    }

    /**
     * Post URL
     *
     * @access public
     *
     * @param string $url URL
     * @param array|mixed $data data
     *
     * @return void
     */
    public function post($url, array $data = [])
    {
        $this->setopt(CURLOPT_URL, $url);
        $this->setopt(CURLOPT_POST, true);
        $data = http_build_query($data);
        $this->setopt(CURLOPT_POSTFIELDS, $data);
        $this->_exec();
    }

    /**
     * Put URL
     *
     * @access public
     *
     * @param string $url URL
     * @param array $data data
     *
     * @return void
     */
    public function put($url, array $data = [])
    {
        $this->setopt(CURLOPT_URL, $url . '?' . http_build_query($data));
        $this->setopt(CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->_exec();
    }

    /**
     * Patch URL
     *
     * @access public
     *
     * @param string $url URL
     * @param array $data data
     *
     * @return void
     */
    public function patch($url, array $data = [])
    {
        $this->setopt(CURLOPT_URL, $url);
        $this->setopt(CURLOPT_CUSTOMREQUEST, 'PATCH');
        $this->setopt(CURLOPT_POSTFIELDS, $data);
        $this->_exec();
    }

    /**
     * Delete URL
     *
     * @access public
     *
     * @param string $url URL
     * @param array $data data
     *
     * @return void
     */
    public function delete($url, array $data = [])
    {
        $this->setopt(CURLOPT_URL, $url . '?' . http_build_query($data));
        $this->setopt(CURLOPT_CUSTOMREQUEST, 'DELETE');
        $this->_exec();
    }

    /**
     * Set basic authentication
     *
     * @access public
     *
     * @param string $username username
     * @param string $password password
     *
     * @return void
     */
    public function setBasicAuthentication($username, $password)
    {
        $this->setHttpAuth(self::AUTH_BASIC);
        $this->setopt(CURLOPT_USERPWD, $username . ':' . $password);
    }

    /**
     * Set HTTP auth
     *
     * @access public
     *
     * @param mixed $httpauth http auth type
     *
     * @return void
     */
    protected function setHttpAuth($httpauth)
    {
        $this->setopt(CURLOPT_HTTPAUTH, $httpauth);
    }

    /**
     * Set header
     *
     * @access public
     *
     * @param string $key key
     * @param string $value value
     *
     * @return void
     */
    public function setHeader($key, $value)
    {
        $this->_headers[$key] = $key . ': ' . $value;
        $this->setopt(CURLOPT_HTTPHEADER, array_values($this->_headers));
    }

    /**
     * Set referrer
     *
     * @access public
     *
     * @param string $referrer URL referrer
     *
     * @return void
     */
    public function setReferrer($referrer)
    {
        $this->setopt(CURLOPT_REFERER, $referrer);
    }

    /**
     * Set cookie
     *
     * @access public
     *
     * @param string $key key
     * @param string $value value
     *
     * @return void
     */
    public function setCookie($key, $value)
    {
        $this->_cookies[$key] = $value;
        $this->setopt(CURLOPT_COOKIE, http_build_query($this->_cookies, '', '; '));
    }

    /**
     * Verbose
     *
     * @access public
     *
     * @param bool $on on
     *
     * @return void
     */
    public function verbose($on = true)
    {
        $this->setopt(CURLOPT_VERBOSE, $on);
    }

    /**
     * Destructor
     *
     * @access public
     * @result void
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Close
     *
     * @access public
     * @return void
     */
    public function close()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }
}