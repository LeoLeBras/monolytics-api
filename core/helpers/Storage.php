<?php

  class Storage {

    /**
     * Retrieve data from storage
     * with a $key
     *
     * @param {string} $key
     * @return {any}
     */
    static public function get($key) {
      $c = new Cache();
      return $c->retrieve($key);
    }


    /**
     * Check if a key is cached
     *
     * @param {string} $key
     * @return {boolean}
     */
    static public function check($key) {
      $c = new Cache();
      $c->eraseExpired();
      return $c->isCached($key);
    }



    /**
     * Set cache storage
     *
     * @param {string} $key
     * @param {any} $value
     */
    static public function set($key, $value) {
      $c = new Cache();
      return $c->store($key, $value, 1800);
    }

  }
