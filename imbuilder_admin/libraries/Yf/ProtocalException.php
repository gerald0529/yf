<?php
/**
 * 协议异常类
 * 
 * 发生错误，用来统一输出。
 * 
 * @category   Framework
 * @package    View
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */

/**
 * An Error Exception.
 * @link http://php.net/manual/en/class.errorexception.php
 */
class Yf_ProtocalException extends Exception {

    protected $id;


    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the exception
     * @link http://php.net/manual/en/errorexception.construct.php
     * @param string $message [optional] The Exception message to throw.
     * @param int $code [optional] The Exception code.
     * @param int $severity [optional] The severity level of the exception.
     * @param string $filename [optional] The filename where the exception is thrown.
     * @param int $lineno [optional] The line number where the exception is thrown.
     * @param Exception $previous [optional] The previous exception used for the exception chaining.
     */
    public function __construct($message = "", $code = 0, $id = 0, $filename = __FILE__, $lineno = __LINE__) {
        $this->id = $id;
        parent::__construct($message, $code);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Gets the exception severity
     * @link http://php.net/manual/en/errorexception.getseverity.php
     * @return int the severity level of the exception.
     */
    final public function getId() {
        return $this->id;
    }
}

?>