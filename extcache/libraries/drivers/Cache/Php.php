<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * PHP static based Cache driver.
 *
 * $Id: Php.php 4848 2015-11-13 19:23:29Z pokimon $
 *
 * @package  Cache::PHP
 * @version  2.0
 * @author   Anton Paramonov <paramonovav@gmail.com>
 * @license  https://kohanaframework.org/license
 */
class Cache_Php_Driver implements Cache_Driver {

     static protected 
          $Cache, $Tags;

     /**
      * Tests that the storage location is a directory and is writable.
      */
     public function __construct()
     {
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
          return isset(self::$Cache[$id])? self::$Cache[$id]: FALSE;
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
          self::$Cache[$id] = $data;

          if (!empty($tags))
          {
               foreach ($tags as $tag)
               {
                    self::$Tags[$tag][$id] = $id;
               }
          }
     }

     /**
      * Finds an array of ids for a given tag.
      *
      * @param  string  tag name
      * @return array   of ids that match the tag
      */
     public function find($tag)
     {
          return isset(self::$Tags[$tag])? self::$Tags[$tag]: array();
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
          return isset(self::$Cache[$id])? self::$Cache[$id]: NULL;
     }

     /**
      * Deletes a cache item by id or tag
      *
      * @param   string   cache id or tag, or TRUE for "all items"
      * @param   boolean  use tags
      * @return  boolean
      */
     public function delete($id, $tag = FALSE)
     {
          if (isset(self::$Cache[$id]))
          {
               unset(self::$Cache[$id]);
          }
     }

     /**
      * Deletes all cache files that are older than the current time.
      *
      * @return void
      */
     public function delete_expired()
     {
     }

     /**
      * Check if a cache file has expired by filename.
      *
      * @param  string  filename
      * @return bool
      */
     protected function expired($file)
     {
     }
} // End Cache File Driver