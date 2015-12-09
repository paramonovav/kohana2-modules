<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * File-based Cache driver with msgpack serialize.
 *
 * $Id: Msgpackfile.php 4046 2015-11-13 19:23:29Z pokimon $
 *
 * @package  Cache::MsgpackFile
 * @version  2.0
 * @author   Anton Paramonov <paramonovav@gmail.com>
 * @license  https://kohanaframework.org/license
 */
class Cache_MsgpackFile_Driver extends Cache_File_Driver {

	/**
	 * Tests that the igbinary extension is loaded.
	 */
	public function __construct($directory)
	{
		if ( ! extension_loaded('msgpack'))
		{
			throw new Kohana_Exception('cache.extension_not_loaded', 'msgpack');
		}

		parent::__construct($directory);
	}

	/**
	 * Finds an array of files matching the given id or tag.
	 *
	 * @param  string  cache id or tag
	 * @param  bool    search for tags
	 * @return array   of filenames matching the id or tag
	 */
	public function exists($id, $tag = FALSE)
	{
		if ($id === TRUE)
		{
			// Find all the files
			return glob($this->directory.'*~*~*.msgpack');
		}
		elseif ($tag === TRUE)
		{
			// Find all the files that have the tag name
			$paths = glob($this->directory.'*~*'.$id.'*~*.msgpack');

			// Find all tags matching the given tag
			$files = array();
			foreach ($paths as $path)
			{
				// Split the files
				$tags = explode('~', basename($path));

				// Find valid tags
				if (count($tags) !== 3 OR empty($tags[1]))
					continue;

				// Split the tags by plus signs, used to separate tags
				$tags = explode('+', $tags[1]);

				if (in_array($tag, $tags))
				{
					// Add the file to the array, it has the requested tag
					$files[] = $path;
				}
			}

			return $files;
		}
		else
		{
			// Find the file matching the given id
			return glob($this->directory.$id.'~*.msgpack');
		}
	}

	/**
	 * Sets a cache item to the given data, tags, and lifetime.
	 *
	 * @param   string   cache id to set
	 * @param   string   data in the cache
	 * @param   array    cache tags
	 * @param   integer  lifetime
	 * @return  bool
	 */
	public function set($id, $data, array $tags = NULL, $lifetime)
	{
		// Remove old cache files
		$this->delete($id);

		// Cache File driver expects unix timestamp
		if ($lifetime !== 0)
		{
			$lifetime += time();
		}

		if ( ! empty($tags))
		{
			// Convert the tags into a string list
			$tags = implode('+', $tags);
		}

		// Write out a serialized cache
		return (bool) file_put_contents($this->directory.$id.'~'.$tags.'~'.$lifetime.'.msgpack', msgpack_pack($data));
	}

	/**
	 * Fetches a cache item. This will delete the item if it is expired or if
	 * the hash does not match the stored hash.
	 *
	 * @param   string  cache id
	 * @return  mixed|NULL
	 */
	public function get($id)
	{
		if ($file = $this->exists($id))
		{
			// Use the first file
			$file = current($file);

			// Validate that the cache has not expired
			if ($this->expired($file))
			{
				// Remove this cache, it has expired
				$this->delete($id);
			}
			else
			{
				// Turn off errors while reading the file
				$ER = error_reporting(0);

				if (($data = file_get_contents($file)) !== FALSE)
				{
					// Unserialize the data
					$data = msgpack_unpack($data);
				}
				else
				{
					// Delete the data
					unset($data);
				}

				// Turn errors back on
				error_reporting($ER);
			}
		}

		// Return NULL if there is no data
		return isset($data) ? $data : NULL;
	}
} // End Cache MsgpackFile Driver