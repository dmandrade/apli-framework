<?php

namespace Apli\Data;


class Helper
{

    /**
     * Utility function to convert all types to an array.
     *
     * @param $data
     * @param bool $recursive
     * @return array
     */
    public static function toArray($data, $recursive = false)
    {
        // Ensure the input data is an array.
        if ($data instanceof \Traversable) {
            $data = iterator_to_array($data);
        } elseif (is_object($data)) {
            $data = get_object_vars($data);
        } else {
            $data = (array)$data;
        }

        if ($recursive) {
            foreach ($data as &$value) {
                if (is_array($value) || is_object($value)) {
                    $value = static::toArray($value, $recursive);
                }
            }
        }

        return $data;
    }


    /**
     * Set value in a specified path in data structure
     *
     * @param array $data
     * @param $path
     * @param $value
     * @param string $separator
     * @return bool
     */
    public static function setByPath(array &$data, $path, $value, $separator = '.')
    {
        $nodes = static::getPathNodes($path, $separator);

        if (empty($nodes)) {
            return false;
        }

        $dataTmp = &$data;

        foreach ($nodes as $node) {
            if (is_array($dataTmp)) {
                if (!isset($dataTmp[$node])) {
                    $dataTmp[$node] = [];
                }

                $dataTmp = &$dataTmp[$node];
                continue;
            }

            // If a node is value but path is not go to the end, we replace this value as a new store.
            // Then next node can insert new value to this store.
            $dataTmp = [];
        }

        // Now, path go to the end, means we get latest node, set value to this node.
        $dataTmp = $value;

        return true;
    }

    /**
     * Explode the data path into an sequential array and remove empty
     * nodes that occur as a result of a double dot. ex: apli..test
     *
     * @param $path
     * @param string $separator
     * @return array
     */
    public static function getPathNodes($path, $separator = '.')
    {
        return array_values(array_filter(explode($separator, $path), 'strlen'));
    }

    /**
     * Get data from array or object by path.
     *
     * @param array $data
     * @param $path
     * @param string $separator
     * @return array|mixed|null
     */
    public static function getByPath(array $data, $path, $separator = '.')
    {
        $nodes = static::getPathNodes($path, $separator);

        if (empty($nodes)) {
            return null;
        }

        $dataTmp = $data;

        foreach ($nodes as $arg) {
            if (is_object($dataTmp) && isset($dataTmp->$arg)) {
                $dataTmp = $dataTmp->$arg;
            } elseif ($dataTmp instanceof \ArrayAccess && isset($dataTmp[$arg])) {
                $dataTmp = $dataTmp[$arg];
            } elseif (is_array($dataTmp) && isset($dataTmp[$arg])) {
                $dataTmp = $dataTmp[$arg];
            } else {
                return null;
            }
        }

        return $dataTmp;
    }

    /**
     * Remove a specified path in data
     *
     * @param array $data
     * @param $path
     * @param string $separator
     * @return bool
     */
    public static function removeByPath(array &$data, $path, $separator = '.')
    {
        $nodes = static::getPathNodes($path, $separator);

        if (empty($nodes)) {
            return false;
        }

        $previous = null;
        $dataTmp = &$data;

        foreach ($nodes as $node) {
            if (is_array($dataTmp)) {
                if (empty($dataTmp[$node])) {
                    return false;
                }

                $previous = &$dataTmp;
                $dataTmp = &$dataTmp[$node];
            } else {
                return false;
            }
        }

        // Now, path go to the end, means we get latest node, set value to this node.
        unset($previous[$node]);

        return true;
    }
}