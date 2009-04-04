<?php

/**
 * This file contains translation of text labels in Dutch
 *
 * @package Text_FirstThingsFirst
 * @author Jasper de Jong
 * @copyright 2007-2009 Jasper de Jong
 * @license http://www.opensource.org/licenses/gpl-license.php
 */


/**
 * shared labels
 */
$text_translations["LABEL_YES"] = "ja";
$text_translations["LABEL_NO"] = "nee";
$text_translations["LABEL_NAVIGATION_LIST"] = "lijst";
$text_translations["LABEL_ADDED_TO_LOG_FILE"] = "toegevoegd aan de log"; 
$text_translations["LABEL_DATABASE_MESSAGE"] = "bericht van database"; 
$text_translations["LABEL_SUNDAY"] = "zo"; 
$text_translations["LABEL_MONDAY"] = "ma"; 
$text_translations["LABEL_TUESDAY"] = "di"; 
$text_translations["LABEL_WEDNESDAY"] = "wo"; 
$text_translations["LABEL_THURSDAY"] = "do"; 
$text_translations["LABEL_FRIDAY"] = "vr"; 
$text_translations["LABEL_SATERDAY"] = "za"; 
$text_translations["LABEL_MINUS"] = "-";
$text_translations["LABEL_RECORDS"] = "records";
$text_translations["LABEL_CREATED_BY"] = "aangemaakt door";
$text_translations["LABEL_AT"] = "op";
$text_translations["LABEL_LAST_MODIFICATION_BY"] = "laatste wijziging door";
$text_translations["LABEL_USER_NAME"] = "naam";
$text_translations["LABEL_DEFINITION_AUTO_NUMBER"] = "nummer (automatisch)&nbsp;&nbsp;";
$text_translations["LABEL_DEFINITION_DATE"] = "datum&nbsp;&nbsp;";
$text_translations["LABEL_DEFINITION_AUTO_CREATED"] = "datum van toevoegen (automatisch)&nbsp;&nbsp;";
$text_translations["LABEL_DEFINITION_AUTO_MODIFIED"] = "datum van wijziging (automatisch)&nbsp;&nbsp;";
$text_translations["LABEL_DEFINITION_TEXT_LINE"] = "regel met tekst&nbsp;&nbsp;";
$text_translations["LABEL_DEFINITION_TEXT_FIELD"] = "veld met meerdere regels tekst&nbsp;&nbsp;";
$text_translations["LABEL_DEFINITION_NOTES_FIELD"] = "opmerkingen&nbsp;&nbsp;";
$text_translations["LABEL_DEFINITION_SELECTION"] = "selectie veld&nbsp;&nbsp;";

/**
 * user administration labels
 */
$text_translations["LABEL_USER_ADMIN_TITLE"] = "Gebruikersadministratie";
$text_translations["LABEL_USER_PW"] = "wachtwoord";
$text_translations["LABEL_USER_LANG"] = "voorkeurs taal";
$text_translations["LABEL_USER_LANG_EN"] = "Engels";
$text_translations["LABEL_USER_LANG_NL"] = "Nederlands";
$text_translations["LABEL_USER_CAN_EDIT_LIST"] = "mag lijsten wijzigen";
$text_translations["LABEL_USER_CAN_CREATE_LIST"] = "mag lijsten maken";
$text_translations["LABEL_USER_IS_ADMIN"] = "is administrator";
$text_translations["LABEL_USER_TIMES_LOGIN"] = "# login";
$text_translations["LABEL_USER_ADMIN_RECORD"] = "gebruiker";

/**
 * user listtable permissions labels
 */
$text_translations["LABEL_USERLISTTABLEPERMISSIONS_TITLE"] = "Lijsttoegang voor gebruikers";
$text_translations["LABEL_USERLISTTABLEPERMISSIONS_LISTTABLE_TITLE"] = "lijstnaam";
$text_translations["LABEL_USERLISTTABLEPERMISSIONS_USER_NAME"] = "gebruikersnaam";
$text_translations["LABEL_USERLISTTABLEPERMISSIONS_CAN_VIEW_LIST"] = "mag deze lijst bekijken";
$text_translations["LABEL_USERLISTTABLEPERMISSIONS_CAN_EDIT_LIST"] = "mag deze lijst wijzigen";
$text_translations["LABEL_USERLISTTABLEPERMISSIONS_IS_AMDIN"] = "is administrator van deze lijst";
$text_translations["LABEL_USERLISTTABLEPERMISSIONS_RECORD"] = "lijsttoegang";

/**
 * listtable labels
 */
$text_translations["LABEL_DISPLAY"] = "toon";
$text_translations["LABEL_ARCHIVE"] = "archiveren";
$text_translations["LABEL_FILTER"] = "filter";
$text_translations["LABEL_NORMAL_RECORDS"] = "normale rijen&nbsp;&nbsp;";
$text_translations["LABEL_ARCHIVED_RECORDS"] = "gearchiveerde rijen&nbsp;&nbsp;";
$text_translations["LABEL_LIST_RECORD"] = "rij";
$text_translations["LABEL_PAGE"] = "pagina";
$text_translations["LABEL_OF"] = "van";
$text_translations["LABEL_ADD_RECORD"] = "Voer gegevens in en klik op 'ok'";
$text_translations["LABEL_EDIT_RECORD"] = "Voer wijzigingen in en klik op 'ok'";

