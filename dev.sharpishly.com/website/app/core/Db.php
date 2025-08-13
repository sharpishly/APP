<?php
namespace App\Core;
use PDO;
use PDOException;

class Db {
    public $config;
    public $result;
    public $wp;
    public $credentials;
    public $trace = array();
    private $pdo; // Store the PDO connection

    public function __construct() {
        $this->credentials = array(
            'servername' => DB_HOST,
            'username' => DB_USER,
            'password' => DB_PASSWORD,
            'dbname' => DB_NAME
        );
    }

    public function string($val) {
        return "'" . $val . "'";
    }

    public function chain($rs, $sql, $options) {
        $el = 'save_insert';
        if ($options['counter'] < 1) {
            $sql = $rs[$el];
        } else {
            $search = $rs['save_statement'];
            $search = str_replace('VALUES (', 'VALUES ', $search);
            $val = str_replace($search, '', $rs[$el]);
            $sql .= ',' . $val;
        }
        return $sql;
    }

    public function savem($conditions, $options = false) {
        $result = array();
        if (isset($conditions[0]['table'])) {
            $options[__FUNCTION__] = TRUE;
            $counter = 0;
            $rs = $conditions;
            $sql = '';
            while ($counter < count($rs)) {
                $options['counter'] = $counter;
                $res = $this->save($rs[$counter], $options);
                $sql = $this->chain($res, $sql, $options);
                $counter++;
            }
            $data = array('sql' => $sql);
            $result['result'] = $this->conn($data);
        }
        return $result;
    }

    public function obj_to_array($r) {
        $result = array();
        foreach ($r as $key => $value) {
            $result[$key] = (array) $value;
        }
        return $result;
    }

    /**
     * Establishes a PDO connection to the MySQL database with retry logic and SSL disabled.
     * Retries up to 5 times with a 2-second delay to handle transient connection issues.
     * Disables SSL verification to bypass self-signed certificate errors.
     *
     * @return PDO The established PDO connection object.
     * @throws \Exception If connection fails after maximum retries.
     */
    private function connect() {
        if ($this->pdo === null) {
            extract($this->credentials); // Extract DB_HOST, DB_USER, DB_PASSWORD, DB_NAME
            $maxRetries = 5; // Maximum number of connection attempts
            $retryDelay = 2; // Delay between retries in seconds
            $attempt = 0;

            while ($attempt < $maxRetries) {
                try {
                    // Initialize PDO with SSL verification disabled to avoid self-signed certificate errors
                    $this->pdo = new PDO(
                        "mysql:host=$servername;dbname=$dbname",
                        $username,
                        $password,
                        [PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false]
                    );
                    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                    error_log("Database connection successful");
                    return $this->pdo;
                } catch (PDOException $e) {
                    $attempt++;
                    if ($attempt === $maxRetries) {
                        throw new \Exception("Connection failed after $maxRetries attempts: " . $e->getMessage());
                    }
                    error_log("Connection attempt $attempt failed: " . $e->getMessage() . ". Retrying in $retryDelay seconds...");
                    sleep($retryDelay);
                }
            }
        }
        return $this->pdo;
    }

    public function conn($data, $options = false) {
        $conn = $this->connect();
        try {
            $stmt = $conn->prepare($data['sql']);
            $stmt->execute();
            if (isset($options['action'])) {
                $data['result'] = $stmt->fetchAll();
            }
            if (null !== $conn->lastInsertId()) {
                $data['inserted'] = $conn->lastInsertId();
            }
            $data['success'] = true;
        } catch (PDOException $e) {
            $data['error'] = $e->getMessage();
            $data['success'] = false;
            error_log("Database Error: " . $e->getMessage());
        }
        return $data;
    }

    public function create($conditions) {
        $data = array();
        $data = $this->create_process($conditions, $data);
        $data = $this->conn($data);
        return $data;
    }

