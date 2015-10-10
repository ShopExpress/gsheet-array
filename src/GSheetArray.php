<?php

namespace Ychuperka\GS2a;

use Ychuperka\GS2a\Exception as GSheetArrayException;
use Onoi\HttpRequest\HttpRequestFactory;
use Onoi\Cache\Cache;

/**
 * Class GSheetArray
 * @package Ychuperka\GS2a
 */
class GSheetArray implements \ArrayAccess, \Iterator
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $fileId;

    /**
     * @var HttpRequestFactory
     */
    private $httpRequestFactory;

    /**
     * @var
     */
    private $iterIndex;

    /**
     * @param string $fileId
     * @param Cache|null $cache
     */
    public function __construct($fileId, Cache $cache = null)
    {
        $this->fileId = $fileId;
        $this->iterIndex = 0;
        $this->httpRequestFactory = new HttpRequestFactory($cache);
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return $this->getData();
    }

    /**
     * @return array
     * @throws Exception
     */
    private function getData()
    {
        if ($this->data !== null) {
            return $this->data;
        }

        $this->data = $this->parseDocument(
            $this->downloadDocument()
        );
        return $this->data;
    }

    /**
     * @return string
     * @throws Exception
     */
    private function downloadDocument()
    {
        $curlHttpRequest = $this->httpRequestFactory->newCachedCurlRequest(
            'https://docs.google.com/spreadsheets/export?id=' . $this->fileId . '&exportFormat=csv'
        );
        $options = [
            CURLOPT_TIMEOUT => 15,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ];
        foreach ($options as $key => $o) {
            $curlHttpRequest->setOption($key, $o);
        }

        $response = $curlHttpRequest->execute();
        if ($curlHttpRequest->getLastErrorCode() != CURLE_OK) {
            throw new GSheetArrayException(
                'Can`t download document! Curl error #' . $curlHttpRequest->getLastErrorCode()
                . ', curl message: ' . $curlHttpRequest->getLastError()
            );
        }

        return str_replace("\r", null, $response);
    }

    /**
     * @param string $response
     * @return array
     */
    private function parseDocument($response)
    {
        $rows = str_getcsv($response, "\n");
        $result = [];
        foreach ($rows as $r) {
            $items = str_getcsv($r, ',');
            foreach ($items as $key => $i) {
                $len = strlen(
                    trim($i)
                );
                if ($len == 0) {
                    $items[$key] = null;
                }
            }
            $result[] = $items;
        }

        return $result;
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
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->getData()[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->getData()[$offset];
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
     * @since 5.0.0
     * @throws GSheetArrayException
     */
    public function offsetSet($offset, $value)
    {
        throw new GSheetArrayException('GSheetArray has a read-only access');
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     * @throws GSheetArrayException
     */
    public function offsetUnset($offset)
    {
        throw new GSheetArrayException('GSheetArray has a read-only access');
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->getData()[$this->iterIndex];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->iterIndex++;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->iterIndex;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->getData()[$this->iterIndex]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->iterIndex = 0;
    }
}