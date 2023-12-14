<?php

/**
 * Get the base path
 * 
 * @param string $path
 * @return string
 */

 function basePath($path= '') {
  return __DIR__ . '/' . $path;
 }

 /**
  * Load a view
  *
  * @param string $name
  * @return void
  */

  function loadView($name, $data = []){

  $viewPath = basePath("App/views/{$name}.view.php");

  if(file_exists($viewPath)){
    extract($data);
    require $viewPath;
  } else {
    echo "View '{$name} not found!'";
  }
  }

  /**
  * Load a partial
  *
  * @param string $name
  * @return void
  */

  function loadPartial($name, $data = []){
  $partialPath = basePath("App/views/partials/{$name}.php");

  if(file_exists($partialPath)){
    extract($data);
    require $partialPath;
  } else {
    echo "Partial '{$name} not found!'";
  }
  }

  /**
   * Inspect a value(s)
   * 
   * @param mixed $value
   *  @return void
   */

   function inspect($value){
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
   }

   /**
   * Inspect a value(s) and die
   * 
   * @param mixed $value
   *  @return void
   */

   function inspectAndDie($value){
    echo '<pre>';
    die(var_dump($value));
    echo '</pre>';
    
   }

   /**
    * Format salary
    * @param string $salary
    * @return string Formatted Salary
    */
    function formatSalary($salary){
      return '$' . number_format(floatval($salary));
    }

    /**
     * Sanatize data
     * @param string $dirty
     * @return string
     */
    function sanitize(&$dirty, $key){
      // Check if the value is 'user_id' and cast it to an integer
      if ($key === 'user_id') {
        $dirty = (int)$dirty;
      } else {
          $dirty = filter_var(trim($dirty, FILTER_SANITIZE_SPECIAL_CHARS));
      }
    }

    /**
     * Redirect to a given url
     * 
     * @param string $url
     * @return void
     */
    function redirect($url){
      header("Location: {$url}");
      exit;
    }