    public function mupdate($conditions, $options = false) {
        $data = array();
        if (isset($conditions[0]['table'])) {
            $data['sql'] = "INSERT INTO `" . $conditions[0]['table'] . "`";
            $data = $this->muppet($data, $conditions, $options);
            $data['sql'] .= ' VALUES ';
            $data = $this->rave($data, $conditions);
            $data['sql'] .= " ON DUPLICATE KEY UPDATE ";
            $data = $this->money($data, $conditions);
            $data = $this->conn($data);
        } else {
            $data['mupdate'] = '$conditions[0] does not exist!';
        }
        return $data;
    }

    public function money($data, $conditions) {
        $it = $conditions[0]['update'];
        $it = new \ArrayIterator($it);
        $it = new \CachingIterator($it);
        $res = '';
        foreach ($it as $e) {
            if ($it->hasNext()) {
                $res .= $it->key() . '=VALUES(' . $it->key() . '),';
            } else {
                $res .= $it->key() . '=VALUES(' . $it->key() . ');';
            }
        }
        $data['sql'] .= $res;
        return $data;
    }

    public function rave($data, $conditions, $options = false) {
        $it = new \ArrayIterator($conditions);
        $it = new \CachingIterator($it, 0);
        foreach ($it as $e) {
            if ($it->hasNext()) {
                $options['it'] = 'next';
                $data[__FUNCTION__] = $it->current();
                $data = $this->quartz($data, $options);
            } else {
                $options['it'] = 'end';
                $data[__FUNCTION__] = $it->current();
                $data = $this->quartz($data, $options);
            }
        }
        return $data;
    }

    public function quartz($data, $options) {
        $it = $data['rave']['update'];
        $id = $data['rave']['where']['id'];
        $it = new \ArrayIterator($it);
        $it = new \CachingIterator($it);
        $res = '(' . $id . ', ';
        foreach ($it as $e) {
            if ($it->hasNext()) {
                $res .= "'" . $it->current() . "', ";
            } else {
                $res .= "'" . $it->current() . "' ";
            }
        }
        $res = $res . ')';
        if ($options['it'] === 'next') {
            $res .= ', ';
        }
        $data['sql'] .= $res;
        return $data;
    }

    public function muppet($data, $conditions, $options) {
        $it = new \ArrayIterator($conditions[0]['update']);
        $it = new \CachingIterator($it);
        $res = '(id,';
        foreach ($it as $e) {
            if ($it->hasNext()) {
                $res .= $it->key() . ',';
            } else {
                $res .= $it->key();
            }
        }
        $data['sql'] .= $res . ')';
        return $data;
    }

    public function update($conditions, $options = false) {
        $data = array();
        $options = array('action' => 'find');
        $data['sql'] = "UPDATE `" . $conditions['table'] . "` Rs SET ";
        $data = $this->update_process($data, $conditions, $options);
        $data = $this->update_where($data, $conditions, $options);
        $data = $this->conn($data);
        return $data;
    }

    public function update_where($data, $conditions, $options) {
        $data['sql'] .= " WHERE ";
        $it = new \ArrayIterator($conditions['where']);
        $it = new \CachingIterator($it);
        foreach ($it as $key => $value) {
            $data['sql'] .= "Rs.`" . $it->key() . "` = " . $it->current();
        }
        return $data;
    }

    public function update_process($data, $conditions, $options) {
        $it = new \ArrayIterator($conditions['update']);
        $it = new \CachingIterator($it);
        foreach ($it as $key => $value) {
            $data['sql'] .= "Rs.`" . $it->key() . "` = '" . $it->current() . "'";
            if ($it->hasNext()) {
                $data['sql'] .= ", ";
            }
        }
        return $data;
    }

    public function save($conditions, $opts = false) {
        $data = array();
        $options = array('action' => 'find');
        if (isset($opts) && !empty($opts)) {
            $options = array_merge($options, $opts);
        }
        $data = $this->describe($conditions, $data, $options);
        $data = $this->describe_process($data);
        $data = $this->save_fields($data, $conditions);
        $data = $this->save_statement($data, $conditions);
        $data = $this->save_insert($data);
        if (!isset($options['savem'])) {
            $data = $this->conn($data);
        }
        return $data;
    }

