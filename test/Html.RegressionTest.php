<?php

/**
 * This file defines functions that are needed for a regression test
 *
 * @package Class_FirstThingsFirst
 * @author Jasper de Jong
 * @copyright 2008 Jasper de Jong
 * @license http://www.opensource.org/licenses/gpl-license.php
 */


/**
 * definition of regression test description
 */
define("REGRESSION_TEST_DESCRIPTION", 0);

/**
 * definition of regression test function name
 */
define("REGRESSION_TEST_FUNCTION_NAME", 1);

/**
 * definition of regression test passed text
 */
define("REGRESSION_TEST_FUNCTION_PASSED_TEXT", 2);

/**
 * definition of regressio test error text
 */
define("REGRESSION_TEST_FUNCTION_ERROR_TEXT", 3);

/**
 * definition of header function name
 */
define("REGRESSION_TEST_FUNCTION_NAME_STR", "HEADER");


/**
 * make sure you create a global variable $regression_test_functions somewhere
 */
#$regression_test_functions = array();


/**
 * start regression testing
 * this function is registered in xajax
 * @return xajaxResponse every xajax registered function needs to return this object
 */
function start_regression_test ()
{
    global $logging;
    global $firstthingsfirst_date_string;
    global $regression_test_functions;

    $logging->info("REGRESSIONTEST: start");

    # create necessary objects
    $response = new xajaxResponse();
    $html_str = "";

    # create html for page layout
    $html_str .= "\n\n            <div id=\"test_body\">\n\n";
    $html_str .= "            </div> <!-- test_body -->\n\n";

    $response->assign("main_body", "innerHTML", $html_str);
    $response->assign("page_title", "innerHTML", "Regression test");

    # create html for footer
    $html_str = "test start: ";
    $html_str .= "<strong>".strftime(DATETIME_FORMAT_EU)."</strong>, test end: ";
    if ($firstthingsfirst_date_string == DATE_FORMAT_US)
        $html_str .= "<strong>".strftime(DATETIME_FORMAT_US)."</strong>, test end: ";

    $response->assign("footer_text", "innerHTML", $html_str);
    $response->script("handleFunction('prepare_test', 0)");

    $logging->trace("started regression testing");

    return $response;
}

/**
 * prepare a test function (display test description only)
 * this function is registered in xajax
 * @param int $test_function_number number that denotes test function in regression_test_functions
 * @return xajaxResponse every xajax registered function needs to return this object
 */
function prepare_test ($test_function_number)
{
    global $logging;
    global $regression_test_functions;

    $test_function_name = $regression_test_functions[$test_function_number][REGRESSION_TEST_FUNCTION_NAME];
    if (strlen($test_function_name) == 0)
        $test_function_name = REGRESSION_TEST_FUNCTION_NAME_STR;
    $logging->info("REGRESSIONTEST: prepare test (name=$test_function_name)");

    # create necessary objects
    $response = new xajaxResponse();

    $html_str = "";
    $num_of_test_functions = count($regression_test_functions);
    $test_function_description = $regression_test_functions[$test_function_number][REGRESSION_TEST_DESCRIPTION];

    $html_str .= "\n                        <div id=\"test_item_$test_function_number\" class=\"test_item\">\n";
    if ($test_function_name != REGRESSION_TEST_FUNCTION_NAME_STR)
    {
        $html_str .= "                            <div class=\"test_item_description\">$test_function_description</div>\n";
        $html_str .= "                            <div class=\"test_item_busy\">busy</div>\n";
    }
    else
        $html_str .= "                            <div class=\"test_item_header\">$test_function_description</div>\n";
    $html_str .= "                        </div>";

    $response->append("test_body", "innerHTML", $html_str);
    $response->script("handleFunction('execute_test', $test_function_number)");

    $logging->trace("done preparing test");

    return $response;
}

/**
 * execute one test function
 * this function is registered in xajax
 * @param int $test_function_number number that denotes test function in regression_test_functions
 * @return xajaxResponse every xajax registered function needs to return this object
 */
