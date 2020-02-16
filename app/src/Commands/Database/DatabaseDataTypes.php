<?php


namespace Framer\Commands\Database;


class DatabaseDataTypes
{

    public function determine_key_provided($key, $property)
    {
        switch ($key) {
            case "allowNull":
                return $property ? "NULL" : "NOT NULL";
                break;
            case "dataType":
                return $this->determine_datatype($property);
                break;
            case "autoincrement":
                return $property ? "AUTO_INCREMENT" : false;
                break;
            case "primaryKey":
                return $property ? "PRIMARY KEY" : null;
                break;
        }
    }

    private function determine_datatype($type)
    {
        switch($type){
            case "string":
                return "TEXT";
                break;
            case "number":
                return "INTEGER";
                break;
            case "bool":
                return "BOOLEAN";
                break;
        }
    }
}
