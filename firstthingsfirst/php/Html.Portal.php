<?php

/**
 * This file contains all php code that is used to generate html for the portal page
 *
 * @package HTML_FirstThingsFirst
 * @author Jasper de Jong
 * @copyright 2008 Jasper de Jong
 * @license http://www.opensource.org/licenses/gpl-license.php
 */


/**
 * definition of 'get_portal_page' action
 */
define("ACTION_GET_PORTAL_PAGE", "get_portal_page");
$firstthingsfirst_action_description[ACTION_GET_PORTAL_PAGE] = array(PERMISSION_CANNOT_EDIT_LIST, PERMISSION_CANNOT_CREATE_LIST, PERMISSION_ISNOT_ADMIN);
$xajax->registerFunction("action_get_portal_page");

/**
 * definition of 'get_list_tables' action
 */
define("ACTION_GET_LIST_TABLES", "get_list_tables");
$firstthingsfirst_action_description[ACTION_GET_LIST_TABLES] = array(PERMISSION_CANNOT_EDIT_LIST, PERMISSION_CANNOT_CREATE_LIST, PERMISSION_ISNOT_ADMIN);
$xajax->registerFunction("action_get_list_tables");

/**
 * definition of 'del_list_table' action
 */
define("ACTION_DEL_LIST_TABLE", "del_list_table");
$firstthingsfirst_action_description[ACTION_DEL_LIST_TABLE] = array(PERMISSION_CAN_EDIT_LIST, PERMISSION_CAN_CREATE_LIST, PERMISSION_ISNOT_ADMIN);
$xajax->registerFunction("action_del_list_table");


/**
 * set the html for the portal page
 * this function is registered in xajax
 * @return xajaxResponse every xajax registered function needs to return this object
 */
function action_get_portal_page ()
{
    global $logging;
    global $result;
    global $user;
    global $response;
    global $firstthingsfirst_portal_title;
    global $firstthingsfirst_portal_intro_text;

    $logging->info("ACTION: get portal page");

    if (!check_preconditions(ACTION_GET_PORTAL_PAGE))
        return $response;
    
    $html_str = "";
    $html_str .= "\n\n        <div id=\"hidden_upper_margin\">something to fill space</div>\n\n";
    $html_str .= "        <div id=\"page_title\">".$firstthingsfirst_portal_title."</div>\n\n";
    $html_str .= "        <div id=\"portal_explanation\">".$firstthingsfirst_portal_intro_text."</div>\n\n";
    $html_str .= "        <div id=\"navigation_container\">\n";
    $html_str .= "            <div id=\"navigation\">";
    if ($user->is_login() && $user->get_is_admin())
        $html_str .= get_query_button("action=get_add_user_page", BUTTON_ADD_USER);
    $html_str .= "</div>\n";
    $html_str .= "            <div id=\"login_status\">&nbsp;</div>&nbsp;\n";
    $html_str .= "        </div> <!-- navigation_container -->\n\n";    
    $html_str .= "        <div id=\"portal_overview_pane\">\n\n";
    $html_str .= "        </div> <!-- portal_overview_pane -->\n\n";
    $html_str .= "        <div id=\"action_pane\">\n\n";
    $html_str .= "            <div id=\"action_bar\">\n";
    $html_str .= "                <p>&nbsp;".get_query_button("action=get_listbuilder_page", BUTTON_CREATE_NEW_LIST)."</p>\n";
    $html_str .= "            </div> <!-- action_bar -->\n\n";    
    $html_str .= "        </div> <!-- action_pane -->\n\n";           
    $html_str .= "        <div id=\"hidden_lower_margin\">something to fill space</div>\n\n    ";

    $result->set_result_str($html_str);    

    $response->addAssign("main_body", "innerHTML", $result->get_result_str());

    # get list tables
    action_get_list_tables();

    set_login_status();
    set_footer("&nbsp;");

    $logging->trace("got portal page");

    return $response;
}

/**
 * get the html for an overview of all ListTable objects contained in database
 * @return void
 */
