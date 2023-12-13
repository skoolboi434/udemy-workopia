<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class ListingController
{
  protected $db;
  public function __construct() {
    $config = require basePath('config/db.php');
    $this->db = new Database($config);
  }

   /**
    * Show home page listings
    * @return void
    */

  public function index() {
    $listings = $this->db->query('SELECT * FROM listings ORDER BY created_at')->fetchAll();

    loadView('listings/index', [
      'listings' => $listings
    ]);
  }

  /**
   * Show the create listing form
   * @return void
   */
  public function create() {
    loadView('listings/create');
  }

   /**
    * Show single listing page
    * @param array $params
    * @return void
    */

  public function show($params) {
    $id = $params['id'] ?? '';

    $params = [
      'id' => $id
    ];

    $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

    // Check if listing exists
    if(!$listing){
      ErrorController::notFound('Listing not found');
      return;
    }


    loadView('listings/show', [
      'listing' => $listing
    ]);
  }

  /**
   * Store data in DB
   * 
   * @return void
   */

  public function store()
  {
    $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 
      'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];

    $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

    // Set user_id directly in the array
    $newListingData['user_id'] = 1;

    // Apply sanitize to all values in the array
    array_walk($newListingData, 'sanitize' );

    // Debugging
    // inspect($newListingData['user_id']);
    // inspectAndDie($newListingData);

    $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];

    $errors = [];

    foreach ($requiredFields as $field) {
        if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
            $errors[$field] = ucfirst($field) . ' is required';
        }
    }

    if (!empty($errors)) {
        // Reload view with errors
        loadView('listings/create', [
            'errors' => $errors,
            'listing' => $newListingData
        ]);
    } else {
        // Submit data

        $fields = [];

        foreach ($newListingData as $field => $value) {
            $fields[] = $field;
        }

        $fields = implode(', ', $fields);

        $values = [];

        foreach ($newListingData as $field => $value) {
            // Convert empty strings to null
            if ($value === '') {
                $newListingData[$field] = null;
            }
            $values[] = ':' . $field;
        }

        $values = implode(', ', $values);

        $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

        $this->db->query($query, $newListingData);

        redirect('/listings');
    }
  }

  /**
   * Delete a listing
   * 
   * @param array $params
   * @return void
   */

   public function destroy($params){
    $id = $params['id'];

    $params = [
      'id' => $id
    ];

    $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

    if(!$listing) {
      ErrorController::notFound('Listing not found');
      return;
    }

    $this->db->query('DELETE FROM listings WHERE id = :id', $params);

    redirect('/listings');
   }

}