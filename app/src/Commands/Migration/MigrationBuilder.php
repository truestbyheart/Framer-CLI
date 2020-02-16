<?php


namespace Framer\Commands\Migration;

use Framer\Commands\Database\DatabaseDataTypes;

class MigrationBuilder
{
    /**
     * MigrationBuilder constructor.
     */
    public function __construct()
    {
    }

    function get_Table_Name_Properties()
    {
        # Get the files from migration folder
        $path_to_migration = APPROOT . '/app/migration';

        # Check if exists
        if (file_exists($path_to_migration)) {
            # Get the list of migration file and check file if JSON
            $migration_file_list = glob($path_to_migration . "/*.json");

            foreach ($migration_file_list as $file) {
                # Read the json object
                $table_data = file_get_contents($file);

                # Convert it to a PHP object
                $obj = json_decode($table_data);

                # Create SQL statement
                $this->generate_SQL_Query($obj);

            }
        }

    }

    function generate_SQL_Query($obj)
    {
        $table_name = $obj->table_name;
        $property_string = "";
        $col_str = "";
        $result="";

        foreach ($obj->column as $col_name => $data_types) {
            foreach ($data_types as $key => $property) {
                $result = (new DatabaseDataTypes())->determine_key_provided($key, $property);
                echo $col_name;
                $property_string = $property_string . " " . $result;
            }
            // $col_str = $col_str . $property_string;
//            echo $property_string."\n";
//            echo $col_name." ".$property_string.",\n";
        }

        // return "CREATE TABLE " . $table_name . "(" . $col_str . ")";
    }
}
