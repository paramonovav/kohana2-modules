<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Memcached Cache driver.
 *
 * $Id: Memcached.php 4102 2015-11-13 12:55:54Z pokimon $
 *
 * @package  Cache::Memcached
 * @version  2.0
 * @author   Anton Paramonov <paramonovav@gmail.com>
 * @license  https://kohanaframework.org/license
 */
class Cache_Memcached_Driver implements Cache_Driver {

	// Cache backend object and flags
	protected $backend;

	public function __construct()
	{
		if ( ! extension_loaded('memcached'))
		{
			throw new Kohana_Exception('cache.extension_not_loaded', 'memcached');
		}

		$this->backend = new Memcached;

		$servers = Kohana::config('cache/memcached.servers');

		if (empty($servers))
		{
			throw new Exception('no Memcached servers in config/cache/memcached.php');
		}
		
		$options = Kohana::config('cache/memcached.options');

        // set options
        foreach ($options as $option => $value)
        {
            if ($option === Memcached::OPT_SERIALIZER && $value === Memcached::SERIALIZER_IGBINARY && !Memcached::HAVE_IGBINARY)
            {
                // exception serializer Igbinary not supported
                throw new Exception('serializer Igbinary not supported, please fix config/cache/memcached.php');
            }
            if ($option === Memcached::OPT_SERIALIZER && $value === Memcached::SERIALIZER_JSON && !Memcached::HAVE_JSON)
            {
                // exception serializer JSON not supported
                throw new Exception('serializer JSON not supported, please fix config/cache/memcached.php');
            }
            $this->backend->setOption($option, $value);
        }

		foreach ($servers as $server)
		{
			if (TRUE === $server['status'])
			{
				// Add the server to the pool
				$this->backend->addServer($server['host'], $server['port'], $server['weight']);
			}
		}
	}

	public function __destruct()
	{
	}

	public function find($tag)
	{
	}

	public function get($id)
	{
        $result = $this->backend->get($id);

        if ($this->backend->getResultCode() !== Memcached::RES_SUCCESS)
        {
            $result = NULL;
        }

        return $result;
	}

	public function set($id, $data, array $tags = NULL, $lifetime = 3600)
	{
		return $this->backend->set($id, $data, $lifetime);
	}

	public function delete($id, $tag = FALSE)
	{
		return $this->backend->delete($id);
	}

	public function delete_expired()
	{
	}

    public function delete_all()
    {
        return $this->backend->flush();
    }	
} // End Cache Memcached Driver
