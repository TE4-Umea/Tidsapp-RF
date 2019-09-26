<?php

include_once 'include/dbinfo.php';
include_once 'include/auth.php';

botRespond("Project \n
\n
/rf-projectadd [projectname] - Adds a new project identified by [projectname]. \n
\n
/rf-projectremove [projectname] - Removes a project identified by [projectname]. \n
\n
Check in / Check out \n
\n
/rf-checkin [projectname] - Checks in the user and sets [projectname] as the active project. If no [projectname] is specified, the active project will be logged as \"other\".
\n
/rf-checkout - Checks out the user and sets any active project to inactive. \n
\n
/rf-checktime [projectname] - shows how much time you have spent on a project specified by [projectname], if no project is specified, it shows how much time you have spent in the current session. \n
\n
/rf-checkactive - shows the current active project.");

// Send information back to slack
function botRespond( $output){
    echo ($output);
}

?>