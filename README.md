# Framer CLI
Framer CLI is the official set of command line tools that can be used to work with the Framer Framework. 

**The CLI helps the user to**
 * Easily generate new project.
 * Generate new components/pages.
 
# Installation

**Requirements**
 
Make sure the following are installed on your system

 1. Make sure PHP is installed and can be accessed globally.
  
      **Window users**
      
      * [Win 7 guide](https://john-dugan.com/add-php-windows-path-variable/)
      * [Win 10 guide](https://www.forevolve.com/en/articles/2016/10/27/how-to-add-your-php-runtime-directory-to-your-windows-10-path-environment-variable/)
      
      **MacOs users**
      
      * [Using homebrew](https://thewebtier.com/php/installing-php-7-2-osx-homebrew/) 

 2. Git is installed in your system.
  
      * [Get git here](https://git-scm.com/downloads)
  
 3. Composer is installed in your system environment
     * [Get started with composer](https://getcomposer.org/doc/00-intro.md)
     
 4. Make sure the git bash has `curl` or `wget` installed
  
### For MacOs users
To install the CLI o nyour system run one of the following command based on your preferences

**via curl**

```bash
sudo sh -c "$(curl -fsSL https://raw.githubusercontent.com/truestbyheart/Framer-CLI/master/install.sh)"
```

**via wget**

```bash
sudo sh -c "$(wget -O- https://raw.githubusercontent.com/truestbyheart/Framer-CLI/master/install.sh)"
```

Once the script has finished, make sure you add the following line to your shell configuration file.

```text
alias framer="php $HOME/Framer-CLI/Framer.phar" 
```

**NB**: $HOME is optional because its full path will be displayed once  the script has executed successfully.

#### Types of Installation

1. Full-install
  
  - This type of installation, clones the whole repository. it is a contributors choice where as you can modify and rebuild the app as you wish. But Please, share the love.
  
  **Note** In order to build the CLI you need to turn off the Phar readonly in your `php.ini` file.
  

2. phar-install
  
  - This installation downloads the build phar file from the repository. it is only for developer that only wish to use the CLI.

## How to build a new Framer app

Once the installation is complete and the path is added to the shell configuration files i.e `~/.zshrc` or `~/.bash_profile` you can create a new project
by running the following command on your terminal/CMD

```bash
framer generate new name_of_your_project
```

**For example**

```bash
framer generate new todo-app
```
## Generating new component
Make sure you run the command on root of the project.

```bash
framer generate component name_of_component
```

**For example**

```bash
framer generate component home
```

or

```bash
framer generate component components/home/home
```
