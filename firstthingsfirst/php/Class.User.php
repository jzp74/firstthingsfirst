<?php

# This class represents a user and handles login/logout as well as permissions
# This class contains now actual data. Data is only stored in session params


# User defines
define("USER_TABLE_NAME", $firstthingsfirst_db_table_prefix."user");
define("USER_ID_RESET_VALUE", -1);
define("USER_NAME_RESET_VALUE", "_");


# Class definition
# TODO improve use of trace/debug logging
# TODO add user created, last login date/time
class User
{
    # error string, contains last known error
    protected $error_str;

    # reference to global json object
    protected $_json;

    # reference to global database object
    protected $_database;

    # reference to global logging object
    protected $_log;
    
    # set attributes of this object when it is constructed
    function __construct ()
    {
        # these variables are assumed to be globally available
        global $json;
        global $logging;
        global $database;
        
        # set global references for this object
        $this->_json =& $json;
        $this->_log =& $logging;
        $this->_database =& $database;
        
        # start a session
        session_cache_limiter('private, must-revalidate');
        session_start();
        
        # reset relevant session parameters
        
        if ($this->is_login())
        {
            $this->_log->debug("user session is still active (name=".$this->get_name().")");
            $this->page = ACTION_GET_PORTAL;
            $this->page_title = "-";
        }
        else
            $this->reset();
        
        $this->_log->trace("constructed new User object");
    }

    # return string representation of this object
    function __toString ()
    {
        $str = "User: id=\"".$this->get_id()."\", ";
        $str .= "name=\"".$this->get_name()."\", ";
        $str .= "times_login=\"".$this->get_times_login()."\", ";
        $str .= "edit_list=\"".$this->get_edit_list()."\", ";
        $str .= "create_list=\"".$this->get_create_list()."\", ";
        $str .= "admin=\"".$this->get_admin()."\", ";
        return $str;
    }

    # getter
    function get_error_str ()
    {
        return $this->error_str;
    }

    # getter
    function get_id ()
    {
        return $_SESSION["id"];
    }
    
    # getter
    function get_name ()
    {
        return $_SESSION["name"];
    }

    # getter
    function get_created ()
    {
        return $_SESSION["created"];
    }

    # getter
    function get_edit_list ()
    {
        return $_SESSION["edit_list"];
    }

    # getter
    function get_create_list ()
    {
        return $_SESSION["create_list"];
    }

    # getter
    function get_admin ()
    {
        return $_SESSION["admin"];
    }

    # getter
    function get_times_login ()
    {
        return $_SESSION["times_login"];
    }

    # getter
    function get_login ()
    {
        return $_SESSION["login"];
    }

    # getter
    # get settings from session and set global list_state object
    function get_list_state ($list_title)        
    {
        global $list_state;
        
        # for some reason a cast is needed here
        $list_states = (array)$this->_json->decode(html_entity_decode($_SESSION["list_states"]), ENT_QUOTES);
        
        if (array_key_exists($list_title, $list_states))
            $list_state->set($list_title, (array)$list_states[$list_title]); # mind the cast
        else
        {
            $this->_log->trace("list_state not found in session (list_title=".$list_title.")");
            
            $list_state->reset();
            $list_state->set_list_title($list_title);
        }
    }
    
    # setter
    function set_id ($id)
    {
        $_SESSION["id"] = $id;
    }
    
    # setter
    function set_name ($name)
    {
        $_SESSION["name"] = $name;
    }
    
    # setter
    function set_created ($created)
    {
        $_SESSION["created"] = $created;
    }
    
    # setter
    function set_edit_list ($permission)
    {
        $_SESSION["edit_list"] = $permission;
    }

    # setter
    function set_create_list ($permission)
    {
        $_SESSION["create_list"] = $permission;
    }

    # setter
    function set_admin ($permission)
    {
        $_SESSION["admin"] = $permission;
    }

    # setter
    function set_times_login ($times_login)
    {
        $_SESSION["times_login"] = $times_login;
    }

    # setter
    function set_login ($login)
    {
        $_SESSION["login"] = $login;
    }

