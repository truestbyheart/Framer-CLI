<?php

namespace Framer\Commands\Helper;

use Framer\Commands\Template\Template;

class Helper extends Template
{

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
        $path_to_app = APPROOT."/".$name."/framer.json";
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
        # Check if we are in a framer app.
        $framer_file = APPROOT . "/framer.json";
        $real_path_to_controller = APPROOT . "/app/controller";
        $real_path_to_view = APPROOT . "/app/views";

        if (file_exists($framer_file)) {
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
                $abs_path_controller = $real_path_to_controller . $dir;

                # Path to the files
                $path_to_Controller_file = $abs_path_controller . "/" . $class_name . ".php";
                $path_to_View_file = $real_path_to_view . "/" . $class_name . ".blade.php";

                # Check if controller path or view file exists.
                if (!file_exists($abs_path_controller) && !file_exists($path_to_View_file)) {

                    # Generate the file in the controller path directories.
                    if (mkdir($abs_path_controller, 0777, true)) {
                        # Generate the files.
                        self::generate_controller_n_view_files(
                            $class_name,
                            $abs_path_controller,
                            $real_path_to_view,
                            $output
                        );
                    } else {
                        # inform the user to check file permission for PHP.
                        $output->writeln([
                            "Failed to generate new component.",
                            "Please check your write permission on PHP ini."]);
                    }

                } else {
                 if(file_exists($path_to_Controller_file) && file_exists($path_to_View_file)) {
                     $output->writeln([
                         "Component exists",
                         "<info>Controller</info>: ".$path_to_Controller_file,
                         "<info>View</info>: ".$path_to_View_file
                     ]);
                 } else {
                     self::generate_controller_n_view_files(
                         $class_name,
                         $abs_path_controller,
                         $real_path_to_view,
                         $output
                     );
                 }
                }

            } else {
                self::generate_controller_n_view_files(
                    ucfirst($path[0]),
                    $real_path_to_controller,
                    $real_path_to_view,
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
}