    public function save_insert($data, $options = false) {
        if (isset($data['value'])) {
            $it = $this->iterate($data['value']);
            foreach ($it as $elem) {
                if ($it->key() == 'id') {
                    $data['sql'] .= $it->current();
                } else {
                    $data['sql'] .= "'" . $it->current() . "'";
                }
                if ($it->hasNext()) {
                    $data['sql'] .= ",";
                }
            }
            $data['sql'] .= " )";
            $data[__FUNCTION__] = $data['sql'];
        }
        return $data;
    }

    public function save_statement($data, $conditions) {
        if (isset($data['fields'])) {
            $it = $this->iterate($data['fields']);
            $sql = "INSERT INTO " . $conditions['table'] . " (";
            foreach ($it as $elem) {
                $sql .= "`" . $it->current() . "`";
                if ($it->hasNext()) {
                    $sql .= ",";
                }
            }
            $sql .= ") VALUES (";
            $data['sql'] = $sql;
            $data[__FUNCTION__] = $sql;
        }
        return $data;
    }

    public function proctector($result, $value, $conditions) {
        if (isset($conditions['save'][$value]) && !empty($conditions['save'][$value])) {
            $result[$value] = $conditions['save'][$value];
        } else {
            $result[$value] = NULL;
        }
        return $result;
    }

    public function save_fields($data, $conditions) {
        if (isset($data['result'])) {
            $rs = array_column($data['result'], 'Field');
            $data['fields'] = $rs;
            $result = array();
            foreach ($rs as $key => $value) {
                if ($value == 'id') {
                    $result[$value] = 'id';
                } else {
                    $result = $this->proctector($result, $value, $conditions);
                }
            }
            $data['value'] = $result;
        }
        return $data;
    }

    public function total($conditions) {
        $data = array();
        $options = array('action' => 'find');
        $data = $this->describe($conditions, $data, $options);
        $data = $this->describe_process($data);
        $data = $this->describe_total_statement($conditions, $data);
        $data = $this->where($data, $conditions);
        $data = $this->wheres($data, $conditions);
        $data = $this->like($data, $conditions);
        $data = $this->order($data, $conditions);
        $data = $this->limit($data, $conditions);
        $data = $this->conn($data, $options);
        $data = $this->json($data, $conditions);
        return $data;
    }

    public function iterate($data) {
        $rs = new \ArrayIterator($data);
        $rs = new \CachingIterator($rs);
        return $rs;
    }

    public function field_names($data, $conditions) {
        if (isset($conditions[__FUNCTION__])) {
            $data['fields_original'] = $data['fields'];
            $data['fields'] = $conditions[__FUNCTION__];
        }
        return $data;
    }

    public function find($conditions) {
        $this->trace[] = $conditions;
        $data = array();
        $options = array('action' => 'find');
        $data = $this->describe($conditions, $data, $options);
        $data = $this->describe_process($data);
        $data = $this->field_names($data, $conditions);
        $data = $this->find_sum($data, $conditions);
        $data = $this->subquery_count($data, $conditions);
        $data = $this->find_grand_total($data, $conditions);
        $data = $this->describe_statement($conditions, $data);
        $data = $this->joins($data, $conditions);
        $data = $this->joins_many($data, $conditions);
        $data = $this->where($data, $conditions);
        $data = $this->wheres($data, $conditions);
        $data = $this->like($data, $conditions);
        $data = $this->order($data, $conditions);
        $data = $this->limit($data, $conditions);
        $data = $this->conn($data, $options);
        $data = $this->json($data, $conditions);
        return $data;
    }

