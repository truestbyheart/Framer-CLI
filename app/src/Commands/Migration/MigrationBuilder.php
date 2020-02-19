<?php

namespace Framer\Commands\Migration;

use Framer\Commands\Database\DatabaseDataTypes;
use Framer\Commands\Database\DatabaseHelper;

class MigrationBuilder
{

    public function get_Table_Name_Properties($output)
    {
        # Get the files from migration folder
        $path_to_migration = CLIROOT . '/app/migration';

        # Check if exists
        if (file_exists($path_to_migration)) {
            # Get the list of migration file and check file if JSON
            $migration_file_list = glob($path_to_migration . "/*.json");

            foreach ($migration_file_list as $file) {
                # Read the json object
                $table_data = file_get_contents($file);

                # Get filename from the path
                $part_of_file_path = explode("/", $file);
                $file_name = $part_of_file_path[count($part_of_file_path) - 1];

                # Convert it to a PHP object
                $obj = json_decode($table_data, true);

                # determine operation to be performed
                if (isset($obj["column"])) {
                    # Create SQL statement
                    $sql = $this->create_table_SQL($obj);
                } elseif (isset($obj["add_column"])) {
                    $sql = $this->add_column($obj);
                } elseif (isset($obj["remove_column"])) {
                    $sql = $this->remove_column($obj);
                }

                # Build the tables
                (new DatabaseHelper())->check_if_migrated($sql, $file_name, $output);

            }
        }

    }

    public function create_table_SQL($obj)
    {
        $table_name = $obj["table_name"];
        $property_string = "";
        $col_str = "";
        $num_col = count($obj["column"], COUNT_NORMAL);
        $col_index = 0;
        $sql = "CREATE TABLE " . $table_name . "(";

        foreach ($obj["column"] as $col_name => $data_types) {
            foreach ($data_types as $key => $property) {
                $result = (new DatabaseDataTypes())->determine_key_provided($key, $property);
                $property_string = $property_string . " " . $result;
            }
            # Check next column
            $col_index++;

            # Creating the column string and its datatype
            $col_str = $col_str . $property_string;
            $col_name . " " . $col_str . ",";

            # Removes the , from column string.
            $sql = $col_index == $num_col ? $sql . $col_name . " " . $col_str : $sql . $col_name . " " . $col_str . ",";
            $col_str = "";
            $property_string = "";
        }

        # Complete the SQL query and close it
        return $sql . ")";
    }


    public function add_column($obj)
    {
        $table_name = $obj["table_name"];
        $property_string = "";
        $col_str = "";
        $num_col = count($obj["add_column"], COUNT_NORMAL);
        $col_index = 0;
        $sql = "ALTER TABLE " . $table_name . " ";

        foreach ($obj["add_column"] as $col_name => $data_types) {
            foreach ($data_types as $key => $property) {
                $result = (new DatabaseDataTypes())->determine_key_provided($key, $property);
                $property_string = $property_string . " " . $result;
            }

            # Check next column
            $col_index++;

            # Creating the column string and its datatype
            $col_str = $col_str . "ADD COLUMN  " . $col_name . " " . $property_string;

            # Removes the , from column string.
            $sql = $col_index == $num_col ?
                $sql . $col_str :
                $sql . " " . $col_str . ",";

            $col_str = "";
            $property_string = "";
        }

        # Complete the SQL query and close it
        return $sql;
    }

    public function remove_column($obj)
    {
        $table_name = $obj["table_name"];
        $col_str = "";
        $num_col = count($obj["remove_column"], COUNT_NORMAL);
        $col_index = 0;
        $sql = "ALTER TABLE " . $table_name . " ";

        foreach ($obj["remove_column"] as $col_name) {
            # Check next column
            $col_index++;

            # Creating the column string and its datatype
            $col_str = $col_str . "DROP COLUMN  " . $col_name . " ";

            # Removes the , from column string.
            $sql = $col_index == $num_col ?
                $sql . $col_str :
                $sql . " " . $col_str . ",";

            $col_str = "";
        }

        # Complete the SQL query and close it
        return $sql;
    }

    function update_column($obj)
    {

    }
}
