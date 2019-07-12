<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 27/03/2019
 * Time: 00:55
 */

namespace Core\Table;

use Core\Database\MySqlDatabase;

class Table
{
    protected $table;
    protected $db;

    public function __construct(MySqlDatabase $db)
    {
        $this->db = $db;
        if ($this->table === null) {
            $classFullName = explode('\\', get_class($this));
            $className = end($classFullName);
            $this->table = strtolower(str_replace('Table', '', $className));
        }
    }

    public function all() {
        return $this->query('SELECT * FROM ' . $this->table . ' ORDER BY id ASC;');
    }

    public function find($id)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE id = ?", [$id], true);
    }

    public function update($id, $fields)
    {
        $sqlParts = [];
        $attributes = [];
        foreach ($fields as $key => $val) {
            $sqlParts[] = "{$key} = ?";
            $attributes[] = $val;
        }
        $attributes[] = $id;
        $sqlParts = implode(', ', $sqlParts);
        return $this->query("UPDATE `{$this->table}` SET {$sqlParts} WHERE id = ?", $attributes, true);
    }

    public function delete($id)
    {
        return $this->query("DELETE FROM `{$this->table}` WHERE id = ?", [$id], true);
    }

    public function create($fields)
    {
        $subSql = [];
        $attributes = [];
        foreach ($fields as $key => $val) {
            $subSql[] = "{$key} = ?";
            $attributes[] = $val;
        }
        $subSql = implode(', ', $subSql);
        return $this->query("INSERT INTO `{$this->table}` SET {$subSql};", $attributes, true);
    }

    public function extractList($key, $value)
    {
        $records = $this->all();
        $return = [];
        foreach ($records as $record) {
            $return[$record->$key] = $record->$value;
        }
        return $return;
    }

    public function query($statement, $attributes = null, $pickOne = false)
    {
        if ($attributes) {
            return $this->db->prepare(
                $statement,
                $attributes,
                str_replace('Table','Entity', get_class($this)),
                $pickOne
            );
        } else{
            return $this->db->query(
                $statement,
                str_replace('Table','Entity', get_class($this))
            );
        }
    }
}