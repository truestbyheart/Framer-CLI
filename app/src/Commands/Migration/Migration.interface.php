<?php

interface Migration {
     function generate_SQL_Query();
     function get_Table_Name_Properties();
}
