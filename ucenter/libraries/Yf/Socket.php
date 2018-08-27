<?php
/**
 * PHP Socket
 *
 * 简单的socket client
 *
 * @category   Framework
 * @package    Socket
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 *
 */
 
define("HEADER_SIZE", 4);

class Yf_Socket
{
    public $socket;
    public $debug = true;
    public $host;
    public $port;
    public $connected = false;
    public $newLine = "";  //  " \r\n"
    public $_buffer = ""; 
    public $_dataStream = "";

    public $_writeDataRow = array();  //写入数据
    
    function __construct($host, $port) 
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function write($data) 
    {
        $rs = false;

        $data_tmp = $data . $this->newLine;
        array_push($this->_writeDataRow, $data_tmp);

        foreach ($this->_writeDataRow as $key=>$data)
        {
            if (false === @socket_write($this->socket, $data, strlen($data)))
            {
                    $errno = socket_last_error();

                    if ($this->debug)
                    {
                        echo "errno: " .  $errno;
                        echo "\n";

                        echo "\nsocket_write() failed: reason: " . socket_strerror($errno) . "\n";
                    }

                    if (10053 == $errno || 32 == $errno)
                    {
                        $this->close();
                        $this->connect();
                    }

                $rs = false;
                break;
            }
            else
            {
                unset($this->_writeDataRow[$key]);

                $rs = true;
                if ($this->debug)
                {
                    echo "send OK.\n";
                }
            }
        }

        return $rs;
    }

    public function read() 
    {
        $rs = array();

        //注意长度限制
        $this->_buffer .= socket_read($this->socket, 1024, PHP_BINARY_READ);
        $this->parseData();

        return $rs;
    }

    public function recv()
    {
        $bytes = socket_recv($this->socket, $buffer, 2048, 0);

        if (0 == $bytes)
        {
            $this->reconnect();

            if ($this->connected)
            {
                $this->login();
            }
        }
        else
        {
            $this->_buffer .= $buffer;
        }
    }



    public function parseData()
    {
        $rs = array();

        if ($this->debug)
        {
            echo strlen($this->_buffer);
            echo "\n";
        }

        while (strlen($this->_buffer) >= HEADER_SIZE)
        {
            $cmd_id_row = unpack('s', substr($this->_buffer, 0, 2));

            $stream_length_row = unpack('S', substr($this->_buffer, 2, 2));
            $stream_length = $stream_length_row[1];
        
            if ($this->debug)
            {
                print_r($stream_length);
                print_r($cmd_id_row[1]);
            }

            $this->dataStream = substr($this->_buffer, HEADER_SIZE, $stream_length);
            //msgpack_unpack();


            if ($stream_length == strlen($this->dataStream))
            {
                //lineReceived($this->dataStream)

                if ($this->debug)
                {
                    $rs = msgpack_unpack($this->dataStream);
                    print_r($rs);
                }

                $this->_buffer = substr($this->_buffer, HEADER_SIZE+$stream_length);
            }
            elseif ($stream_length > strlen($this->dataStream))
            {
                return ;
            }
            else
            {
                return ;
            }


            //$this->_dataStream = substr($this->_buffer, HEADER_SIZE, strlen($this->_buffer));
            //print_r($this->_dataStream);

            //$this->_buffer = substr($this->_buffer, strlen($this->_buffer), strlen($this->_buffer));
        }

        return $rs;
    }

    public function close() 
    {
        if ($this->debug)
        {
            echo "close socket...";
        }

        socket_close($this->socket);
        $this->connected = false;
    }

    public function connect() 
    {
        if ($this->debug)
        {
            echo "Start to create socket...";
        }

        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if ($this->socket === false) 
        {
            if ($this->debug)
            {
                echo "\nsocket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
            }
        }
        else
        {
            socket_set_option($this->socket, SOL_SOCKET,SO_RCVTIMEO, array("sec"=>1, "usec"=>0 ));
            socket_set_option($this->socket, SOL_SOCKET,SO_SNDTIMEO, array("sec"=>2, "usec"=>0 ));

            if ($this->debug)
            {
                echo "OK.\n";
            }


            if ($this->debug)
            {
                echo "Attempting to connect to " . $this->host . " on port " . $this->port;
                echo "\n";
            }

            $result = socket_connect($this->socket, $this->host, $this->port);

            socket_set_block($this->socket);

            if ($result === false)
            {
                if ($this->debug)
                {
                    echo "\nsocket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error( $this->socket)) . "\n";
                }
            }
            else
            {
                $this->connected = true;

                if ($this->debug)
                {
                    echo "OK.\n";
                }
            }
        }

    }


    public function reconnect()
    {
        $this->connected = false;

        while(!$this->connected )
        {
            echo "reconnect.....\n";

            $this->close();
            $this->connect();

            sleep(1);
        }
    }
}
?>