<?php

namespace Framer\Commands\Database;

use PDO;
use PDOException;

require_once CLIROOT . "/app/config/config.php";

class DatabaseHelper
{
    private $dbname = DB_NAME;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $host = DB_HOST;


    private $db_handler;
    private $error;

    /**
     * DatabaseHelper constructor.
     */
    public function __construct()
    {
        $dsn = "mysql:dbname=" . $this->dbname . ";host=" . $this->host;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        try {
            $this->db_handler = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    public function check_if_migrated($sql, $file_name, $output)
    {
        $create_sql = "CREATE TABLE migration (id INT PRIMARY KEY AUTO_INCREMENT, filename TEXT NOT NULL)";

        # Check if the table is generated.
        try {
            $this->db_handler->query($create_sql);
        } catch (PDOException $exception) {
            echo $sql."\n";
            if (!$this->check_if_filename_exists($file_name, $output)) {
                $is_created = $this->create_table($sql, $file_name, $output);
                $is_created ?
                    $this->insert_filename($file_name, $output) :
                    $output->writeln([
                        $file_name." is not migrated to Database.",
                        "Please read the migration documentation."
                    ]);
            }

        }

    }

    private function check_if_filename_exists($file_name, $output)
    {
        $select_sql = "SELECT * FROM migration WHERE filename=:file";
        try {
            $sql_stmt = $this->db_handler->prepare($select_sql);
            $sql_stmt->bindParam(":file", $file_name);
            $sql_stmt->execute();
            $value = $sql_stmt->fetch(PDO::FETCH_ASSOC);

            return gettype($value) === "array" ? true : false;
        } catch (PDOException $exception) {
            $output->writeln($exception->getMessage());
        }
    }

    public function create_table($sql, $file_name, $output)
    {
        try {
            $this->db_handler->query($sql);
            $output->writeln(["<info>==========" . $file_name . "==========</info>"]);
            return true;
        } catch (PDOException $exception) {
            $output->writeln(["<error>Failed to migrate " . $file_name . "</error>",
                $exception->getMessage()]);
        }
    }

    private function insert_filename($file_name, $output)
    {
        $insert_sql = "INSERT INTO migration (filename) VALUES(:filename)";
        try {
            $sql_stmt = $this->db_handler->prepare($insert_sql);
            $sql_stmt->bindParam(":filename", $file_name);
            $sql_stmt->execute();
        } catch (PDOException $exception) {
            $output->writeln($exception->getMessage());
        }
    }
}
