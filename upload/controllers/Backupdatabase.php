<?php
class Backupdatabase extends Controller
{

    public function __construct()
    {
        // $this->userModel = $this->model('User');
    }

    public function index()
    {
        dbconn();
        global $config;
        // CHECK THE ADMIN PRIVILEGES
        if (!$_SESSION['loggedin'] == true || $_SESSION["control_panel"] != "yes") {
            show_error_msg(T_("ERROR"), T_("SORRY_NO_RIGHTS_TO_ACCESS"), 1);
        }

        $DBH = DB::instance();

        //put table names you want backed up in this array.
        //leave empty to do all
        $tables = array();

        backup_tables($DBH, $tables);

    }
}
