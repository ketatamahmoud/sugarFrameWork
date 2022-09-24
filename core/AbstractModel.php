<?php

namespace Core;

use PDO;

abstract class AbstractModel
{
    protected static $pdo;

    protected static function getPdo()
    {
        if (is_null(self::$pdo)) {
            self::$pdo = App::get('database');
        }
        return self::$pdo;
    }

    public function get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }

    /**
     * @param null $order_by
     * @param null $property
     * @return mixed
     */
    public static function getAll($order_by = null, $property = null)
    {
        $table_name = static::$object;
        $class_name = "App\Model\\" . ucfirst(static::$object);
        if ($order_by) {
            if (property_exists($class_name, $property) && in_array(strtoupper($order_by), ['DESC', 'ASC'])) {
                $sql = "SELECT * FROM $table_name ORDER BY $property $order_by";
            }
        } else {
            $sql = "SELECT * FROM $table_name";
        }

        $resultat = self::getPdo()->execute_query($sql);

        return $resultat->fetchAll(PDO::FETCH_CLASS, $class_name);
    }


    /**
     * @param $primary_value
     * @return mixed
     */
    public static function getByPrimaryKey($primary_value)
    {
        $table_name = static::$object;
        $class_name = "App\Model\\" . ucfirst(static::$object);
        $primary_key = static::$primary;

        $sql = "SELECT * from $table_name WHERE $primary_key=:searched_value";

        $params = array(
            "searched_value" => $primary_value,
        );

        $resultat = self::getPdo()->execute_query($sql, $params);

        return $resultat->fetchObject($class_name);
    }

    /**
     * @param $primary_value
     * @return mixed
     */
    public static function delete($primary_value)
    {
        $table_name = static::$object;
        $primary_key = static::$primary;

        $sql = "DELETE FROM $table_name WHERE $primary_key=:primary_value";

        $params = array(
            "primary_value" => $primary_value
        );

        return self::getPdo()->execute_query($sql, $params);

    }

    /**
     * @return mixed
     */
    public function save()
    {
        $table_name = static::$object;
        $strInsert = "";
        $strValues = "";
        $params = [];
        foreach (get_object_vars($this) as $key => $value) {
            if ($value != null) {
                $strInsert = $strInsert . "$key,";
                $strValues = "$strValues:$key,";
                $params[$key] = $value;
            }
        }

        $strInsert = rtrim($strInsert, ",");
        $strValues = rtrim($strValues, ",");

        $sql = "INSERT INTO $table_name ($strInsert) VALUES ($strValues)";

        return self::getPdo()->execute_query($sql, $params);

    }

    /**
     * @param $proprieties
     * @return mixed
     */
    public static function getBy($proprieties)
    {
        $table_name = static::$object;
        $class_name = "App\Model\\" . ucfirst(static::$object);
        $strSelect = "";
        foreach ($proprieties as $key => $value) {
            $strSelect = $strSelect . " $key=:$key and";
        }
        $strSelect = rtrim($strSelect, "and");


        $sql = "select * from $table_name where $strSelect";

        $params = $proprieties;

        $resultat = self::getPdo()->execute_query($sql, $params);
        return $resultat->fetchAll(PDO::FETCH_CLASS, $class_name);
    }

    /**
     * @return mixed
     */
    public function update()
    {
        $strSET = "";
        $table_name = static::$object;
        $primary_key = static::$primary;
        $params = [];
        $old = static::getByPrimaryKey($this->get($primary_key));
        foreach (get_object_vars($this) as $key => $value) {
            if ($old->get($key) !== $value) {
                $strSET = $strSET . "$key=:$key, ";
                $params[$key] = $value;
            }
        }
        if (!empty($params)) {
            $params[$primary_key] = $this->get($primary_key);
            $strSET = rtrim($strSET, " ,");
            $sql = "UPDATE $table_name SET $strSET WHERE $primary_key = :$primary_key";
            return self::getPdo()->execute_query($sql, $params);
        }
    }
}