function execute_test ($test_function_number)
{
    global $logging;
    global $regression_test_functions;

    $test_function_name = $regression_test_functions[$test_function_number][REGRESSION_TEST_FUNCTION_NAME];
    if (strlen($test_function_name) == 0)
        $test_function_name = REGRESSION_TEST_FUNCTION_NAME_STR;
    $logging->info("REGRESSIONTEST: execute test (name=$test_function_name)");

    # create necessary objects
    $response = new xajaxResponse();

    $html_str = "";
    $num_of_test_functions = count($regression_test_functions);
    $test_function_description = $regression_test_functions[$test_function_number][REGRESSION_TEST_DESCRIPTION];
    $test_function_passed_text = $regression_test_functions[$test_function_number][REGRESSION_TEST_FUNCTION_PASSED_TEXT];
    $test_function_error_text = $regression_test_functions[$test_function_number][REGRESSION_TEST_FUNCTION_ERROR_TEXT];

    $result = TRUE;
    if ($test_function_name != REGRESSION_TEST_FUNCTION_NAME_STR)
        $result = $test_function_name();

    if ($result == TRUE)
    {
        $logging->debug("test execution returned TRUE");

        if ($test_function_name != REGRESSION_TEST_FUNCTION_NAME_STR)
        {
            $html_str .= "\n                            <div class=\"test_item_description\">$test_function_description</div>\n";
            $html_str .= "                            <div class=\"test_item_successful\">$test_function_passed_text</div>\n";

            $response->assign("test_item_$test_function_number", "innerHTML", $html_str);
        }

        # check if this was the last test
        if ($test_function_number == ($num_of_test_functions - 1))
            $response->script("handleFunction('end_regression_test', 1)");
        else
            $response->script("handleFunction('prepare_test', ".($test_function_number + 1).")");
    }
    else
    {
        $logging->debug("test execution returned FALSE");

        $html_str .= "\n                            <div class=\"test_item_description\">$test_function_description</div>\n";
        $html_str .= "                            <div class=\"test_item_unsuccessful\">$test_function_error_text</div>\n";

        $response->assign("test_item_$test_function_number", "innerHTML", $html_str);
        $response->script("handleFunction('end_regression_test', 0)");
    }

    $logging->trace("done executing test");

    return $response;
}

/**
 * end regression testing
 * this function is registered in xajax
 * @param int $successful indicates if regression test was successful
 * @return xajaxResponse every xajax registered function needs to return this object
 */
function end_regression_test ($successful)
{
    global $logging;
    global $firstthingsfirst_date_string;

    $logging->info("REGRESSIONTEST: end regression test (successful=$successful)");

    # create necessary objects
    $response = new xajaxResponse();

    $html_str = "";

    if ($successful == 1)
    {
        # create html for text
        $html_str .= "\n            <div id=\"test_end_white_space\">&nbsp;</div>\n";
        $html_str .= "            <div id=\"test_end_successful\">";
        $html_str .= "Congratulations! Regression test was successful";
        $html_str .= "</div> <!-- test_end_successful -->\n\n        ";

        $response->append("test_body", "innerHTML", $html_str);
    }
    else
    {
        # create html for error text
        $html_str .= "\n            <div id=\"test_end_white_space\">&nbsp;</div>\n";
        $html_str .= "\n            <div id=\"test_end_unsuccessful\">";
        $html_str .= "Regression test was unsuccessful";
        $html_str .= "<div> <!-- test_end_unsuccessful -->\n\n        ";

        $response->append("test_body", "innerHTML", $html_str);
    }

    # append end date to footer
    $html_str = "<strong>".strftime(DATETIME_FORMAT_EU)."</strong>";
    if ($firstthingsfirst_date_string == DATE_FORMAT_US)
        $html_str = "<strong>".strftime(DATETIME_FORMAT_US)."</strong>";
    $response->append("footer_text", "innerHTML", $html_str);

    # highlight footer
    $response->script("document.getElementById('focus_on_this_input').blur()");
    $response->script("document.getElementById('focus_on_this_input').focus()");

    $logging->trace("done ending");

    return $response;
}

?>