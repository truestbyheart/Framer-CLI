<?php

namespace Framer\Commands\Helper;

use Framer\Commands\Template\Template;

class Helper extends Template
{
    protected $framer_file = CLIROOT . "/framer.json";
    protected $path_to_migration = CLIROOT . "/app/migration";
    protected $real_path_to_controller = CLIROOT . "/app/controller";
    protected $real_path_to_view = CLIROOT . "/app/views";

    /**
     * Create a new Framer application through cloning the main repo.
     * @param $output
     * @param $name
     */
    static function get_Initial_Repository($output, $name)
    {

        # Get the initial project structure from the official github repo.
        $repo_path = 'https://github.com/truestbyheart/Framer.git';
        $command = 'git clone ' . $repo_path;
        $output->writeln([' ', '========Cloning main repository========']);
        exec($command);


        # Rename the application;
        $output->writeln([
            ' ',
            '========Renaming Main repository========',
            'Renaming Framer project -> ' . $name]);
        $command = "mv Framer " . $name;
        exec($command);


        # Remove the Git version control form the project.
        $command = "cd " . $name . " && rm -rf .git READ.md README.md";
        exec($command);

        # create and insert data to the framer.json file
        $path_to_app = CLIROOT . "/" . $name . "/framer.json";
        $framer_file = fopen($path_to_app, "w");
        fwrite($framer_file, Template::framer_json_template($name));
        fclose($framer_file);

        # Final instructions for the dev.
        $output->writeln([
            ' ',
            '========Instructions========',
            '1.Run <info>cd ' . $name . ' </info>',
            '2.Run <info>composer install</info>',
            '3.Run <info>framer start</info>']);

    }

    /**
     * Create new component, generate the controller and view template.
     * @param $output
     * @param $component
     */
    static function create_New_Component($output, $component)
    {

        if (file_exists((new Helper())->framer_file)) {
            $path = explode("/", $component);

            # Check if the component contains a folder path.
            if (sizeof($path) > 1) {
                #STEP 2-1: if folder doesn't exist generate one
                $lastIndex = sizeof($path) - 1;
                $dir = '';

                # File name variables
                $class_name = ucfirst($path[$lastIndex]);

                # Building the folder path based on the passed parameter
                foreach ($path as $key => $value) {
                    if ($key == $lastIndex) break;
                    $dir = ($dir == '') ? "/" . $path[$key] : $dir . "/" . $path[$key];
                }

                # Absolute path to put the files
                $abs_path_controller = (new Helper())->real_path_to_controller . $dir;

                # Path to the files
                $path_to_Controller_file = (new Helper())->real_path_to_controller . "/" . $class_name . ".php";
                $path_to_View_file = (new Helper())->real_path_to_view . "/" . $class_name . ".blade.php";

                # Check if controller path or view file exists.
                if (!file_exists($abs_path_controller) && !file_exists($path_to_View_file)) {

                    # Generate the file in the controller path directories.
                    if (mkdir($abs_path_controller, 0777, true)) {
                        # Generate the files.
                        (new Helper)->generate_controller_n_view_files(
                            $class_name,
                            $abs_path_controller,
                            (new Helper())->real_path_to_view,
                            $output
                        );
                    } else {
                        # inform the user to check file permission for PHP.
                        $output->writeln([
                            "Failed to generate new component.",
                            "Please check your write permission on PHP ini."]);
                    }

                } else {
                    if (file_exists($path_to_Controller_file) && file_exists($path_to_View_file)) {
                        $output->writeln([
                            "Component exists",
                            "<info>Controller</info>: " . $path_to_Controller_file,
                            "<info>View</info>: " . $path_to_View_file
                        ]);
                    } else {
                        (new Helper)->generate_controller_n_view_files(
                            $class_name,
                            $abs_path_controller,
                            (new Helper())->real_path_to_view,
                            $output
                        );
                    }
                }

            } else {
                (new Helper)->generate_controller_n_view_files(
                    ucfirst($path[0]),
                    (new Helper())->real_path_to_controller,
                    (new Helper())->real_path_to_view,
                    $output
                );
            }

        } else {
            $output->writeln([
                "<error>Framer command couldn't run.</error>",
                "<info>Possible causes:</info>",
                "1. you are running this command outside a framer project.",
                "2. You are not at the root of the project."
            ]);
        }


    }

    /**
     * Generates and insert template for the new component.
     * @param $file_name
     * @param $controller_path
     * @param $view_path
     * @param $output
     */
    private function generate_controller_n_view_files($file_name, $controller_path, $view_path, $output)
    {
        # Generate Controller file and Insert initial code.
        $controller_file = fopen($controller_path . "/" . $file_name . ".php", "w");
        fwrite($controller_file, Template::controller_template($file_name));
        fclose($controller_file);

        # Generate View file and Insert initial code.
        $view_file = fopen($view_path . "/" . $file_name . ".blade.php", "w");
        fwrite($view_file, Template::view_template());
        fclose($view_file);

        $output->writeln([
            "<info>Controller:</info>: " . $controller_path . "/" . $file_name . ".php",
            "<info>View:</info>: " . $view_path . "/" . $file_name . ".blade.php"
        ]);
    }

    /**
     * Creates a new file for the migration.
     * @param $output
     * @param $file
     */
    static function create_New_Migration_File($output, $file)
    {
        $file_name = date('YmdHi') . "-" . $file . ".json";
        $full_path_to_migrations = (new Helper)->path_to_migration . "/" . $file_name;

        if ((new Helper)->is_Framer_Project($output)) {
            if (file_exists((new Helper)->path_to_migration)) {
                fopen($full_path_to_migrations, "w");
                $output->writeln(["<info>Migration:</info>" . $full_path_to_migrations]);
            } else {
                if ((new Helper)->create_A_Folder((new Helper)->path_to_migration, $output)) {
                    $file_name = date('YmdHi') . "-" . $file . ".json";
                    $full_path_to_migrations = (new Helper)->path_to_migration . "/" . $file_name;
                    fopen($full_path_to_migrations, "w");
                    $output->writeln(["<info>Migration:</info>" . $full_path_to_migrations]);
                }
            }
        }

    }

    /**
     * Checks is the your in the framer project
     * @param $output
     * @return bool
     */
    public function is_Framer_Project($output)
    {
        if ((new Helper)->framer_file) {
            return true;
        } else {
            $output->writeln([
                "<error>Framer command couldn't run.</error>",
                "<info>Possible causes:</info>",
                "1. you are running this command outside a framer project.",
                "2. You are not at the root of the project."
            ]);
        }
    }

    /**
     * Creates folder.
     * @param $path
     * @param $output
     * @return bool
     */
    public function create_A_Folder($path, $output)
    {
        if (mkdir($path, 0777, true)) {
            return true;
        } else {
            # inform the user to check file permission for PHP.
            $output->writeln([
                "Failed to generate new component.",
                "Please check your write permission on PHP ini."]);
        }
    }
}