function action_get_list_tables ()
{
    global $logging;
    global $result;
    global $database;
    global $response;
    global $list_table_description;
    
    $logging->info("ACTION: get list tables");

    $html_str = "";

    if (!check_preconditions(ACTION_GET_LIST_PAGES))
        return $response;

    # get all list_tables from database
    $list_table_descriptions = $list_table_description->select("", DATABASETABLE_UNKWOWN_PAGE);
    if (count($list_table_descriptions) == 0)
    {
        $result->set_error_str(ERROR_DATABASE_PROBLEM);
        $result->set_error_element("portal_overview_pane");
        # no return statement here because we want the complete page to be displayed
    }
    
    # now create the table
    $html_str .= "            <table id=\"portal_overview\" align=\"left\" border=\"0\" cellspacing=\"2\">\n";
    
    # create the table header
    $html_str .= "                <thead>\n";
    $html_str .= "                    <tr>\n";
    $html_str .= "                        <th>".LABEL_LIST_NAME."</th>\n";
    $html_str .= "                        <th>".LABEL_LIST_DESCRIPTION."</th>\n";
    $html_str .= "                        <th>".LABEL_LIST_CREATOR."</th>\n";
    $html_str .= "                        <th>".LABEL_LIST_MODIFIER."</th>\n";
    $html_str .= "                        <th>&nbsp;</th>\n";
    $html_str .= "                    </tr>\n";
    $html_str .= "                </thead>\n";
    $html_str .= "                <tbody>\n";
    
    # add table row for each list
    foreach($list_table_descriptions as $list_table_description)
    {
        $html_str .= "                    <tr>\n";
        $html_str .= "                        <td ".get_query_link("action=get_list_page&list=".$list_table_description[LISTTABLEDESCRIPTION_TITLE_FIELD_NAME]);
        $html_str .= ">".$list_table_description[LISTTABLEDESCRIPTION_TITLE_FIELD_NAME]."</td>\n";
        $html_str .= "                        <td ".get_query_link("action=get_list_page&list=".$list_table_description[LISTTABLEDESCRIPTION_TITLE_FIELD_NAME]);
        $html_str .= ">".$list_table_description[LISTTABLEDESCRIPTION_DESCRIPTION_FIELD_NAME]."</td>\n";
        $html_str .= "                        <td width=\"1%\" ".get_query_link("action=get_list_page&list=".$list_table_description[LISTTABLEDESCRIPTION_DESCRIPTION_FIELD_NAME]);
        $html_str .= "><strong>".$list_table_description[DB_CREATOR_FIELD_NAME]."</strong>&nbsp;".LABEL_AT."&nbsp;<strong>";
        $html_str .= get_date_str(DATE_FORMAT_NORMAL, $list_table_description[DB_TS_CREATED_FIELD_NAME])."</strong></td>\n";
        $html_str .= "                        <td width=\"1%\" ".get_query_link("action=get_list_page&list=".$list_table_description[LISTTABLEDESCRIPTION_TITLE_FIELD_NAME]);
        $html_str .= "><strong>".$list_table_description[DB_MODIFIER_FIELD_NAME]."</strong>&nbsp;".LABEL_AT."&nbsp;<strong>";
        $html_str .= get_date_str(DATE_FORMAT_NORMAL, $list_table_description[DB_TS_MODIFIED_FIELD_NAME])."</strong></td>\n";
        $html_str .= "                        <td width=\"1%\">".get_query_button("action=get_listbuilder_page&list=".$list_table_description[LISTTABLEDESCRIPTION_TITLE_FIELD_NAME], BUTTON_MODIFY);
        $html_str .= "&nbsp;&nbsp;".get_button_confirm("xajax_action_del_list_table('".$list_table_description[LISTTABLEDESCRIPTION_TITLE_FIELD_NAME]."')", LABEL_CONFIRM_DELETE, BUTTON_DELETE)."</td>\n";
        $html_str .= "                    </tr>\n";
    }
    if (!count($list_table_descriptions))
    {
        $html_str .= "                    <tr>\n";
        $html_str .= "                        <td>".LABEL_NONE."</td>\n";
        $html_str .= "                        <td><em>".LABEL_NO_LISTST_DEFINED."</em></td>\n";
        $html_str .= "                        <td>".LABEL_NONE."</td>\n";
        $html_str .= "                        <td>".LABEL_NONE."</td>\n";
        $html_str .= "                        <td width=\"1%\">&nbsp;</td>\n";
        $html_str .= "                    </tr>\n";
    }    
    
    $html_str .= "                </tbody>\n";
    $html_str .= "            </table>  <!-- portal_overview -->\n\n";
    
    $result->set_result_str($html_str);    

    $response->addAssign("portal_overview_pane", "innerHTML", $result->get_result_str());

    if (!check_postconditions())
        return $response;

    $logging->trace("got list_tables");

    return;
}

/**
 * delete a list table 
 * this function is registered in xajax
 * @param string $list_title title of list
 * @return xajaxResponse every xajax registered function needs to return this object
 */

function action_del_list_table ($list_title)
{
    global $logging;
    global $response;
    global $list_table_description;
    global $list_table;
    
    $logging->info("ACTION: delete list table (list_title=".$list_title.")");

    if (!check_preconditions(ACTION_DEL_LIST_TABLE))
        return $response;

    # delete the list table
    if (!$list_table_description->delete($list_title))
        set_error_message("portal_overview_pane", $list_table_description->get_error_str());
    $list_table->set($title);
    if (!$list_table->drop())
        set_error_message("portal_overview_pane", $list_table_description->get_error_str());

    action_get_list_tables();
    
    return $response;
}

?>
