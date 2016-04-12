<?php

  class FormValidator {

    /**
     * The value is a valid email
     *
     * @param {string} $value
     * @return {boolean}
     */
    static public function isEmail($value) {
      return filter_var($value, FILTER_VALIDATE_EMAIL);
    }



    /**
     * The value is set
     *
     * @param {string} $value
     * @return {boolean}
     */
    static public function isRequired($value) {
      return !empty($value);
    }


    /**
     * THe value is a float
     *
     * @param {number} value
     * @return {boolean}
     */
    static public function isNumber($value) {
      return is_numeric($value);
    }

  }
