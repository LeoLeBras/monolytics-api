<?php

  namespace Helpers;

  class Model {

    private $query;
    private $where;
    private $limit;
    private $orderBy;
    private $join;
    private $set;



    /**
     * Select specific entries
     *
     * @param {array} $params
     * @return {ctx}
     */
    public function where($params) {
      $select = '';
      foreach($params as $key => $param) {
        $select = $select.$key.'="'.$param.'" AND ';
      }
      $this->where = substr($select, 0, -4);
      return $this;
    }



    /**
     * Limit number of entries
     *
     * @param {int} $limit
     * @return {ctx}
     */
    public function limit($limit) {
      $this->limit = $limit;
      return $this;
    }



    /**
     * Order by entries
     *
     * @param {string} $column
     * @param {string} $type
     * @return {ctx}
     */
    public function orderBy($column, $type) {
      $this->orderBy = $column.' '.$type;
      return $this;
    }



    /**
     * Join a table
     *
     * @param {string} $table
     * @param {string} $join
     * @return {ctx}
     */
    public function join($table, $join) {
      $this->join = $table.' ON '.$join;
      return $this;
    }



    /**
     * Set entries
     *
     * @param {string} $params
     * @return {ctx}
     */
    public function set($params) {
      $this->set = $params;
      return $this;
    }



    /**
     * Remove specific entries
     *
     * @param {array} $params
     * @return {boolean}
     */
    public function removeWhere($params) {

      // Build query
      $select = '';
      foreach($params as $key => $param) {
        $select = $select.$key.'="'.$param.'" AND ';
      }
      $select = substr($select, 0, -4);

      // Execute
      global $pdo;
      return $pdo->exec('DELETE FROM '.$this->table.' WHERE '.$select);

    }



    /**
     * Build select query
     */
    public function select() {
      global $pdo;
      $query = 'SELECT * FROM '.$this->table;

      // WHERE defined
      if(!empty($this->where)) {
        $query = $query.' WHERE '.$this->where;
      }

      // ORDER BY DEFINED
      if(!empty($this->orderBy)) {
        $query = $query.' ORDER BY '.$this->orderBy;
      }

      // LIMIT defined
      if(!empty($this->limit)) {
        $query = $query.' LIMIT '.$this->limit;
      }

      // JOIN defined
      if(!empty($this->join)) {
        $query = $query.' INNER JOIN '.$this->join;
      }

      return $pdo->query($query);
    }



   /**
    * Find an entry by id
    *
    * @param {integer} id
    */
   public function find($id) {
     global $pdo;
     $this->query = $pdo->query('SELECT * FROM '.$this->table.' WHERE id="'.$id.'" LIMIT 1');
     return $this;
   }



    /**
     * Return all entries
     */
    public function fetchAll(){
      return $this->select()->fetchAll();
    }



    /**
     * Return first entry
     */
    public function fetch() {
      return $this->select()->fetch();
    }



    /**
     * Save an entry
     */
    public function save() {
      if(empty($this->where)) {
        return $this->create();
      }
      return $this->update();
    }



    /**
     * Create an entry in a table
     *
     * @return {boolean}
     */
    public function create() {

      // Set datetime
      $this->set['created_at'] = date("Y-m-d H:i:s");
      $this->set['updated_at'] = date("Y-m-d H:i:s");

      // Set keys and values
      $keys = '';
      $values = '';
      foreach($this->set as $key => $value) {
        $keys = $keys.$key.', ';
        $values = $values."'".$value."', ";
      }
      $keys = substr($keys, 0, -2);
      $values = substr($values, 0, -2);

      // Create query
      global $pdo;
      $query = "INSERT INTO ".$this->table." (".$keys.") VALUES (".$values.")";

      return $pdo->exec($query);

    }



    /**
     * Update an entry in a table
     *
     * @return {boolean}
     */
    public function update() {

      // Set datetime
      $this->set['updated_at'] = date("Y-m-d H:i:s");

      // Build query
      $query = 'UPDATE '.$this->table;
      $set = '';
      foreach($this->set as $key => $value) {
        $set = $set.$key.' = "'.$value.'", ';
      }
      $set = substr($set, 0, -2);

      // Run query
      global $pdo;
      $query = 'UPDATE '.$this->table;
      $query = $query.' SET '.$set;
      $query = $query.' WHERE '.$this->where;
      return $pdo->exec($query);

    }


    /**
     * Remove an entry in a table
     *
     * @return {bolean}
     */
    public function remove() {
      global $pdo;
      $query = 'DELETE FROM '.$this->table;

      // WHERE defined
      if(!empty($this->where)) {
        $query = $query.' WHERE '.$this->where;
      }

      return $pdo->exec($query);
    }


    /**
     * Get last inserted id
     *
     * @return {int}
     */
    public function lastInsertId() {
      global $pdo;
      return $pdo->lastInsertId();
    }


    /**
     * Truncate the table
     *
     * @return {bolean}
     */
    public function truncate() {
      global $pdo;
      return $pdo->exec('TRUNCATE TABLE '.$this->table);
    }


  }