    public function subquery_count($data, $conditions) {
        $key = __FUNCTION__;
        if (isset($conditions[$key])) {
            $temp_data_for_wheres = ['sql' => ''];
            $temp_data_for_wheres = $this->wheres($temp_data_for_wheres, $conditions);
            $wheres = $temp_data_for_wheres['wheres'] ?? '';
            $tbl = $conditions['table'];
            $col = $conditions[$key]['col'];
            $name = $conditions[$key]['name'];
            $sql = "SELECT COUNT(" . $tbl . ".`" . $col . "`) AS " . $name
                . " FROM " . $tbl
                . " " . $wheres;
            $sql = " ,(" . $sql . ") AS " . $name . " ";
            $data[$key] = $sql;
        }
        return $data;
    }

    public function find_sum($data, $conditions) {
        if (isset($conditions['sum'])) {
            $temp_data_for_wheres = ['sql' => ''];
            $temp_data_for_wheres = $this->wheres($temp_data_for_wheres, $conditions);
            $wheres = $temp_data_for_wheres['wheres'] ?? '';
            $tbl = $conditions['table'];
            $col = $conditions['sum']['col'];
            $name = $conditions['sum']['name'];
            $sql = "SELECT SUM( `" . $col . "`) AS "
                . $name . " FROM "
                . $tbl
                . " " . $wheres;
            $sql = " ,(" . $sql . ") AS " . $name . " ";
            $data[__FUNCTION__] = $sql;
        }
        return $data;
    }

    /**
     * Calculates the sum of a calculated column (e.g., 'total' = price * quantity)
     * and adds it as a subquery to the main SELECT statement.
     *
     * @param array $data The current data array containing the SQL query.
     * @param array $conditions An array containing the conditions, including 'grand_total' configuration.
     * @return array The updated data array with the grand total subquery.
     */
    public function find_grand_total($data, $conditions) {
        $key = __FUNCTION__;
        if (isset($conditions[$key])) {
            $grandTotalConfig = $conditions[$key];
            $temp_data_for_wheres = ['sql' => ''];
            $temp_data_for_wheres = $this->wheres($temp_data_for_wheres, $conditions);
            $wheres = $temp_data_for_wheres['wheres'] ?? '';
            $tbl = $conditions['table'];
            $priceCol = $grandTotalConfig['price_col'];
            $quantityCol = $grandTotalConfig['quantity_col'];
            $name = $grandTotalConfig['name'];
            $sql = "SELECT SUM(" . $tbl . ".`" . $priceCol . "` * " . $tbl . ".`" . $quantityCol . "`) AS " . $name
                . " FROM " . $tbl
                . " " . $wheres;
            $sql = " ,(" . $sql . ") AS " . $name . " ";
            $data[$key] = $sql;
        }
        return $data;
    }

    public function thecount($sql, $conditions) {
        $e = 'count';
        if (isset($conditions[$e])) {
            $rs = $conditions[$e];
            $counter = 0;
            while ($counter < count($rs)) {
                $m = $rs[$counter];
                $sub = ',(SELECT COUNT(' .
                    $m['where']['total'] .
                    ') FROM ' .
                    $m['tbl'] .
                    ' WHERE ' .
                    $m['where']['a'] .
                    ' = ' .
                    $m['where']['b'] .
                    ') AS ' .
                    $m['where']['as'] . '';
                $sql .= $sub;
                $counter++;
            }
        }
        return $sql;
    }

    public function joinsget($data, $conditions) {
        $e = __FUNCTION__;
        $k = 'joins';
        $tbl1 = $data[$e]['on']['tbl1'];
        $tbl2 = $data[$e]['on']['tbl2'];
        $tbl = $data[$e]['table'];
        $data[$k] .= ' ' . $data[$e]['type'];
        $data[$k] .= ' JOIN ';
        $data[$k] .= ' ' . $tbl . ' ';
        $data[$k] .= 'ON ' . $conditions['table'] .
            '.`' . $tbl1 .
            '` = ' . $tbl .
            '.`' . $tbl2 . '` ';
        return $data;
    }

