<?php

namespace vendor;

use vendor\Model;

class DB extends Model
{
    // == Bagian SELECT == //

    private $selectables = array();
    private $table;
    private $whereAnd = [];
    private $whereOr = [];
    private $whereAndValue = [];
    private $whereOrValue = [];
    private $whereforUpdate_key = [];
    private $whereforUpdate_data = [];
    private $limit;
    private $is_delete_selected = null;
    private $is_select_selected = null;
    private $is_update_selected = null;
    private $delete_table = '';
    private $update_table = '';
    private $manual_sql_script = null;

    // == Bagian Update == //

    private $update_data_key = [];
    private $update_data_value = [];

    public function update()
    {
        $this->is_update_selected = true;
        return $this;
    }

    // == Bagian Manual SQL Script == //

    public function sql($kode)
    {
        $this->manual_sql_script = $kode;
        return $this;
    }

    public function run()
    {
        if($this->manual_sql_script != null) {
            if (
                    stripos($this->manual_sql_script, "SELECT") !== false   || 
                    stripos($this->manual_sql_script, "ORDER BY") !== false ||
                    stripos($this->manual_sql_script, "LIKE") !== false     ||
                    stripos($this->manual_sql_script, "BETWEEN") !== false
               ) {

                return $this->connect($this->manual_sql_script);  

            } else if(
                        stripos($this->manual_sql_script, "INSERT INTO") !== false      || 
                        stripos($this->manual_sql_script, "UPDATE") !== false           || 
                        stripos($this->manual_sql_script, "DELETE") !== false           ||
                        stripos($this->manual_sql_script, "CREATE DATABASE") !== false  || 
                        stripos($this->manual_sql_script, "DROP DATABASE") !== false    ||
                        stripos($this->manual_sql_script, "BACKUP DATABASE") !== false  || 
                        stripos($this->manual_sql_script, "CREATE TABLE") !== false     || 
                        stripos($this->manual_sql_script, "DROP TABLE") !== false       || 
                        stripos($this->manual_sql_script, "ALTER TABLE") !== false
                    ) {

                        if($this->db->query($this->manual_sql_script)) {
                            $hasil['code'] = 'success';
                            $hasil['message'] = 'Successfuly execute your sql script';
                            $hasil['query'] = $this->manual_sql_script;
                        } else {
                            $hasil['code'] = 'failed';
                            $hasil['message'] = 'Failed to execute your sql script';
                            $hasil['query'] = $this->manual_sql_script;
                        }

                return $hasil;  

            } else {

                $error['code'] = 'wrong';
                $error['message'] = 'Your SQL script are not supported with OFI PHP System, please contact teguhrijanandi02@gmail.com for report this bug';

                return $error;

            }
         }
    }

    /**
     * Notice
     * -> where('id', 2)
     * 1. id is key and store in $whereAnd or $whereOr array
     * 2. 2 is value store in $whereAndValue or $whereOrValue array
     */

    public function select() {
        $this->selectables = func_get_args();
        $this->is_select_selected = true;
        return $this;
    }

    public function from($table) {

        if($this->is_delete_selected) {
            $this->delete_table = $table;
            return $this;
        } else if($this->is_select_selected) {
            $this->table = $table;
            return $this;
        } else {
            return false;
        }
    }

    public function where($key, $value) {
        if ($this->is_select_selected) {

            array_push($this->whereAnd, $key);
            array_push($this->whereAndValue, $value);
            return $this;

        } else if($this->is_delete_selected) {

            $query = "DELETE FROM $this->delete_table WHERE $key = '$value'";

            if($this->db->query($query)) {
                $hasil['code'] = 'success';
                $hasil['query'] = $query;
            } else {
                $hasil['code'] = 'fail';
                $hasil['query'] = $query;
            }

            return $hasil;

        } else if($this->is_update_selected) {
            array_push($this->whereforUpdate_key, $key);
            array_push($this->whereforUpdate_data, $value);
            return $this;
        } else {
            return false;
        }
    }

