#!/usr/bin/env bash
# REFER TO: https://misc.flogisoft.com/bash/tip_colors_and_formatting
#color
setup_color() {
	# Only use colors if connected to a terminal
	if [ -t 1 ]; then
		BOLD_RED=$(printf '\033[31m')
		GREEN=$(printf '\033[32m')
		BOLD_BLUE=$(printf '\033[34m')
		BOLD=$(printf '\033[1m')
		NORMAL_TEXT=$(printf '\033[m')
	else
		BOLD_RED=""
		GREEN=""
		BOLD_BLUE=""
		BOLD=""
		NORMAL_TEXT=""
	fi
}

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
   echo "$GREEN======================================$NORMAL_TEXT"
   PS3="Select your installation package:"
   select package in full-install phar-install;
   do
   case $package in
   full-install)
      git clone https://github.com/truestbyheart/Framer-CLI.git
      # STEP 3-opt-1: Compile the files to phar archive
      compile_Framer_Project
     break
    ;;
    phar-install)
     # STEP 3-opt-2: Download the compiled the  phar archive
     mkdir Framer-CLI
     cd Framer-CLI
     wget --no-check-certificate --content-disposition https://github.com/truestbyheart/Framer-CLI/tree/master/Framer.phar
     break
     ;;
    esac
   done
   echo "$GREEN======================================$NORMAL_TEXT"
   echo " "
  else
   echo " "
   echo "$BOLD_RED======================================"
   echo "= Please install git in your system  ="
   echo "= https://git-scm.com/downloads      ="
   echo "======================================$NORMAL_TEXT"
   echo " "
  fi
}

function compile_Framer_Project {
  cd Framer-CLI
  php build.script.php
}

# STEP 1: Check if php is installed
 setup_color
 check_command="$(check_if_Command_exists "php")"
 if [[ "$result" -eq "yes" ]]; then
   # STEP 2: Clone the repository
   clone_the_Framer_project

   # STEP 4: Add the framer alias to the ~/.bash or ~/.zshrc
   echo "Add the following line to your ~/.bash_profile or ~/.zshrc"
   echo " "
   echo "$BOLD_BLUE alias framer=\"php $HOME/Framer-CLI/Framer.phar\" $NORMAL_TEXT"
   echo " "
   echo "$BOLD_BLUE========================================"
   echo "= Thank you from installing Framer-CLI ="
   echo "========================================$NORMAL_TEXT"
 else
   echo "php is not installed in this system"
   echo "Please make sure PHP is installed and can be accessed globally"
 fi