    public function joins($data, $conditions) {
        if (isset($conditions[__FUNCTION__])) {
            $data[__FUNCTION__] = '';
            $debug = TRUE;
            $j = $conditions[__FUNCTION__];
            $counter = 0;
            while ($counter < count($j)) {
                $data['joinsget'] = $j[$counter];
                $data = $this->joinsget($data, $conditions);
                $counter++;
            }
            $data['sql'] .= $data[__FUNCTION__];
        }
        return $data;
    }

    /**
     * Joins multiple tables based on the 'joins_many' key in the $conditions array.
     *
     * @param array $data The current data array containing the SQL query.
     * @param array $conditions An array containing join conditions.
     * @return array The updated data array with the constructed join clauses.
     */
    public function joins_many($data, $conditions) {
        if (isset($conditions[__FUNCTION__])) {
            foreach ($conditions[__FUNCTION__] as $join) {
                $joinType = isset($join['type']) ? strtoupper($join['type']) : 'INNER';
                $joinedTable = $join['table'];
                $onConditions = $join['on'];
                $data['sql'] .= " " . $joinType . " JOIN `" . $joinedTable . "` ON ";
                $onClauses = [];
                foreach ($onConditions as $thisTableCol => $otherTableCol) {
                    $onClauses[] = $conditions['table'] . ".`" . $thisTableCol . "` = `" . $joinedTable . "`.`" . $otherTableCol . "`";
                }
                $data['sql'] .= implode(' AND ', $onClauses);
            }
        }
        return $data;
    }

    public function json($data, $conditions, $options = false) {
        $el = __FUNCTION__;
        if (isset($conditions[$el])) {
            $rs = $conditions[$el];
            foreach ($rs as $key => $val) {
                $options['key'] = $key;
                $options['val'] = $val;
                $data = $this->jsons($data, $options);
            }
        }
        return $data;
    }

    public function jsons($data, $options) {
        $rs = $data['result'];
        $counter = 0;
        while ($counter < count($rs)) {
            if (isset($rs[$counter][$options['key']])) {
                $m = json_decode($rs[$counter][$options['key']], TRUE);
                $rs[$counter][$options['val']] = $m;
            }
            $counter++;
        }
        $data['result'] = $rs;
        return $data;
    }

    public function order($data, $conditions) {
        if (isset($conditions[__FUNCTION__])) {
            $sql = ' ORDER BY ' . $conditions['table'] . '.';
            foreach ($conditions[__FUNCTION__] as $k => $v) {
                $sql .= '`' . $k . '`' . ' ' . $v;
            }
            $data['sql'] .= $sql;
        }
        return $data;
    }

    public function limit($data, $conditions) {
        if (isset($conditions['limit'])) {
            $data['sql'] .= ' LIMIT ' . $conditions['limit'] . ';';
        }
        return $data;
    }

    public function like($data, $conditions) {
        if (isset($conditions['like'])) {
            $like = "'%" . $conditions['like']['val'] . "%'";
            $where = " WHERE " . $conditions['table'] . ".`" . $conditions['like']['col'] . "` LIKE " . $like;
            $data['sql'] .= $where;
        }
        return $data;
    }

    public function or_and($data, $conditions) {
        if (isset($conditions['wheres']['or'])) {
            $it = $conditions['wheres']['or'];
            $it = new \ArrayIterator($it, 0);
            $it = new \CachingIterator($it, 0);
            $tbl = $conditions['table'];
            $where = ' OR ';
            foreach ($it as $el) {
                if ($it->hasNext()) {
                    $col = $tbl . ".`" . $it->key() . "`";
                    $counter = 0;
                    $rs = $it->current();
                    while ($counter < count($rs)) {
                        $val = $rs[$counter];
                        $where .= $col . " = " . $val . " OR ";
                        $counter++;
                    }
                } else {
                    $col = $tbl . ".`" . $it->key() . "`";
                    $counter = 0;
                    $rs = $it->current();
                    while ($counter < count($rs)) {
                        $val = $rs[$counter];
                        $where .= $col . " = " . $val . " ";
                        if ($counter < count($rs) - 1) {
                            $where .= " OR ";
                        }
                        $counter++;
                    }
                }
            }
            unset($conditions['wheres']['or']);
            $conditions['or'] = $where;
        }
        return $conditions;
    }

