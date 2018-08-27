<?php
 /**
 * Generic storage class helps to manage global data.
 * 
 * @category   Zero
 * @package    Yf_Registry
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */

class Yf_Registry extends ArrayObject
{
    /**
     * Class name of the singleton registry object.
     * @var string
     */
    private static $_instanceClassName = 'Yf_Registry';

    /**
     * Registry object provides storage for shared objects.
     * @var Yf_Registry
     */
    private static $_instance = null;

    /**
     * Retrieves the default registry instance.
     *
     * @return Yf_Registry
     */
    final public static function getInstance()
    {
        if (self::$_instance === null) {
            self::init();
        }

        return self::$_instance;
    }

    /**
     * Set the default registry instance to a specified instance.
     *
     * @param Yf_Registry $registry An object instance of type Yf_Registry,
     *   or a subclass.
     * @return void
     * @throws Exception if registry is already initialized.
     */
    public static function setInstance(Yf_Registry $registry)
    {
        if (self::$_instance !== null) {
            throw new Exception('Registry is already initialized');
        }

        self::setClassName(get_class($registry));
        self::$_instance = $registry;
    }

    /**
     * Initialize the default registry instance.
     *
     * @return void
     */
    protected static function init()
    {
        self::setInstance(new self::$_instanceClassName());
    }

    /**
     * Set the class name to use for the default registry instance.
     * Does not affect the currently initialized instance, it only applies
     * for the next time you instantiate.
     *
     * @param string $registryClassName
     * @return void
     * @throws Exception if the registry is initialized or if the
     *   class name is not valid.
     */
    public static function setClassName($registryClassName = 'Yf_Registry')
    {
        if (self::$_instance !== null) {
            throw new Exception('Registry is already initialized');
        }

        if (!is_string($registryClassName)) {
            throw new Exception("Argument is not a class name");
        }

        self::$_instanceClassName = $registryClassName;
    }

    /**
     * Unset the default registry instance.
     * Primarily used in tearDown() in unit tests.
     * @returns void
     */
    public static function _unsetInstance()
    {
        self::$_instance = null;
    }

    /**
     * getter method, basically same as offsetGet().
     *
     * This method can be called from an object of type Yf_Registry, or it
     * can be called statically.  In the latter case, it uses the default
     * static instance stored in the class.
     *
     * @param string $index - get the value associated with $index
     * @return mixed
     * @throws Exception if no entry is registerd for $index.
     */
    public static function get($index)
    {
        $instance = self::getInstance();

        if (!$instance->offsetExists($index)) {
            throw new Exception("No entry is registered for key '$index'");
        }

        return $instance->offsetGet($index);
    }

    /**
     * setter method, basically same as offsetSet().
     *
     * This method can be called from an object of type Yf_Registry, or it
     * can be called statically.  In the latter case, it uses the default
     * static instance stored in the class.
     *
     * @param string $index The location in the ArrayObject in which to store
     *   the value.
     * @param mixed $value The object to store in the ArrayObject.
     * @return void
     */
    public static function set($index, $value)
    {
        $instance = self::getInstance();
        $instance->offsetSet($index, $value);
    }

    /**
     * Returns TRUE if the $index is a named value in the registry,
     * or FALSE if $index was not found in the registry.
     *
     * @param  string $index
     * @return boolean
     */
    public static function isRegistered($index)
    {
        if (self::$_instance === null) {
            return false;
        }
        return self::$_instance->offsetExists($index);
    }

    /**
     * Constructs a parent ArrayObject with default
     * ARRAY_AS_PROPS to allow acces as an object
     *
     * @param array $array data array
     * @param integer $flags ArrayObject flags
     */
    public function __construct($array = array(), $flags = parent::ARRAY_AS_PROPS)
    {
        parent::__construct($array, $flags);
    }

    /**
     * @param string $index
     * @returns mixed
     *
     * Workaround for http://bugs.php.net/bug.php?id=40442 (ZF-960).
     */
    public function offsetExists($index)
    {
        return array_key_exists($index, $this);
    }

}

?>