<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 09/07/2018
 * Time: 15:30
 */

namespace Apli\Data;

class Data implements DataInterface, \JsonSerializable, \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * Property separator.
     *
     * @var  string
     */
    protected $separator = '.';

    /**
     * Data store.
     *
     * @var    array
     * @since  2.0
     */
    protected $data = [];

    /**
     * Property ignoreValues.
     *
     * @var  array
     */
    protected $ignoreValues = [null];

    /**
     * Constructor.
     *
     * @param mixed $data
     */
    public function __construct($data = null)
    {
        if (null !== $data) {
            $this->bind($data);
        }
    }

    /**
     * Get a value from data
     *
     * @param $path
     * @param null $default
     * @return null
     */
    public function get($path, $default = null)
    {
        $result = Helper::getByPath($this->data, $path, $this->separator);

        return !is_null($result) ? $result : $default;
    }

    /**
     * Set a value and convert object to array.
     *
     * @param $field
     * @param $value
     * @return $this
     */
    public function set($field, $value)
    {
        if (is_array($value) || is_object($value)) {
            $value = Helper::toArray($value, true);
        }

        Helper::setByPath($this->data, $field, $value, $this->separator);

        return $this;
    }


    /**
     * Gets this object represented as an RecursiveArrayIterator.
     *
     * This allows the data properties to be accessed via a foreach statement.
     *
     * @return  \RecursiveArrayIterator a object iterator
     */
    public function getIterator()
    {
        return new \RecursiveArrayIterator($this->data);
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        // TODO: Implement count() method.
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }

    /**
     * Bind the data
     *
     * @param $values
     * @param bool $replaceNulls
     * @return mixed
     */
    public function bind($values, $replaceNulls = false)
    {
        $this->bindData($this->data, $values, $replaceNulls);

        return $this;
    }

    /**
     * Method to recursively bind data to a parent object.
     *
     * @param $parent
     * @param $data
     * @param bool $replaceNulls
     */
    protected function bindData(&$parent, $data, $replaceNulls = false)
    {
        // Ensure the input data is an array.
        $data = Helper::toArray($data, true);

        foreach ($data as $key => $value) {
            if (in_array($value, $this->ignoreValues, true)) {
                continue;
            }

            if (is_array($value)) {
                if (!isset($parent[$key]) || !is_array($parent[$key])) {
                    $parent[$key] = [];
                }

                $this->bindData($parent[$key], $value, $replaceNulls);
                continue;
            }

            if ($value === null && !$replaceNulls) {
                continue;
            }

            $parent[$key] = $value;
        }
    }

    /**
     * Check if data is empty
     *
     * @return mixed
     */
    public function isNull()
    {
        // TODO: Implement isNull() method.
    }

    /**
     * Check if data is not null
     *
     * @return mixed
     */
    public function notNull()
    {
        // TODO: Implement notNull() method.
    }

    /**
     * Dump all data as array
     *
     * @return  array
     */
    public function dump()
    {
        // TODO: Implement dump() method.
    }

    /**
     * __get
     *
     * @param   string $name
     *
     * @return  mixed
     */
    public function __get($name)
    {
        // TODO: Implement __get() method.
    }
}