    public function wheres($data, $conditions) {
        $e = __FUNCTION__;
        $where = '';
        if (isset($conditions[$e])) {
            $conditions = $this->or_and($data, $conditions);
            $it = $conditions[$e];
            $it = new \ArrayIterator($it);
            $it = new \CachingIterator($it);
            $tbl = $conditions['table'];
            $where = " WHERE ";
            foreach ($it as $el) {
                if ($it->hasNext()) {
                    $where .= $tbl . ".`" . $it->key() . "` = '" . $it->current() . "' AND ";
                } else {
                    $where .= $tbl . ".`" . $it->key() . "` = '" . $it->current() . "'";
                }
            }
            $data['sql'] .= $where;
            if (isset($conditions['or'])) {
                $data['sql'] .= $conditions['or'];
            }
            $data[__FUNCTION__] = $where;
        }
        return $data;
    }

    public function where($data, $conditions) {
        if (isset($conditions['where'])) {
            $where = " WHERE " . $conditions['table'] . ".`" . $conditions['where']['col'] . "` = " . $conditions['where']['val'];
            $data['sql'] .= $where;
        }
        return $data;
    }

    public function distinct_statement($sql, $conditions, $data = false) {
        $el = 'distinct';
        if (isset($conditions[$el])) {
            $sql .= 'DISTINCT ';
        }
        return $sql;
    }

    public function distinct_fields($conditions, $data) {
        $el = 'distinct';
        if (isset($conditions[$el])) {
            $res = array($conditions[$el]);
            $rs = $data['fields'];
            $counter = 0;
            while ($counter < count($rs)) {
                $res[] = $rs[$counter];
                $counter++;
            }
            $data['fields'] = $res;
        }
        return $data;
    }

    public function describe_total_statement($conditions, $data) {
        if (isset($data['fields'])) {
            $data = $this->distinct_fields($conditions, $data);
            $it = $this->iterate($data['fields']);
            $sql = "SELECT ";
            $sql = $this->distinct_statement($sql, $conditions);
            $id = $conditions['table'] . ".`" . $data['fields'][0] . "`";
            $sql .= " count(" . $id . ") as total ";
            $sql = $this->replicate($sql, $conditions);
            $sql .= "FROM " . $conditions['table'];
            $data['sql'] = $sql;
        }
        return $data;
    }

    public function describe_statement($conditions, $data) {
        if (isset($data['fields'])) {
            $data = $this->distinct_fields($conditions, $data);
            $it = $this->iterate($data['fields']);
            $sql = "SELECT ";
            $sql = $this->distinct_statement($sql, $conditions);
            foreach ($it as $elem) {
                $sql .= $conditions['table'] . ".`" . $it->current() . "`";
                if ($it->hasNext()) {
                    $sql .= ",";
                }
            }
            if (isset($conditions['calculated_columns']['total'])) {
                $totalColumns = $conditions['calculated_columns']['total'];
                $sql .= ", (" . $conditions['table'] . ".`" . $totalColumns['price_col'] . "` * " . $conditions['table'] . ".`" . $totalColumns['quantity_col'] . "`) AS total";
            }
            $sql = $this->thecount($sql, $conditions);
            $sql = $this->joinsfields($sql, $conditions);
            $sql = $this->replicate($sql, $conditions);
            $sql = $this->find_sum_set($sql, $data);
            $sql = $this->find_subquery_count_set($sql, $data);
            $sql = $this->find_grand_total_set($sql, $data);
            $sql .= "FROM " . $conditions['table'];
            $data['sql'] = $sql;
        }
        return $data;
    }

    public function find_subquery_count_set($sql, $data) {
        $el = 'subquery_count';
        if (isset($data[$el])) {
            $sql .= $data[$el];
        }
        return $sql;
    }

