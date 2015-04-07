<?php

namespace Kasha\Caching;

class Cache
{
	/** @var Cache */
	private static $instance;

	/** @var string */
	public $filePath = '';

	/** @var bool */
	private $isValid = false;

	/**
	 * @param $rootFolderPath - should end with slash!
	 */
	public function setRootFolder($rootFolderPath)
	{
		if (!file_exists($this->filePath)) {
			@mkdir($this->filePath, 0777);
		}
		if (file_exists($this->filePath)) {
			$this->filePath = $rootFolderPath;
			$this->isValid = true;
		}
	}

	/**
	 * Cache class is a singleton - reuse the same instance of an object
	 *
	 * @return Cache|null
	 * @throws \Exception
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new Cache();
		}

		return self::$instance;
	}

	/**
	 * Gets value from the cache by id
	 *
	 * @param string $key
	 *
	 * @return string|bool
	 */
	public static function get($key)
	{
		$instance = self::$instance;
		if (!$instance->isValid) return false;

		$fileName = $instance->filePath . $key . '.txt';

		return file_exists($fileName) ? file_get_contents($fileName) : false;
	}

	/**
	 * Sets value to the cache using the key
	 *
	 * @param string $key
	 * @param string $value
	 */
	public static function set($key, $value)
	{
		$instance = self::$instance;
		if (!$instance->isValid) return;

		$fileName = $instance->filePath . $key . '.txt';
		try {
			// check if all folders in the path exist for the $key
			$pathFolders = explode('/', $key);
			if (count($pathFolders) > 1) {
				$pureKeyName = array_pop($pathFolders); // do not create folder for the last element
				$path = $instance->filePath;
				foreach ($pathFolders as $folderName) {
					$path .= ($folderName . '/');
					if (!file_exists($path)) {
						mkdir($path);
					}
				}
			}
			// safely write out the cache item
			file_put_contents($fileName, $value);
		} catch(\Exception $ex) {
			// @TODO report if needed
		}
	}

	/**
	 * Deletes value from the cache using key
	 *
	 * @param string $key
	 */
	public static function delete($key)
	{
		$instance = self::$instance;
		if (!$instance->isValid) return;

		$fileName = $instance->filePath . $key . '.txt';
		try {
			unlink($fileName);
		} catch(\Exception $ex) {
			// @TODO report if needed
		}
	}

	/**
	 * Checks if given key exists in the cache
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public static function hasKey($key)
	{
		$instance = self::$instance;
		if (!$instance->isValid) return false;

		$fileName = self::$instance->filePath . $key . '.txt';

		return file_exists($fileName);
	}

	/**
	 * Enumerates all keys that have specific prefix
	 *
	 * @param string $prefix
	 *
	 * @return array
	 */
	public static function listKeysByPrefix($prefix = null)
	{
		$instance = self::$instance;
		if (!$instance->isValid) return array();

		return glob(self::$instance->filePath . "$prefix*");
	}

	public static function deleteByPrefix($prefix)
	{
		return count(array_map("unlink", glob(self::$instance->filePath . "$prefix*")));
	}

}
