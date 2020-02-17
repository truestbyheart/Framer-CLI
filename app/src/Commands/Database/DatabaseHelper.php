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
    private $stmt;
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

    public function create_table($sql, $file_name, $output)
    {
        if ($this->db_handler->query($sql) != false) {
            $output->writeln(["<info>==========" . $file_name . "==========</info>"]);
        } else {
            $output->writeln(["<error>Failed to migrate " . $file_name . "</error>",
                $this->error]);
        }
    }

    private function check_if_migrated($file_name) {
        $create_sql = "CREATE TABLE IF NOT EXISTS migration (id INT PRIMARY KEY, filename TEXT NOT NULL)";
        $select_sql = "SELECT * FROM migration";
        $insert_sql = "INSERT INTO migration (filename) VALUES(".$file_name.")";

        if($this->db_handler->query($create_sql) != false) {
            $this->db_handler->exec($insert_sql);
        }

    }
}