    public function find_sum_set($sql, $data) {
        $el = 'find_sum';
        if (isset($data[$el])) {
            $sql .= $data[$el];
        }
        return $sql;
    }

    /**
     * Appends the grand total subquery to the main SQL statement.
     *
     * @param string $sql The current SQL query.
     * @param array $data The data array containing the grand total subquery.
     * @return string The updated SQL query.
     */
    public function find_grand_total_set($sql, $data) {
        $el = 'find_grand_total';
        if (isset($data[$el])) {
            $sql .= $data[$el];
        }
        return $sql;
    }

    public function joinsfields($sql, $conditions) {
        if (isset($conditions['joins'][0])) {
            $rs = $conditions['joins'];
            $counter = 0;
            while ($counter < count($rs)) {
                $conditions[__FUNCTION__] = $rs[$counter];
                $sql = $this->joinsfield($sql, $conditions);
                $counter++;
            }
        }
        return $sql;
    }

    public function joinsfield($sql, $conditions) {
        $arr = $conditions['joinsfields']['fields'];
        $it = new \ArrayIterator($arr);
        $it = new \CachingIterator($it);
        $tbl = $conditions['joinsfields']['table'];
        $sql .= ' ,';
        foreach ($it as $e) {
            if ($it->hasNext()) {
                $sql .= $tbl . '.`' . $it->key() . '` AS ' . $it->current() . ', ';
            } else {
                $sql .= $tbl . '.`' . $it->key() . '` AS ' . $it->current() . ' ';
            }
        }
        return $sql;
    }

    public function replicate($sql, $conditions) {
        if (isset($conditions['as'])) {
            $it = new \ArrayIterator($conditions['as']);
            $it = new \CachingIterator($it);
            $tbl = $conditions['table'];
            $sql .= ' ,';
            foreach ($it as $e) {
                if ($it->hasNext()) {
                    $sql .= $tbl . '.`' . $it->key() . '` AS ' . $it->current() . ', ';
                } else {
                    $sql .= $tbl . '.`' . $it->key() . '` AS ' . $it->current() . ' ';
                }
            }
        }
        return $sql;
    }

    public function describe_process($data) {
        if (isset($data['result'])) {
            $data['fields'] = array_column($data['result'], 'Field');
        }
        return $data;
    }

    public function describe($conditions, $data, $options) {
        $data['sql'] = "DESCRIBE " . $conditions['table'];
        $data = $this->conn($data, $options);
        return $data;
    }

    public function create_process($conditions, $data) {
        $it = $this->iterate($conditions['create']);
        $sql = "CREATE TABLE IF NOT EXISTS " . $conditions['prefix'] . $conditions['table'] . "( ";
        foreach ($it as $elem) {
            $sql .= " " . $it->key();
            $sql .= " " . $it->current();
            if ($it->hasNext()) {
                $sql .= ",";
            }
        }
        $data['sql'] = $sql . ")";
        return $data;
    }

    /**
     * Starts a database transaction.
     * @return bool True on success, false on failure.
     */
    public function beginTransaction() {
        $conn = $this->connect();
        try {
            return $conn->beginTransaction();
        } catch (PDOException $e) {
            error_log("Transaction begin failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Commits the current database transaction.
     * @return bool True on success, false on failure.
     */
    public function commit() {
        $conn = $this->connect();
        try {
            return $conn->commit();
        } catch (PDOException $e) {
            error_log("Transaction commit failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Rolls back the current database transaction.
     * @return bool True on success, false on failure.
     */
    public function rollBack() {
        $conn = $this->connect();
        try {
            return $conn->rollBack();
        } catch (PDOException $e) {
            error_log("Transaction rollback failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Checks if the database connection is in a transaction.
     *
     * @return bool True if in a transaction, false otherwise.
     */
    public function inTransaction() {
        $conn = $this->connect();
        return $conn->inTransaction();
    }
}