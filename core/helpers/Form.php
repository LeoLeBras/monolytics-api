<?php

  require_once(CORE_DIR.'/helpers/FormValidator.php');

  class Form extends FormValidator {


    /**
     * Test if a form was sent
     *
     * @return {boolean}
     */
    static public function sent() {
      if(!empty($_POST)) {
        return true;
      }
      return false;
    }



    /**
     * Check if the form is valid
     *
     * @param {array} $rules
     * @return {boolean | array}
     */
    static public function valid($form, $rules) {

      // Form is valid by default
      $valid = true;

      // Parse each input
      foreach ($rules as $key => $validationRules) {
        $value = $form[$key];

        // Validator
        foreach (explode('|', $validationRules) as $rule) {
          $validationMethod = 'is'.ucfirst($rule);
          if(!self::$validationMethod($value)) {
            $valid = false;
          }
        }
      }

      return $valid;
    }



    /**
     * Return form errors
     *
     * @param {array} $form
     * @param {array} $rules
     * @return { array}
     */
    static public function getErrors($form, $rules) {
      $errors = new stdClass;

      // Parse each input
      foreach ($rules as $key => $validationRules) {
        $value = $form[$key];
        $errors->$key = array();

        // Validator
        foreach (explode('|', $validationRules) as $rule) {
          $validationMethod = 'is'.ucfirst($rule);
          if(!self::$validationMethod($value)) {
            $error = '';
            switch($validationMethod) {
              case 'isEmail':
                $error = 'Adresse mail non valide';
                break;
              case 'isNumber':
                $error = 'La valeur ne correspond pas à un nombre';
                break;
              case 'isRequired':
                $error = 'Champ requis';
                break;
            }
            array_push($errors->$key, $error);
          }
        }
      }

      return $errors;
    }



    /**
     * Get form data
     *
     * @return {array}
     */
    static public function get() {
      return $_POST;
    }

  }
