#!/usr/bin/env bash
# REFER TO: https://misc.flogisoft.com/bash/tip_colors_and_formatting
#color
BOLD_BLUE="\e[1m\e[34m"
NORMAL_TEXT="\e[0m"
GREEN="\e[32m"
BOLD_RED="\e[1m\e[31m"
LIGHT_GRAY_BACKGROUND="\e[47m"


# REFER TO: https://linuxize.com/post/bash-functions/
# REFER TO: https://www.linuxjournal.com/content/return-values-bash-functions
function check_if_Command_exists {
 local result=""
 [ -x "$(command -v $1)" ] &&  result="yes" || result="no";
 echo "$result"
}

function clone_the_Framer_project {
  check_git_command="$(check_if_Command_exists "git")"
  # REFER TO: https://linuxize.com/post/how-to-compare-strings-in-bash/
  # REFER TO: https://linuxconfig.org/bash-scripting-tutorial-for-beginners#h14-numeric-and-string-comparisons
  if [[ "$check_git_command" -eq "yes" ]]; then
   cd $HOME
   rm -rf Framer-CLI
   echo " "
   echo -e "$GREEN======================================$NORMAL_TEXT"
   git clone https://github.com/truestbyheart/Framer-CLI.git
   echo -e "$GREEN======================================$NORMAL_TEXT"
     echo " "
  else
   echo " "
   echo -e "$BOLD_RED======================================"
   echo "= Please install git in your system  ="
   echo "= https://git-scm.com/downloads      ="
   echo -e "======================================$NORMAL_TEXT"
   echo " "
  fi
}

function compile_Framer_Project {
  cd Framer-CLI
  php build.script.php
}

# STEP 1: Check if php is installed
 check_command="$(check_if_Command_exists "php")"

 if [[ "$result" -eq "yes" ]]; then
   # STEP 2: Clone the repository
   clone_the_Framer_project
   # STEP 3: Compile the files to phr archive
   compile_Framer_Project
   # STEP 4: Add the framer alias to the ~/.bash or ~/.zshrc
   echo "Add the following line to your ~/.bash_profile or ~/.zshrc"
   echo " "
   echo -e "$BOLD_BLUE alias framer=\"php $HOME/Framer-CLI/Framer.phar\" $NORMAL_TEXT"
   echo " "
   echo -e "$BOLD_BLUE========================================"
   echo -e "= Thank you from installing Framer-CLI ="
   echo -e "========================================$NORMAL_TEXT"
 else
   echo "php is not installed in this system"
 fi
