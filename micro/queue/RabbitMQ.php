<?php /** MicroRabbitMQ */

namespace Micro\queue;

/**
 * RabbitMQ class file.
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
class RabbitMQ
{
    /** @var \AMQPConnection $connect Connect to broker */
    protected $connect;
    /** @var \AMQPChannel $channel Channel of connection */
    protected $channel;


    /**
     * Constructor RabbitMQ
     *
     * @access public
     *
     * @param array $params connect to broker
     *
     * @result void
     */
    public function __construct(array $params = [])
    {
        $this->connect = new \AMQPConnection($params);
        $this->connect->connect();

        $this->channel = new \AMQPChannel($this->connect);
    }

    /**
     * Close RabbitMQ
     *
     * @access public
     * @return void
     */
    public function __destruct()
    {
        $this->connect->disconnect();
    }

    /**
     * Send message
     *
     * @access public
     *
     * @param string $message message text
     * @param string $route name route
     * @param string $chat name chat room
     *
     * @return void
     */
    public function send($message, $route, $chat)
    {
        $exchange = new \AMQPExchange($this->channel);
        $exchange->setName($chat);
        $exchange->publish($message, $route);
    }

    /**
     * Read current message
     *
     * @access public
     *
     * @param string $chat name chat room
     * @param string $route name route
     * @param string $nameReader name queue
     *
     * @return \AMQPEnvelope|bool
     */
    public function read($chat, $route, $nameReader = 'random')
    {
        $queue = new \AMQPQueue($this->channel);
        $queue->setName($nameReader);
        $queue->declare();
        $queue->bind($chat, $route);

        $envelop = $queue->get();
        if ($envelop) {
            $queue->ack($envelop->getDeliveryTag());

            return $envelop;
        }

        return false;
    }

    /**
     * Read all messages
     *
     * @access public
     *
     * @param string $chat name chat room
     * @param string $route name route
     * @param string $nameReader name queue
     *
     * @return array
     */
    public function readAll($chat, $route, $nameReader)
    {
        $queue = new \AMQPQueue($this->channel);
        $queue->setName($nameReader);
        $queue->declare();
        $queue->bind($chat, $route);

        $result = [];
        while ($envelop = $queue->get()) {
            $queue->ack($envelop->getDeliveryTag());
            $result[] = $envelop;
        }

        return $result;
    }
}