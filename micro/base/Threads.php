<?php /** MicroThreads */

namespace Micro\base;

/**
 * Threads class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage wrappers
 * @version 1.0
 * @since 1.0
 */
abstract class Threads
{
    /** @var string $name thread name */
    private $name;
    /** @var integer $pid process ID */
    private $pid;
    /** @var integer $puid process UID */
    private $puid;
    /** @var integer $guid process GUID */
    private $guid;
    /** @var bool $isChild is child */
    private $isChild = false;
    /** @var array $internalIPCArray Internal IPC array */
    private $internalIPCArray = [];
    /** @var integer $internalIPCKey Internal IPC key */
    private $internalIPCKey;
    /** @var integer $internalSemaphoreKey Internal semaphore key */
    private $internalSemaphoreKey;
    /** @var bool $isIPC is IPC */
    private $isIPC = false;
    /** @var bool $running is running */
    private $running = false;
    /** @var string $fileIPC1 file IPC1 */
    private $fileIPC1;
    /** @var string $fileIPC2 file IPC2 */
    private $fileIPC2;


    /**
     * Constructor thread
     *
     * @access public
     *
     * @param     $name
     * @param int $puid
     * @param int $guid
     * @param int $umask
     *
     * @result void
     * @throws Exception
     */
    public function __construct($name, $puid = 0, $guid = 0, $umask = -1)
    {
        if (empty($_SERVER['argc'])) {
            throw new Exception('Threads are permitted only for CLI');
        }
        $this->name = $name;
        $this->guid = $guid;
        $this->puid = $puid;

        if ($umask !== -1) {
            umask($umask);
        }

        $this->isChild = false;
        $this->internalIPCArray = [];
        $this->isIPC = false;

        if ($this->createIPCSegment() && $this->createIPCSemaphore()) {
            $this->isIPC = true;
        }
    }

    /**
     * Create IPC segment
     *
     * @access protected
     * @return bool
     * @throws Exception
     */
    protected function createIPCSegment()
    {
        $this->fileIPC1 = '/tmp/' . mt_rand() . md5($this->getName()) . '.shm';

        touch($this->fileIPC1);

        $shm_key = ftok($this->fileIPC1, 't');
        if ($shm_key === -1) {
            throw new Exception('Fatal exception creating SHM segment (ftok)');
        }

        $this->internalIPCKey = @shmop_open($shm_key, 'c', 0644, 10240);
        if (!$this->internalIPCKey) {
            return false;
        }

        return true;
    }

    /**
     * get thread name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set thread name
     *
     * @access public
     *
     * @param string $name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Create IPC semaphore
     *
     * @access protected
     * @return bool
     * @throws Exception
     */
    protected function createIPCSemaphore()
    {
        $this->fileIPC2 = '/tmp/' . mt_rand() . md5($this->getName()) . '.sem';

        touch($this->fileIPC2);

        $sem_key = ftok($this->fileIPC2, 't');
        if ($sem_key === -1) {
            throw new Exception('Fatal exception creating semaphore (ftok)');
        }

        $this->internalSemaphoreKey = shmop_open($sem_key, 'c', 0644, 10);
        if (!$this->internalSemaphoreKey) {
            return false;
        }

        return true;
    }

    /**
     * Is running thread
     *
     * @access public
     * @return bool
     */
    public function isRunning()
    {
        return (bool)$this->running;
    }

    /**
     * Set alive
     *
     * @access public
     * @return void
     */
    public function setAlive()
    {
        $this->setVariable('_pingTime', time());
    }

    /**
     * Set variable in shared memory
     *
     * @access public
     *
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function setVariable($name, $value)
    {
        $this->internalIPCArray[$name] = $value;
        $this->writeToIPCSegment();
    }

    /**
     * Write to IPC segment
     *
     * @access protected
     * @return void
     * @throws Exception
     */
    protected function writeToIPCSegment()
    {
        if (shmop_read($this->internalSemaphoreKey, 1, 1) === 1) {
            return;
        }

        $serialized_IPC_array = serialize($this->internalIPCArray);
        $shm_bytes_written = shmop_write($this->internalIPCKey, $serialized_IPC_array, 0);

        if ($shm_bytes_written !== strlen($serialized_IPC_array)) {
            throw new Exception(
                'Fatal exception writing SHM segment (shmop_write)' . strlen($serialized_IPC_array) .
                '-' . shmop_size($this->internalIPCKey)
            );
        }
    }

    /**
     * Get last alive
     *
     * @access public
     * @return int
     */
    public function getLastAlive()
    {
        $timestamp = (int)$this->getVariable('_pingTime');
        if ($timestamp === 0) {
            return 0;
        } else {
            return (time() - $timestamp);
        }
    }

