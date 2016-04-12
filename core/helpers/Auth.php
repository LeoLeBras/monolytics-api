<?php

  require_once(APP_DIR.'/models/User.php');
  require_once(CORE_DIR.'/helpers/Route.php');

  class Auth {

    /**
     * Log a user
     *
     * @param {array} $form
     * @return {boolean}
     */
    static public function login($form) {

      // Fetch user
      $user = new User();
      $response = $user
        ->where(array(
          'email' => $form['email']
        ))
        ->fetch();
      if(empty($response)) return false;

      // Check password
      if(self::checkPassword($form['password'], $response->password)) {
        $_SESSION['user_id'] = $response->id;
        $_SESSION['user_name'] = $response->name;
        return true;
      }
      return false;

    }



    /**
     * Subscribe a user
     *
     * @param {array} $data:
     *    - {string} name
     *    - {string} email
     *    - {string} password
     * @return {boolean}
     */
    static public function subscribe($data) {
      $user = new User();
      return $user->set($data)->save();
    }



    /**
     * Check if the user is logged.
     * If he's not, redirect it to
     * the login page
     *
     * @return {boolean}
     */
    static public function check() {
      if(self::isLogged()) {
        return true;
      }
      Route::redirect('login');
    }



    /**
     * Logout
     */
    static public function logout() {
      return session_destroy();
    }



    /**
     * Check if the user is logged.
     *
     * @return {boolean}
     */
    static public function isLogged() {
      if(isset($_SESSION['user_id'])) {
        return true;
      }
      return false;
    }



    /**
     * Get user information
     *
     * @param {key}
     * @return {string}
     */
    static public function get($key) {
      if(isset($_SESSION['user_'.$key])) {
        return $_SESSION['user_'.$key];
      }
      return null;
    }



    /**
     * Hash password of the user
     *
     * > http://www.sitepoint.com/password-hashing-in-php/
     *
     * @param {string} $password
     * @return {strgin}
     */
    static public function hashPassword($password) {
      if(defined('CRYPT_BLOWFISH') && CRYPT_BLOWFISH) {
        $salt = '$2y$11$'.substr(sha1(uniqid(rand(), true)), 0, 22);
        return crypt($password, $salt);
      }
    }



    /**
     * Check if the password of the user
     * is valid
     *
     * > http://www.sitepoint.com/password-hashing-in-php/
     *
     * @param {string} $password
     * @param {string} $hashedPassword
     * @return {boolean}
     */
    static public function checkPassword($password, $hashedPassword) {
      return crypt($password, $hashedPassword) == $hashedPassword;
    }

  }
