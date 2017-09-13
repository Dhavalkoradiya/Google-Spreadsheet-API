<?php
/*
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
::                                                                                              ::
::  Google Spreadsheet API/V4 by Dhaval koradiya - https://www.linkedin.com/in/Dhavalkoradiya   ::
::                                                                                              ::
::  https://github.com/Dhavalkoradiya/Google-Spreadsheet-API                                    ::
::                                                                                              ::
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
*/

    require_once __DIR__ . '/vendor/autoload.php'; //Path for lib
    // Set up the API
    $client = new Google_Client();
    $client->setAuthConfigFile( __DIR__ . '/sheets_api_secret.json'); // Use your own client_secret JSON file
    $client->addScope(Google_Service_Sheets::SPREADSHEETS);
    $accessToken = '76669b61e58efb433a7a943d822b309d974c5d9v'; // Use your generated access token
    $client->setAccessToken($accessToken);
    $sheetsService = new Google_Service_Sheets($client);
    $spreadsheetId = "ENTER-SPREADSHEET-ID";
    $sheetId = "ENTER-SHEET-ID";

    //value in array
    $newValues = array("Dhaval", "dhavalk@gmail.com", "+91 7990473046", "https://www.linkedin.com/in/Dhavalkoradiya"); 
    addRowToSpreadsheet($sheetsService, $spreadsheetId, $sheetId, $newValues);    

function addRowToSpreadsheet($sheetsService, $spreadsheetId, $sheetId, $newValues = []) {
    // Build the CellData array
    $values = [];
    foreach ($newValues AS $d) {
        $cellData = new Google_Service_Sheets_CellData();
        $value = new Google_Service_Sheets_ExtendedValue();
        $value->setStringValue($d);
        $cellData->setUserEnteredValue($value);
        $values[] = $cellData;
    }
    // Build the RowData
    $rowData = new Google_Service_Sheets_RowData();
    $rowData->setValues($values);
    // Prepare the request
    $append_request = new Google_Service_Sheets_AppendCellsRequest();
    $append_request->setSheetId($sheetId);
    $append_request->setRows($rowData);
    $append_request->setFields('userEnteredValue');
    // Set the request
    $request = new Google_Service_Sheets_Request();
    $request->setAppendCells($append_request);
    // Add the request to the requests array
    $requests = array();
    $requests[] = $request;
    // Prepare the update
    $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest(array(
        'requests' => $requests
    ));

    try {
        // Execute the request
        $response = $sheetsService->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
        if ($response->valid()) {            
            return true;// Success, the row has been added
        }
    } catch (Exception $e) {        
        error_log($e->getMessage());// Something went wrong
    }
    return false;
}