    /**
     * Get variable from shared memory
     *
     * @access public
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getVariable($name)
    {
        $this->readFromIPCSegment();

        return $this->internalIPCArray[$name];
    }

    /**
     * Read from IPC segment
     *
     * @access public
     * @return void
     * @throws Exception
     */
    protected function readFromIPCSegment()
    {
        $serialized_IPC_array = shmop_read($this->internalIPCKey, 0, shmop_size($this->internalIPCKey));

        if (!$serialized_IPC_array) {
            throw new Exception('Fatal exception reading SHM segment (shmop_read)' . "\n");
        }

        unset($this->internalIPCArray);

        $this->internalIPCArray = @unserialize($serialized_IPC_array);
    }

    /**
     * Get thread process ID
     *
     * @access public
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Register callback func into shared memory
     *
     * @access public
     *
     * @param mixed $argList
     * @param string $methodName
     *
     * @return mixed|void
     */
    public function register_callback_func($argList, $methodName)
    {
        if (is_array($argList) && count($argList) > 1) {
            if ($argList[1] === -2) {
                $this->internalIPCArray['_call_type'] = -2;
            } else {
                $this->internalIPCArray['_call_type'] = -1;
            }
        } else {
            $this->internalIPCArray['_call_type'] = -1;
        }

        $this->internalIPCArray['_call_method'] = $methodName;
        $this->internalIPCArray['_call_input'] = $argList;

        $this->writeToIPCSegment();

        switch ($this->internalIPCArray['_call_type']) {
            case -1: {
                $this->sendSigUsr1();
                break;
            }
            case -2: {
                shmop_write($this->internalSemaphoreKey, 1, 0);

                $this->sendSigUsr1();
                $this->waitIPCSemaphore();
                $this->readFromIPCSegment();

                shmop_write($this->internalSemaphoreKey, 0, 1);

                return $this->internalIPCArray['_call_output'];
                break;
            }
        }

        return false;
    }

    /**
     * Send signal USR1
     *
     * @access protected
     * @return void
     */
    protected function sendSigUsr1()
    {
        if ($this->pid > 0) {
            posix_kill($this->pid, SIGUSR1);
        }
    }

    /**
     * Wait IPC semaphore
     *
     * @access protected
     * @return void
     */
    protected function waitIPCSemaphore()
    {
        while (true) {
            $ok = shmop_read($this->internalSemaphoreKey, 0, 1);

            if ($ok === 0) {
                break;
            } else {
                usleep(10);
            }
        }
    }

    /**
     * Start
     *
     * @access public
     * @return void
     * @throws Exception
     */
    public function start()
    {
        if (!$this->isIPC) {
            throw new Exception('Fatal error, unable to create SHM segments for process communications');
        }

        pcntl_signal(SIGCHLD, SIG_IGN);

        $pid = pcntl_fork();
        if ($pid === 0) {
            $this->isChild = true;
            sleep(1);

            pcntl_signal(SIGUSR1, [$this, 'sigHandler']);

            if ($this->guid !== 0) {
                posix_setgid($this->guid);
            }
            if ($this->puid !== 0) {
                posix_setuid($this->puid);
            }
            $this->run();

            exit(0);
        } else {
            $this->isChild = false;
            $this->running = true;
            $this->pid = $pid;
        }
    }

    /**
     * Running thread
     *
     * @access public
     * @return void
     */
    abstract public function run();

    /**
     * Stop thread
     *
     * @access public
     * @return bool
     */
    public function stop()
    {
        $success = false;

        if ($this->pid > 0) {
            posix_kill($this->pid, 9);
            pcntl_waitpid($this->pid, $temp = 0, WNOHANG);

            $success = pcntl_wifexited($temp);

            $this->cleanThreadContext();
        }

        return $success;
    }

    /**
     * Clean thread context
     *
     * @access protected
     * @return void
     */
    protected function cleanThreadContext()
    {
        @shmop_delete($this->internalIPCKey);
        @shmop_delete($this->internalSemaphoreKey);

        @shmop_close($this->internalIPCKey);
        @shmop_close($this->internalSemaphoreKey);

        unlink($this->fileIPC1);
        unlink($this->fileIPC2);

        $this->running = false;
        unset($this->pid);
    }

    /**
     * Signal handler
     *
     * @access protected
     *
     * @param $sigNo
     *
     * @return void
     */
    protected function sigHandler($sigNo)
    {
        switch ($sigNo) {
            case SIGTERM: {
                exit;
                break;
            }
            case SIGHUP: {
                break;
            }
            case SIGUSR1: {
                $this->readFromIPCSegment();

                $method = $this->internalIPCArray['_call_method'];
                $params = $this->internalIPCArray['_call_input'];

                switch ($this->internalIPCArray['_call_type']) {
                    case -1: {
                        $this->$method($params);
                        break;
                    }
                    case -2: {
                        $this->internalIPCArray['_call_output'] = $this->$method($params);

                        $this->writeToIPCSegment();

                        shmop_write($this->internalSemaphoreKey, 0, 0);
                        shmop_write($this->internalSemaphoreKey, 1, 1);

                        break;
                    }
                }
                break;
            }
            default: {
                // handle all other signals
            }
        }
    }
}