    public function whereOr($key, $value) {
        if($this->is_select_selected) {
            // Push data ketika method ini dipanggil
            array_push($this->whereOr, $key);
            array_push($this->whereOrValue, $value);
            return $this;
        } else {
            return false;
        }
    }

    public function limit($limit) {

        if($this->is_select_selected) {
            $this->limit = $limit;
            return $this;
        } else {
            return false;
        }
    }

    public function get() {

        $query[] = "SELECT";
        // if the selectables array is empty, select all
        if (empty($this->selectables)) {
            $query[] = "*";  
        }
        // else select according to selectables
        else {
            $query[] = join(', ', $this->selectables);
        }

        $query[] = "FROM";
        $query[] = "`" . $this->table . "`";
        
        /**
         * Jika query where and tidak kosong
         */

        if (!empty($this->whereAnd)) {
            $query[] = " WHERE ";

            for ($i=0; $i < count($this->whereAnd) ; $i++) { 
                $sql .= $this->whereAnd[$i] . " = '" . $this->whereAndValue[$i] . "'" . " AND ";
            }

            $sql_explode = explode(' ', $sql);
                array_pop($sql_explode);
                array_pop($sql_explode);

            $query[] = join(' ', $query) . implode(' ', $sql_explode);
        }

        /**
         * Jika query where or tidak kosong
         */
        
        if (!empty($this->whereOr)) {
            $query[] = " WHERE ";

            for ($i=0; $i < count($this->whereOr) ; $i++) { 
                $sql .= $this->whereOr[$i] . " = '" . $this->whereOrValue[$i] . "'" . " OR ";
            }

            $sql_explode = explode(' ', $sql);
                array_pop($sql_explode);
                array_pop($sql_explode);

            $query[] = join(' ', $query) . implode(' ', $sql_explode);
        }

        
        /**
         * Jika query limit or tidak kosong
         */

        if (!empty($this->limit)) {
            $query[] = "LIMIT";
            $query[] = "'" . $this->limit . "'";
        }

        for ($i=0; $i < 5 ; $i++) { 
            array_shift($query);
        }

        if($this->is_select_selected) {
            return $this->connect(join(' ', $query));
        } else {
            return false;
        }
    }

    /**
     * Untuk mendapatkan hasil data hanya satu saja,
     * beda jika dengan get yang mendapatkan semuanya
     */

    public function first() {

        $query[] = "SELECT";
        // if the selectables array is empty, select all
        if (empty($this->selectables)) {
            $query[] = "*";  
        }
        // else select according to selectables
        else {
            $query[] = join(', ', $this->selectables);
        }

        $query[] = "FROM";
        $query[] = "`" . $this->table . "`";
        
        /**
         * Jika query where and tidak kosong
         */

        if (!empty($this->whereAnd)) {
            $query[] = " WHERE ";

            for ($i=0; $i < count($this->whereAnd) ; $i++) { 
                $sql .= $this->whereAnd[$i] . " = '" . $this->whereAndValue[$i] . "'" . " AND ";
            }

            $sql_explode = explode(' ', $sql);
                array_pop($sql_explode);
                array_pop($sql_explode);

            $query[] = join(' ', $query) . implode(' ', $sql_explode);
        }

        /**
         * Jika query where or tidak kosong
         */
        
        if (!empty($this->whereOr)) {
            $query[] = " WHERE ";

            for ($i=0; $i < count($this->whereOr) ; $i++) { 
                $sql .= $this->whereOr[$i] . " = '" . $this->whereOrValue[$i] . "'" . " OR ";
            }

            $sql_explode = explode(' ', $sql);
                array_pop($sql_explode);
                array_pop($sql_explode);

            $query[] = join(' ', $query) . implode(' ', $sql_explode);
        }

        
        /**
         * Jika query limit tidak kosong
         */

        if (!empty($this->limit)) {
            $query[] = "LIMIT";
            $query[] = "'" . $this->limit . "'";
        }

        for ($i=0; $i < 5 ; $i++) { 
            array_shift($query);
        }

        if($this->is_select_selected) {
            $hasil = $this->connect(join(' ', $query));

            return $hasil[0] ;
        } else {
            return false ;
        }
    }

