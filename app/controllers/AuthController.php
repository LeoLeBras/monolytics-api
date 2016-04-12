<?php

  require_once(CORE_DIR.'/helpers/View.php');
  require_once(CORE_DIR.'/helpers/Form.php');
  require_once(CORE_DIR.'/helpers/Route.php');
  require_once(CORE_DIR.'/helpers/Auth.php');
  require_once(APP_DIR.'/models/User.php');

  class AuthController {

    /**
     * Login form
     */
    public function login() {

      // Set form
      $error = false;
      $rules = array(
        'email' => 'email|required',
        'password' => 'required'
      );

      // Send form
      if(Form::sent()) {
        $form = Form::get();
        if(Form::valid($form, $rules) && Auth::login($form)) {
          Route::redirect('/index');
        }
        else {
          $error = true;
        }
      }

      // Render view
      View::make('auth/login', array(
        'error' => $error
      ));

    }



    /**
     * Subscribe form
     */
    public function subscribe() {

      // Set form
      $form = Form::get();
      $error = false;
      $errors = new stdClass;
      $rules = array(
        'name' => 'required',
        'email' => 'email|required',
        'password' => 'required'
      );

      // Send form
      if(Form::sent()) {
        $query = $form;
        $query['password'] = Auth::hashPassword($form['password']);
        if(Form::valid($form, $rules) && Auth::subscribe($query)) {
          return View::make('auth/subscribe--success');
        }
        else {
          $errors = Form::getErrors($form, $rules);
          $error = true;
        }
      }

      // Render view
      View::make('auth/subscribe', array(
        'form' => $form,
        'error' => $error,
        'errors' => $errors
      ));

    }



    /**
     * Logout
     */
    public function logout() {
      if(Auth::logout()) {
        Route::redirect('/');
      }
    }

  }