    # setter
    # store values from global list state object in session 
    function set_list_state ()
    {
        global $list_state;
        
        # for some reason a cast is needed here
        $list_states = (array)$this->_json->decode(html_entity_decode($_SESSION["list_states"]), ENT_QUOTES);

        $list_states[$list_state->get_list_title()] = $list_state->pass();        
        $_SESSION["list_states"] = htmlentities($this->_json->encode($list_states), ENT_QUOTES);
    }
        
    
    # reset attributes to standard values
    function reset ()
    {
        $this->_log->trace("resetting User");

        $this->set_id(USER_ID_RESET_VALUE);
        $this->set_name(USER_NAME_RESET_VALUE);
        $this->set_times_login("0");
        $this->set_edit_list("0");
        $this->set_create_list("0");
        $this->set_admin("0");
        $this->set_login("0");
    }

    # create the database table that contains all users
    function create ()
    {
        $this->_log->trace("creating User (table=".USER_TABLE_NAME.")");
        
        $query = "CREATE TABLE ".USER_TABLE_NAME." (";
        $query .= DB_ID_FIELD_NAME." ".DB_DATATYPE_ID.", ";
        $query .= "_name ".DB_DATATYPE_USERNAME.", ";
        $query .= "_pw ".DB_DATATYPE_PASSWORD.", ";
        $query .= DB_TS_CREATED_FIELD_NAME." ".DB_DATATYPE_DATETIME.", ";
        $query .= "_edit_list ".DB_DATATYPE_BOOL.", ";
        $query .= "_create_list ".DB_DATATYPE_BOOL.", ";
        $query .= "_admin ".DB_DATATYPE_BOOL.", ";
        $query .= "_times_login ".DB_DATATYPE_INT.", ";
        $query .= "_ts_last_login ".DB_DATATYPE_DATETIME.", ";
        $query .= "PRIMARY KEY (".DB_ID_FIELD_NAME."), ";
        $query .= "UNIQUE KEY _name (_name)) ";

        $result = $this->_database->query($query);
        if ($result == FALSE)
        {
            $this->_log->error("could not create table in database for user");
            $this->_log->error("database error: ".$this->_database->get_error_str());
            $this->error_str = ERROR_DATABASE_PROBLEM;
            
            return FALSE;
        }
        
        $this->_log->trace("created table");

        return TRUE;
    }

    # check if user is really logged in
    function is_login ()
    {
        if (($this->get_login()) && ($this->get_id() != USER_ID_RESET_VALUE) && ($this->get_name() != USER_NAME_RESET_VALUE) && ($this->get_name() != ""))
            return TRUE;
        return FALSE;
    }