    // == Bagian DELETE == //

    public function delete()
    {
        $this->is_delete_selected = true;
        return $this;
    }

    // == Bagian INSERT == //

    private $is_insert_selected = false;
    private $insert_table_name = '';
    private $insert_data = array();
    private $insert_data_key = array();

    public function insert()
    {
        $this->is_insert_selected = true;
        return $this;
    }

    public function into($table)
    {
        // Select
        if ($this->is_insert_selected) {
            $this->insert_table_name = $table;
            return $this;

            // Update
        } else if($this->is_update_selected)         {
            $this->update_table = $table;
            return $this;

        } else {
            return false;
        }
    }

    public function value($key, $data)
    {
        // Insert
        if($this->is_insert_selected) {
            array_push($this->insert_data_key, $key);
            array_push($this->insert_data, $data);
            return $this;
        
            // Update
        } else if($this->is_update_selected) {
            array_push($this->update_data_key, $key);
            array_push($this->update_data_value, $data);

            return $this;
        } else {
            return false;
        }

    }

    public function save()
    {
        if ($this->is_insert_selected) {
            
            $query = [];
            $query = "INSERT INTO $this->insert_table_name (";
            $query_key = '';
            $query_data = '';

            // Masukan key kedalam query
            for ($i=0; $i < count($this->insert_data_key) ; $i++) { 
                $query_key .= $this->insert_data_key[$i] . ', ';
            }

                $query_key_explode = explode(' ', $query_key);
                $query_key_explode[count($this->insert_data_key) - 1] = str_replace(',', '', $query_key_explode[count($this->insert_data_key) - 1]);
                $query .= join('', $query_key_explode) . ') VALUES (';

            // Masukan value kedalam query

            for ($i=0; $i < count($this->insert_data) ; $i++) { 
                $query_data .= "'" . $this->insert_data[$i] . "', ";
            }

                    $query_data_explode = explode(' ', $query_data);
                    $query_data_explode[count($query_data_explode) - 2] = str_replace(',', '', $query_data_explode[count($query_data_explode) - 2]);
                    $query .= join(' ', $query_data_explode) . ')';

                // Save ke database
                if ($this->db->query($query)) {
                    $hasil['code'] = 'success';
                    $hasil['query'] = $query;
                } else {
                    $hasil['code'] = 'fail';
                    $hasil['query'] = $query;
                }

                return $hasil;
            
        } else if($this->is_update_selected) {

            $query = [];
            $query = "UPDATE $this->update_table SET ";
            $sql_query = '';

            for ($i=0; $i < count($this->update_data_key) ; $i++) { 
                $sql_query .= $this->update_data_key[$i] . " = '" . $this->update_data_value[$i] . "' , ";
            }

            $sql_query_explode = explode(' ', $sql_query);

                array_pop($sql_query_explode);
                array_pop($sql_query_explode);

            $sql_query_join = join(' ', $sql_query_explode);
            $query .= $sql_query_join . " WHERE ";

            if(count($this->whereforUpdate_key) > 1) {
                for ($i=0; $i < count($this->whereforUpdate_key) ; $i++) { 
                    $query .= $this->whereforUpdate_key[$i] . " = '" . $this->whereforUpdate_data[$i] . "' AND ";
                }

                $query_di_explode = explode(' ', $query);

                    array_pop($query_di_explode);
                    array_pop($query_di_explode);

                $query = join(' ', $query_di_explode);

            } else {
                for ($i=0; $i < count($this->whereforUpdate_key) ; $i++) { 
                    $query .= $this->whereforUpdate_key[$i] . " = '" . $this->whereforUpdate_data[$i] . "'";
                }
            }

            if($this->db->query($query)) {
                $hasil['code'] = 'success';
                $hasil['query'] = $query;
            } else {
                $hasil['code'] = 'fail';
                $hasil['query'] = $query;
            }

            return $hasil;
            
        } else  {
            return false;
        }
    }
    
}