/**
 * listtableitemnote labels
 */
$text_translations["LABEL_NEW_NOTE"] = "nieuwe opmerking";

/**
 * listbuilder labels
 */
$text_translations["LABEL_CONFIGURE_NEW_LIST"] = "Ontwerp een nieuwe lijst";
$text_translations["LABEL_MODIFY_LIST"] = "Wijzig bestaande lijst";
$text_translations["LABEL_GENERAL_SETTINGS"] = "Algemene gegegevens";
$text_translations["LABEL_TITLE_OF_THIS_LIST"] = "Naam van lijst";
$text_translations["LABEL_SHORT_DESCRIPTION_OF_THIS_LIST"] = "Omschrijving van lijst";
$text_translations["LABEL_DEFINE_TABLE_FIELDS"] = "Lijst kolommen";
$text_translations["LABEL_FIELDTYPE"] = "Kolomtype";
$text_translations["LABEL_FIELDNAME"] = "Kolomnaam";
$text_translations["LABEL_OPTIONS"] = "Opties";
$text_translations["LABEL_DEFINITION_AUTO_NUMBER_EXPLANATION"] = "Deze kolom bevat een uniek nummer voor elke rij in je lijst. Geef bij opties aan of je deze kolom wilt tonen in je lijst";
$text_translations["LABEL_DEFINITION_DATE_EXPLANATION"] = "De gebruiker kan zelf een datum in dit veld invoeren";
$text_translations["LABEL_DEFINITION_AUTO_CREATED_EXPLANATION"] = "Geef aan of deze kolom de naam van de gebruiker die de rij heeft aangemaakt en de datum waarop deze rij is aangemaakt toont, of een van beiden";
$text_translations["LABEL_DEFINITION_AUTO_MODIFIED_EXPLANATION"] = "Geef aan of deze kolom de naam van de gebruiker die de rij heeft gewijzigd en de datum waarop deze rij is gewijzigd toont, of een van beiden";
$text_translations["LABEL_DEFINITION_TEXT_LINE_EXPLANATION"] = "Een korte regel met tekst";
$text_translations["LABEL_DEFINITION_TEXT_FIELD_EXPLANATION"] = "Een veld met een aantal regels tekst";
$text_translations["LABEL_DEFINITION_NOTES_FIELD_EXPLANATION"] = "De gebruiker kan hier meerdere opmerkingen toevoegen. Per opmerking is te zien welke gebruiker de opmerking aangemaakt heeft en wanneer";
$text_translations["LABEL_DEFINITION_SELECTION_EXPLANATION"] = "Geef de verschillende invoermogelijkheden waaruit de gebruiker kan kiezen op, gescheiden door het '|' teken<br>bijvoorbeeld: 'open|bezig|gesloten'";
$text_translations["LABEL_LIST_MODIFICATIONS_DONE"] = "Lijst wijzigingen doorgevoerd!<br>Je kunt terugkeren naar het lijstoverzicht";
$text_translations["LABEL_NEW_LIST_CREATED"] = "Nieuwe lijst gemaakt!<br>maak een nieuwe lijst of keer terug naar het lijstoverzicht";
$text_translations["LABEL_ID_COLUMN_SHOW"] = "toon dit veld&nbsp;&nbsp;";
$text_translations["LABEL_ID_COLUMN_NO_SHOW"] = "toon dit veld niet&nbsp;&nbsp;";
$text_translations["LABEL_NAME_ONLY"] = "alleen naam&nbsp;&nbsp;";
$text_translations["LABEL_DATE_ONLY"] = "alleen datum&nbsp;&nbsp;";
$text_translations["LABEL_DATE_NAME"] = "datum en naam&nbsp;&nbsp;";
$text_translations["LABEL_CONFIRM_MODIFY"] = "De inhoud van velden die je gewijzigd hebt zal worden verwijderd.\\nDe inhoud van velden die je hebt verwijderd zal verwijderd worden.\\nWeet je zeker dat je deze lijst wilt wijzigen?";

/**
 * login labels
 */
$text_translations["LABEL_PLEASE_LOGIN"] = "Log in";
$text_translations["LABEL_PASSWORD"] = "wachtwoord";
$text_translations["LABEL_USER"] = "gebruiker";

/**
 * portal labels
 */
$text_translations["LABEL_LIST_ID"] = "id";
$text_translations["LABEL_LIST_NAME"] = "naam";
$text_translations["LABEL_LIST_DESCRIPTION"] = "beschrijving";
$text_translations["LABEL_LIST_DEFINITION"] = "definitie";
$text_translations["LABEL_LIST_CREATOR"] = "gemaakt";
$text_translations["LABEL_LIST_MODIFIER"] = "laatste wijziging";
$text_translations["LABEL_CONFIRM_DELETE"] = "Weet je zeker dat je deze lijst wilt wijzigen?";

?>