    # try to login specified user with specified password
    # restore user settings from database if name/password combination is valid
    function login ($name, $pw)
    {
        global $firstthingsfirst_db_passwd;
        
        $this->_log->trace("log in (name=".$name.")");
        
        if ($this->is_login())
        {
            $this->_log->warn("user already logged in (name=".$name.")");
            return FALSE;
        }
            
        # create user admin the first time user admin tries to login    
        if ($name == "admin" && !$this->exists("admin"))
        {
            $this->_log->info("first time login for admin");
            if (!$this->add($name, $pw, 1, 1, 1))
                return FALSE;
        }

        $password = md5($pw);
        $query = "SELECT ".DB_ID_FIELD_NAME.", _name, _pw, ".DB_TS_CREATED_FIELD_NAME.", _edit_list, ";
        $query .= "_create_list, _admin, _times_login, _ts_last_login FROM ".USER_TABLE_NAME." WHERE _name=\"".$name."\"";
        $result = $this->_database->query($query);
        $row = $this->_database->fetch($result);
        
        if ($row != FALSE)
        {
            $db_id = $row[0];
            $db_name = $row[1];
            $db_password = $row[2];
            $db_created = $row[3];
            $db_edit_list = $row[4];
            $db_create_list = $row[5];
            $db_admin = $row[6];
            $times_login = $row[7] + 1;
            $last_login = $row[8];
            
            # obtain admin pw from localsettings
            if ($name == "admin")
                $db_password = md5($firstthingsfirst_db_passwd);
            
            if ($db_password == $password)
            {
                # set session parameters
                $this->set_id($db_id);
                $this->set_name($db_name);
                $this->set_created($db_created);
                $this->set_edit_list($db_edit_list);
                $this->set_create_list($db_create_list);
                $this->set_admin($db_admin);
                $this->set_times_login($times_login);
                $this->set_login(1);
                
                # update the number of times this user has logged in
                $query = "UPDATE ".USER_TABLE_NAME." SET _times_login=\"".$times_login."\", _ts_last_login=\"".strftime(DB_DATETIME_FORMAT)."\" where _name=\"".$name."\"";
                $result = $this->_database->query($query);
                if ($result == FALSE)
                {
                    $this->_log->error("could not update _times_login (name=".$name.")");
                    $this->_log->error("database error: ".$this->_database->get_error_str());
                    $this->error_str = ERROR_DATABASE_PROBLEM;
                    
                    return FALSE;
                }
                else
                {
                    $this->_log->info("user logged in (name=".$name.")");
                
                    return TRUE;
                }
            }
            else
            {        
                $this->_log->warn("passwords do not match (name=".$name."), user is not logged in");
                $this->error_str = ERROR_INCORRECT_NAME_PASSWORD;
                
                return FALSE;
            }
        }
        else
        {
            $this->_log->error("could not select user (name=".$name.")");
            $this->_log->error("database error: ".$this->_database->get_error_str());
            $this->error_str = ERROR_DATABASE_PROBLEM;
                    
            return FALSE;
        }
    } 
    
    # logout current user
    function logout ()
    {
        $name = $this->get_name();
        
        $this->_log->trace("log out");
        
        $this->reset();

        $this->_log->info("user logged out (name=".$name.")");        
    }
    
    # return TRUE when user already exists
    function exists ($name)
    {
        $query = "SELECT _name FROM ".USER_TABLE_NAME." WHERE _name=\"".$name."\"";
        $result = $this->_database->query($query);
        $row = $this->_database->fetch($result);
        
        $this->_log->trace("checking if user exists (name=".$name.")");
        
        if ($row != FALSE)
        {
            if ($row[0] == $name)
            {
                $this->_log->debug("user already exists (name=".$name.")");
                
                return TRUE;
            }
            else
            {
                $this->_log->debug("user does not exist (name=".$name.")");
                
                return FALSE;
            }
        }
        else
        {
            $this->_log->error("could not select (name=".$name.")");
            $this->_log->error("database error: ".$this->_database->get_error_str());
            $this->error_str = ERROR_DATABASE_PROBLEM;
            
            return FALSE;
        }
    }                    

    # add given user with given characteristics to database
    function add ($name, $pw, $edit_list = 0, $create_list = 0, $is_admin = 0)
    {
        $password = md5($pw);

        $this->_log->trace("add user (name=".$name.")");
        
        # create table if it does not yet exists
        if (!$this->_database->table_exists(USER_TABLE_NAME))
            $this->create();
        
        # check if user already exists
        if ($this->exists($name))
        {
            $this->error_str = ERROR_DUPLICATE_USER_NAME;
            
            return FALSE;
        }

        $query = "INSERT INTO ".USER_TABLE_NAME." VALUES (0, \"".$name."\", \"".$password."\", \"".strftime(DB_DATETIME_FORMAT)."\", ";
        $query .= $edit_list.", ".$create_list.", ".$is_admin.", 0, \"".strftime(DB_DATETIME_FORMAT)."\")";
        $result = $this->_database->insertion_query($query);
        if ($result == FALSE)
        {
            $this->_log->error("could not add user (name=".$name.")");
            $this->_log->error("database error: ".$this->_database->get_error_str());
            $this->error_str = ERROR_DATABASE_PROBLEM;
            
            return FALSE;
        }
        else
        {
            $this->_log->info("user added (name=".$name.")");
        
            return TRUE;
        }
    }
}

?>
