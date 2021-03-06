<?php

/**
 * This file contains the class definition of Database
 *
 * @package Class_FirstThingsFirst
 * @author Jasper de Jong
 * @copyright 2007-2009 Jasper de Jong
 * @license http://www.opensource.org/licenses/gpl-license.php
 */


/**
 * Make sure firstthingsfirst_db_table_prefix ends with a '_' char
 */
if ((substr($firstthingsfirst_db_table_prefix, -1, 1) != "_") && (strlen($firstthingsfirst_db_table_prefix) > 0))
    $firstthingsfirst_db_table_prefix = $firstthingsfirst_db_table_prefix."_";


/**
 * This class takes care of all database access
 *
 * @package Class_FirstThingsFirst
 */
class Database
{
    /**
    * name of host
    * @var string
    */
    protected $host;
    
    /**
    * name of user
    * @var string
    */
    protected $user;
    
    /**
    * the password
    * @var string
    */
    protected $passwd;
    
    /**
    * name of database
    * @var string
    */
    protected $database;
    
    /**
    * error string, contains last known error
    * @var string
    */
    protected $error_str;
    
    /**
    * reference to global logging object
    * @var Logging
    */
    protected $_log;
    
    /**
    * overwrite __construct() function
    * @return void
    */
    function __construct ()
    {
        # variable $logging is assumed to be globally available
        global $logging;
    
        # set global references for this object
        $this->_log = $logging;

        # globals defined in localsettings.php
        global $firstthingsfirst_db_host;
        global $firstthingsfirst_db_user;
        global $firstthingsfirst_db_passwd;
        global $firstthingsfirst_db_schema;

        # set attributes to standard values
        $this->host =& $firstthingsfirst_db_host;
        $this->user =& $firstthingsfirst_db_user;
        $this->passwd =& $firstthingsfirst_db_passwd;
        $this->database =& $firstthingsfirst_db_schema;
        $this->error_str = "";

        $this->_log->debug("constructed new Database object");        
    }

    /**
    * get value of error_str attribute
    * @return string value of error_str attribute
    */
    function get_error_str ()
    {
        return $this->error_str;
    }

    /**
    * open database connection, select database and return link identifier or FALSE
    * @return resource|bool link identifier or FALSE in case of any error
    */
    function connect ()
    {    
        $this->_log->trace("opening db connection (host=".$this->host.", user=".$this->user.")");

        $db_link = @mysql_connect($this->host, $this->user, $this->passwd);
        if (!$db_link)
        {
            $this->error_str = "unable to connect to database (host=".$this->host.", user=".$this->user.")";
            $this->_log->error($this->error_str);
            
            return FALSE;
        }
        $succes = mysql_select_db($this->database, $db_link);
        if (!$succes)
        {
            $this->error_str = "unable to select database (db=".$this->database.")";
            $this->_log->error($this->error_str);
            
            return FALSE;
        }
    
        return $db_link;
    }

    /**
    * query database and return result
    * @param string $query database query
    * @return resource|bool result of query or FALSE in case of any error
    */
    function query ($query)
    {   
        $db_link = $this->connect();
        if (!$db_link)
            return FALSE;
    
        $this->_log->debug("query database (query=".$query.")");
    
        $result = mysql_query($query, $db_link);
        $this->error_str = mysql_error($db_link);
        mysql_close($db_link);
    
        return $result;
    }
    
    /**
    * perform insertion query and return the resulting id
    * @param string $query database insertion query
    * @return int|bool insertion identifier or FALSE in case of any error
    */
    function insertion_query ($query)
    {
        $db_link = $this->connect();
        if (!$db_link)
            return FALSE;
     
        $this->_log->debug("insertion query (query=".$query.")");
    
        $result = mysql_query($query, $db_link);
        
        if ($result != FALSE)
        {
            $result = mysql_insert_id($db_link);
            $this->_log->debug("insert (id=".$result.")");
        }
        
        $this->error_str = mysql_error($db_link);
        mysql_close($db_link);
    
        return $result;
    }
    
    /**
    * get next row from result of last query
    * be sure to call the query function or the insertion_query function first
    * @todo sometimes this function yields a non empty array when nothing is found (how come???)
    * @param resource $result result of query or insertion_query function call
    * @return array|bool array containing one table row or FALSE in case of any error
    */
    function fetch ($result)
    {
        if ($result != FALSE)
        {
            $row = mysql_fetch_array($result);
            if (count($row) == 0 || (count($row) == 1 && count($row[0]) == 0))
                return array();
            return $row;
        }
        else
            return FALSE;
    }
    
    /**
    * check if given table exists in database
    * @param string $table name of table
    * @return bool indicates if given table exists in database
    */
    function table_exists ($table)
    {
        $query = "SHOW TABLES";

        $this->_log->debug("check if table exists (table=".$table.")");

        $result = $this->query($query);
        
        while ($row = $this->fetch($result))
        {
            if ($row[0] == $table)
                return TRUE;
        }
        
        return FALSE;
    }
}

?>
