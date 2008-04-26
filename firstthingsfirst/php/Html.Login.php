<?php

/**
 * This file contains all php code that is used to generate html for the login page
 *
 * @package HTML_FirstThingsFirst
 * @author Jasper de Jong
 * @copyright 2008 Jasper de Jong
 * @license http://www.opensource.org/licenses/gpl-license.php
 */


$xajax->registerFunction("action_get_login_page");
$xajax->registerFunction("action_login");
$xajax->registerFunction("action_logout");                                        


/**
 * set the html for the login page
 * this function is registered in xajax
 * @return xajaxResponse every xajax registered function needs to return this object
 */
function action_get_login_page ()
{
    global $logging;
    global $user;

    $logging->info("ACTION: get login page");
    
    # create necessary objects
    $response = new xajaxResponse();

    # set main body
    $html_str = get_login_page_html();
    $response->addAssign("main_body", "innerHTML", $html_str);

    # set footer
    set_footer("", $response);

    # set focus on user name
    $response->addScriptCall("document.getElementById('user_name').focus()");

    $logging->trace("got login page");

    return $response;
}

/**
 * get html for login page
 * @return string html for login page
 */
function get_login_page_html ()
{
    global $logging;

    $logging->trace("get login page html");

    $html_str = "";
 
    $html_str .= "\n\n        <div id=\"hidden_upper_margin\">something to fill space</div>\n\n";
    $html_str .= "        <div id=\"page_title\">".LABEL_PLEASE_LOGIN."</div>\n\n";
    $html_str .= "        <div id=\"login_pane\">\n\n";
    $html_str .= "            <form name=\"login_form_name\" id=\"login_form\" onsubmit=\"javascript:xajax_action_login(document.getElementById('user_name').value, document.getElementById('password').value); return false;\">\n";
    $html_str .= "                <table id=\"login_overview\" align=\"center\" border=\"0\" cellspacing=\"2\">\n";
    $html_str .= "                    <tbody>\n";
    $html_str .= "                        <tr>\n";
    $html_str .= "                            <td colspan=\"4\">&nbsp;<td>\n";
    $html_str .= "                        </tr>\n";
    $html_str .= "                        <tr>\n";
    $html_str .= "                            <td></td>\n";
    $html_str .= "                            <td align=\"right\">".LABEL_USER_NAME."</td>\n";
    $html_str .= "                            <td id=\"user_name_id\" align=\"left\"><input size=\"16\" maxlength=\"16\" id=\"user_name\" type=\"text\"></td>\n";
    $html_str .= "                            <td></td>\n";
    $html_str .= "                        </tr>\n";
    $html_str .= "                        <tr>\n";
    $html_str .= "                            <td></td>\n";
    $html_str .= "                            <td align=\"right\">".LABEL_PASSWORD."</td>\n";
    $html_str .= "                            <td id=\"password_id\" align=\"left\"><input size=\"16\" maxlength=\"16\" id=\"password\" type=\"password\"></td>\n";
    $html_str .= "                            <td></td>\n";
    $html_str .= "                        </tr>\n";
    $html_str .= "                        <tr>\n";
    $html_str .= "                            <td></td>\n";
    $html_str .= "                            <td align=\"right\"><input type=submit class=\"button\" value=\"".BUTTON_LOGIN."\"></td>\n";
    $html_str .= "                            <td></td>\n";
    $html_str .= "                            <td></td>\n";
    $html_str .= "                        </tr>\n";
    $html_str .= "                        <tr>\n";
    $html_str .= "                            <td colspan=\"4\">&nbsp;<td>\n";
    $html_str .= "                        </tr>\n";
    $html_str .= "                    </tbody>\n";
    $html_str .= "                </table> <!-- login_overview -->\n\n";
    $html_str .= "            </form> <!-- login_form -->\n";
    $html_str .= "        </div> <!-- login_pane -->\n\n";
    $html_str .= "        <div id=\"hidden_lower_margin\">something to fill space</div>\n\n    ";

    $logging->trace("got login page html");

    return $html_str;
}

/**
 * login a user
 * this function is registered in xajax
 * @param string $user_name name of user
 * @param string $password password for user
 * @return xajaxResponse every xajax registered function needs to return this object
 */
function action_login ($user_name, $password)
{
    global $logging;
    global $user;
    
    $logging->info("ACTION: login (user_name=".$user_name.")");

    # create necessary objects
    $response = new xajaxResponse();

    if (strlen($user_name) == 0)
    {
        $logging->warn("no user name given");
        set_error_message("user_name_id", ERROR_NO_USER_NAME_GIVEN, $response);

        return $response;
    }
    
    if (strlen($password) == 0)
    {
        $logging->warn("no password given");
        set_error_message("password_id", ERROR_NO_PASSWORD_GIVEN, $response);

        return $response;        
    }

    if ($user->login($user_name, $password))
    {    
        $logging->trace("user is logged in");

        $response->AddScriptCall("window.location.reload");
        
        return $response;
    }
    else
    {
        $logging->warn("user could not log in");
        set_error_message("password_id", $user->get_error_str(), $response);
        
        return $response;
    }
}
    
/**
 * logout a user
 * this function is registered in xajax
 * @return xajaxResponse every xajax registered function needs to return this object
 */
function action_logout ()
{
    global $logging;
    global $user;
    
    $logging->info("ACTION: logout");

    # create necessary objects
    $response = new xajaxResponse();

    $user->logout();
    set_login_status($response);

    $logging->trace("user is logged out");

    return $response;
}

/**
 * get html to display the login status of a user
 * @param $response xajaxResponse response object
 * @return string html for login status
 */
function get_login_status ()
{
    global $user;
    global $logging;
    
    $html_str = "";

    $logging->trace("getting login_status");
        
    $html_str .= LABEL_USER.": ";
    if ($user->is_login())
    {        
        $logging->debug("user: ".$user->get_name()." is logged in");
        $html_str .= $user->get_name();
        $html_str .= "&nbsp;&nbsp;".get_href("xajax_action_logout()", BUTTON_LOGOUT)."&nbsp;&nbsp;";
    }
    else
    {
        $logging->warn("no user is logged in");
        $html_str .= LABEL_MINUS;
        $html_str .= "&nbsp;&nbsp;".get_href("xajax_action_get_login_page()", BUTTON_LOGIN)."&nbsp;&nbsp;";
    }
        
    $logging->trace("got login_status");

    return $html_str;
}

/**
 * set login status
 * @return void
 */
function set_login_status ($response)
{
    global $logging;
    
    $logging->trace("setting login status");
        
    $response->addAssign("login_status", "innerHTML", get_login_status());

    $logging->trace("set login status");

    return;
}

?>
