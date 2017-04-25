<?php

// Common Log :
$langArray['PERMISSION_ERROR']               = array('code'=>5500, 'title'=>"Permission denied.", 'description'=>"Don't have permission. Please contact WK Admin.");
 
// Metadata module - Code sequence 1***
$langArray['METADATA_TYPE_ERROR']                   = array('code'=>1001, 'title'=>"Error retrieving Metadata Type.", 'description'=>"Metadata type not found.");
$langArray['METADATA_DATA_TYPE_ERROR']              = array('code'=>1002, 'title'=>"Error retrieving Metadata Data Type.", 'description'=>"Metadata data type not found.");
$langArray['CREATE_METADATA_ERROR']                 = array('code'=>1003, 'title'=>"Error Creating Metadata.", 'description'=>"Metadata insertion error.");
$langArray['INVALID_METADATA_REQUEST_ERROR']        = array('code'=>1004, 'title'=>"Invalid input request for Metadata Create.", 'description'=>"Invalid input request for Metadata Create.");
$langArray['METADATA_NOT_FOUND_ERROR']              = array('code'=>1005, 'title'=>"Metadata Not Found.", 'description'=>"Metadata not found for the requested Metadata Id.");
$langArray['EMPTY_METADATA_ERROR']                  = array('code'=>1006, 'title'=>"Metadata request is empty.", 'description'=>"Metadata request content is empty.");
$langArray['METADATA_ERROR']                        = array('code'=>1007, 'title'=>"Error retrieving Metadata details.", 'description'=>"Error retrieving Metadata details.");
$langArray['DELETE_METADATA_ERROR']                 = array('code'=>1008, 'title'=>"Error deleting the Metadata.", 'description'=>"Error while deleting the Metadata.");
$langArray['UPDATE_METADATA_ERROR']                 = array('code'=>1009, 'title'=>"Error updating Metadata.", 'description'=>"Error while updating Metadata.");
$langArray['INVALID_GET_METADATA_REQUEST_ERROR']    = array('code'=>1010, 'title'=>"Invalid input request to get Metadata.", 'description'=>"Invalid input request to get Metadata.");
$langArray['INVALID_METADATA_UPDATE_REQUEST_ERROR'] = array('code'=>1011, 'title'=>"Invalid input request for Metadata update.", 'description'=>"Invalid input request for Metadata update.");
$langArray['DUPLICATE_METADATA_TAG_ERROR']          = array('code'=>1012, 'title'=>"Duplicate Metadata.", 'description'=>"Duplicate Metadata.");
$langArray['INSTITUTION_LIST_ERROR']                = array('code'=>1013, 'title'=>"Institutions not found.", 'description'=>"Error retrieving Institutions.");
$langArray['TAXONOMY_LIST_ERROR']                   = array('code'=>1014, 'title'=>"Error retrieving Taxonomy.", 'description'=>"Error retrieving Taxonomy.");
$langArray['INVALID_TAXONOMY_REQUEST_ERROR']        = array('code'=>1015, 'title'=>"Invalid input request to get Taxonomy.", 'description'=>"Invalid input request to get Taxonomy.");
$langArray['INVALID_SUBJECT_REQUEST_ERROR']         = array('code'=>1016, 'title'=>"Invalid input request to get Subjects.", 'description'=>"Invalid input request to get Subjects.");
$langArray['INVALID_SNOMED_REQUEST_ERROR']          = array('code'=>1017, 'title'=>"Invalid input request to get SNOMED CT terms.", 'description'=>"Invalid input request to get SNOMED CT terms.");
$langArray['INVALID_PRODUCT_REQUEST_ERROR']         = array('code'=>1018, 'title'=>"Invalid input request to get Products.", 'description'=>"Invalid input request to get Products.");

// Item types module - Code sequence 2***
$langArray['QUESTION_TYPE_ERROR']                   = array('code'=>2001, 'title'=>"Error retrieving Question Types.", 'description'=>"Question Types not found.");
$langArray['REMEDIATION_LINK_ERROR']                = array('code'=>2002, 'title'=>"Error retrieving Remediation Link Types.", 'description'=>"Remediation Link Types not found.");
$langArray['CREATE_QUESTION_ERROR']                 = array('code'=>2003, 'title'=>"Error creating a Question.", 'description'=>"Question insertion error.");
$langArray['INVALID_CREATE_QUESTION_REQUEST_ERROR'] = array('code'=>2004, 'title'=>"Invalid input request for Question create.", 'description'=>"Invalid input request for Question create.");
$langArray['EMPTY_CREATE_QUESTION_REQUEST_ERROR']   = array('code'=>2005, 'title'=>"Question create request is empty.", 'description'=>"Question create request is empty.");
$langArray['INVALID_UPDATE_QUESTION_REQUEST_ERROR'] = array('code'=>2006, 'title'=>"Invalid input request for Question update.", 'description'=>"Invalid input request for Question update.");
$langArray['QUESTION_NOT_FOUND_ERROR']              = array('code'=>2007, 'title'=>"Question not found.", 'description'=>"Question not found for the requested Id.");
$langArray['EMPTY_ASSET_UPLOAD_ERROR']              = array('code'=>2008, 'title'=>"Empty asset upload.", 'description'=>"Empty asset upload.");
$langArray['DELETE_QUESTION_ERROR']                 = array('code'=>2009, 'title'=>"Error deleting a Question.", 'description'=>"Error while deleting the Question.");
$langArray['UPDATE_QUESTION_ERROR']                 = array('code'=>2010, 'title'=>"Error updating a Question.", 'description'=>"Error while updating the Question.");
$langArray['PUBLISH_QUESTION_FAILED_ERROR']         = array('code'=>2011, 'title'=>"Failed to Publish a Question.", 'description'=>"Failed to Publish a Question.");
$langArray['ASSET_TYPE_ERROR']                      = array('code'=>2012, 'title'=>"Error retrieving Asset Types.", 'description'=>"Asset types not found.");
$langArray['DUPLICATE_ASSOC_ITEM_ERROR']            = array('code'=>2013, 'title'=>"Duplicate Question association.", 'description'=>"Duplicate Question association.");
$langArray['ASSOCIATING_ITEM_ERROR']                = array('code'=>2014, 'title'=>"Error while associating the Question.", 'description'=>"Error while associating the Question.");

// ItemBank module - Code sequence 3***
$langArray['DUPLICATE_QUESTION_NAME_ERROR']                 = array('code'=>3001, 'title'=>"Duplicate Question Collection.", 'description'=>"Duplicate Question Collection name is not allowed.");
$langArray['ITEM_COLLECTION_CREATION_ERROR']                = array('code'=>3002, 'title'=>"Error creating Question Collection.", 'description'=>"Question collection insertion error.");
$langArray['INVALID_INPUT_ITEM_COLLECTION_CREATE_ERROR']    = array('code'=>3003, 'title'=>"Invalid input request for Question Collection create.", 'description'=>"Invalid input request for Question Collection create.");
$langArray['CREATE_ITEM_COLLECTION_EMPTY_ERROR']            = array('code'=>3004, 'title'=>"Question Collection create request is empty.", 'description'=>"Question Collection create request is empty.");
$langArray['ITEM_COLLECTION_NOTFOUND_ERROR']                = array('code'=>3005, 'title'=>"Question Collection not found.", 'description'=>"Question Collection not found  for the requested Id.");
$langArray['ITEM_COLLECTION_UPDATE_ERROR']                  = array('code'=>3006, 'title'=>"Error while updating Question Collection.", 'description'=>"Error while updating Question Collection.");
$langArray['INVALID_INPUT_ITEM_COLLECTION_UPDATE_ERROR']    = array('code'=>3007, 'title'=>"Invalid input request for Question Collection update.", 'description'=>"Invalid input request for Question Collection update.");
$langArray['ITEM_COLLECTION_DELETE_ERROR']                  = array('code'=>3008, 'title'=>"Error deleting the Question Collection.", 'description'=>"Error deleting the Question Collection.");
$langArray['INVALID_FILE_ITEM_COLLECTION_ERROR']            = array('code'=>3009, 'title'=>"Invalid Question Collection upload file.", 'description'=>"Invalid Question Collection upload file.");
$langArray['INVALID_UPLOADING_NEWBANK_FILE']                = array('code'=>3010, 'title'=>"Upload failed, the xml is not a valid xml.",'description'=>"Question Collection created successfully and template upload failed due to invalid template. Please select the Question Collection from the dropdown and re-upload the template.Please check the 'status' of the collection to see the XML parsing status.");
$langArray['UPLOAD_NEWBANK_FILE_NOT_EXIST']                 = array('code'=>3011, 'title'=>"Upload failed, allQuestions.xml / allQuestionsSXD.xml is missing.",'description'=>"Question Collection created successfully and template upload failed due to missing allQuestions.xml / allQuestionsXSD.xml. Please select the Question Collection from the dropdown and re-upload the template.Please check the 'status' of the collection to see the XML parsing status.");
$langArray['INVALID_UPLOADING_EXISTBANK_FILE']              = array('code'=>3012, 'title'=>"Upload failed, the xml is not a valid xml.",'description'=>" Template uploaded failed due to invalid template. Please check the 'status' of the Question Collection to see the XML parsing status.");
$langArray['UPLOAD_EXISTBANK_FILE_NOT_EXIST']               = array('code'=>3013, 'title'=>"Upload failed, allQuestions.xml / allQuestionsSXD.xml is missing.",'description'=>"Template upload failed due to missing allQuestions.xml / allQuestionsXSD.xml. Please check the 'status' of the collection to see the XML parsing status.");
$langArray['PUBLISH_BANK_FAILED']                           = array('code'=>3014, 'title'=>"Failed to publish the Question Collection",'description'=>"Failed to publish the Question Collection");
$langArray['IMPORTSTATUS_BANK_FAILED']                      = array('code'=>3015, 'title'=>"Failed to get status of Question Collection import",'description'=>"Failed to get status of Question Collection import");
$langArray['FAILED_TO_EXPORT_ITEM_COLLECTION']              = array('code'=>3016, 'title'=>"Failed to export Question Collection",'description'=>"Failed to export Question Collection");
$langArray['NO_ITEMS_ASSOCIATED_TO_ITEM_COLLECTION']        = array('code'=>3017, 'title'=>"There is no question associated with Question Collection",'description'=>"There is no question associated with Question Collection to export");



// Test module - Code sequence 4***
$langArray['CREATE_QUIZ_ERROR']                     = array('code'=>4001, 'title'=>"Error creating Quiz.", 'description'=>"Quiz insertion error.");
$langArray['INVALID_QUIZ_REQUEST_ERROR']            = array('code'=>4002, 'title'=>"Invalid input request for Quiz create.", 'description'=>"Invalid input request for Quiz create.");
$langArray['INVALID_INPUT_TEST_REQUEST_ERROR']      = array('code'=>4003, 'title'=>"Invalid input request to create a Quiz.", 'description'=>"Invalid input request to create a Quiz.");
$langArray['INVALID_REQUEST_QUIZ_PROGRESS_ERROR']   = array('code'=>4004, 'title'=>"Invalid input request for Quiz progress.", 'description'=>"Invalid input request for Quiz progress.");
$langArray['INVALID_QUIZ_INSTANCE_DELETE_ERROR']    = array('code'=>4005, 'title'=>"Invalid input request for Quiz Instance delete.", 'description'=>"Invalid input request for Quiz Instance delete.");
$langArray['INVALID_QUIZ_DELETE_REQUEST_ERROR']     = array('code'=>4006, 'title'=>"Invalid input request for Quiz delete.", 'description'=>"Invalid input request for Quiz delete.");
$langArray['EMPTY_QUIZ_REQUEST_ERROR']              = array('code'=>4007, 'title'=>"Quiz create request is empty.", 'description'=>"Quiz create request is empty.");
$langArray['DUPLICATE_QUIZ_ERROR']                  = array('code'=>4008, 'title'=>"Duplicate Quiz Name.", 'description'=>"Duplicate Quiz Name.");
$langArray['QUIZ_NOT_FOUND_ERROR']                  = array('code'=>4009, 'title'=>"Quiz Not Found.", 'description'=>"Quiz not found  for the requested ID.");
$langArray['QUIZ_INSTANCE_NOT_FOUND_ERROR']         = array('code'=>4010, 'title'=>"Quiz Instance not found.", 'description'=>"Quiz Instance not found for the requested ID.");
$langArray['QUIZ_INSTANCES_NOT_FOUND_ERROR']        = array('code'=>4023, 'title'=>"There is no Quiz Instance available.", 'description'=>"There is no Quiz Instance available.");

$langArray['TEST_NOT_FOUND_ERROR']                  = array('code'=>4011, 'title'=>"Quiz Not Found.", 'description'=>"Quiz not found  for the requested ID.");
$langArray['QUIZ_RETRIEVING_ERROR']                 = array('code'=>4012, 'title'=>"Error retrieving Quiz details.", 'description'=>"Failed to retrieve Quiz details.");
$langArray['TEST_PROGRESS_ERROR']                   = array('code'=>4013, 'title'=>"Error retrieving Quiz progress.", 'description'=>"Error retrieving Quiz progress.");
$langArray['DELETE_QUIZ_ERROR']                     = array('code'=>4014, 'title'=>"Error deleting a Quiz.", 'description'=>"Error deleting a Quiz.");
$langArray['UPDATE_QUIZ_ERROR']                     = array('code'=>4015, 'title'=>"Error while updating a Quiz.", 'description'=>"Error while updating a Quiz.");
$langArray['CREATE_QUIZ_INSTANCE_ERROR']            = array('code'=>4016, 'title'=>"Error creating Quiz Instance.", 'description'=>"There is no question available for the selected metadata and Quiz criteria.");
$langArray['GET_QUESTION_QUIZ_ERROR']               = array('code'=>4017, 'title'=>"Error retrieving Questions for Quiz.", 'description'=>"Error retrieving Questions for Quiz.");
$langArray['SUBMIT_QUIZ_ERROR']                     = array('code'=>4018, 'title'=>"Error submitting the Quiz.", 'description'=>"Error while submitting the Quiz.");
$langArray['GET_QUIZ_QUESTION_INSTANCE_ERROR']      = array('code'=>4019, 'title'=>"Error retrieving Quiz Instance question details.", 'description'=>"Error retrieving Quiz Instance question details.");
$langArray['QUESTION_QUIZ_ASSOC_ERROR']             = array('code'=>4020, 'title'=>"Question is not associated with the requested Quiz Instance.", 'description'=>"Question is not associated with the requested Quiz Instance.");
$langArray['INVALID_QUIZ_INSTANCE_USER_ERROR']      = array('code'=>4021, 'title'=>"Not a valid Quiz Instance for the user.", 'description'=>"Not a valid Quiz Instance for the user.");
$langArray['TEST_INSTANCE_COMPLETED_ERROR']         = array('code'=>4022, 'title'=>"Quiz Instance completed.", 'description'=>"Quiz Instance completed.");

// User module - Code sequence 5***
$langArray['GET_RESOURCE_PERMISSION_ERROR'] = array('code'=>5001, 'title'=>"Error retrieving resource permissions.", 'description'=>"Error retrieving resource permissions.");
$langArray['RESOURCE_REQUEST_EMPTY_ERROR']  = array('code'=>5002, 'title'=>"Request is empty.", 'description'=>"Resource/User Id is empty.");
$langArray['DUPLICATE_USER_INFO_ERROR']     = array('code'=>5003, 'title'=>"Duplicate User information.", 'description'=>"Duplicate User information.");
$langArray['CREATE_USER_INFO_ERROR']        = array('code'=>5004, 'title'=>"Error creating User.", 'description'=>"Error creating User.");
$langArray['EMPTY_USER_INFO_REQUEST_ERROR'] = array('code'=>5005, 'title'=>"User request is empty.", 'description'=>"User request content is empty.");
$langArray['USER_INFO_NOT_FOUND_ERROR']     = array('code'=>5006, 'title'=>"User not found.", 'description'=>"User not found  for the requested Id.");
$langArray['DELETE_USER_INFO_ERROR']        = array('code'=>5007, 'title'=>"Error deleting the User.", 'description'=>"User deletion error.");
$langArray['UPDATE_USER_INFO_ERROR']        = array('code'=>5008, 'title'=>"Error while updating User.", 'description'=>"Error while updating user.");
$langArray['ASSOCIATING_USER_INFO_ERROR']   = array('code'=>5009, 'title'=>"Error while associating User.", 'description'=>"Error while associating User.");
$langArray['INVALID_USER_EMAIL_ERROR']      = array('code'=>5010, 'title'=>"User Email is invalid.", 'description'=>"User Email is invalid.");

//System setting Module - Code sequence 6***
$langArray['SYSTEM_CONFIG_ERROR']            = array('code'=>6001, 'title'=>"Error retrieving System Settings.", 'description'=>"System Settings not found.");
$langArray['MENU_LIST_ERROR']                = array('code'=>6002, 'title'=>"Error retrieving Menus.", 'description'=>"Menus not found.");
$langArray['CLEAR_CACHE_ERROR']              = array('code'=>6003, 'title'=>"Error clearing the cache.", 'description'=>"Error clearing the cache.");
$langArray['COUNTRY_LIST_ERROR']             = array('code'=>6005, 'title'=>"Error retrieving Countries.", 'description'=>"Countries not found.");
$langArray['STATE_LIST_ERROR']               = array('code'=>6006, 'title'=>"Error retrieving States.", 'description'=>"States not found for given Country id.");
$langArray['INVALID_STATE_ID_ERROR']         = array('code'=>6007, 'title'=>"Error retrieving States.", 'description'=>"Invalid country id.");
$langArray['GROUP_LIST_ERROR']               = array('code'=>6008, 'title'=>"Error retrieving Groups.", 'description'=>"Groups not found.");
$langArray['GROUP_BYID_ERROR']               = array('code'=>6009, 'title'=>"Error retrieving Group for the requested Id.", 'description'=>"No data found for given Group id.");
$langArray['INVALID_GROUP_ID_ERROR']         = array('code'=>6010, 'title'=>"Error retrieving Group.", 'description'=>"Invalid input group Id.");
$langArray['ROLE_LIST_ERROR']                = array('code'=>6011, 'title'=>"Error retrieving Roles.", 'description'=>"Roles not found.");
$langArray['ROLE_LIST_BYID_ERROR']           = array('code'=>6012, 'title'=>"Error retrieving Role.", 'description'=>"Role not found for given Role Id.");
$langArray['INVALID_ROLE_ID_ERROR']          = array('code'=>6013, 'title'=>"Error retrieving Role.", 'description'=>"Invalid Role id.");
$langArray['STATUS_ERROR']                   = array('code'=>6014, 'title'=>"Error retrieving status list.", 'description'=>"status list not found.");
$langArray['USERTYPE_LIST_ERROR']            = array('code'=>6015, 'title'=>"Error retrieving User Type list.", 'description'=>"User Type list not found.");
$langArray['SYSTEM_SETTING_LIST_ERROR']      = array('code'=>6016, 'title'=>"Error retrieving System Settings.", 'description'=>"System Settings not found.");
$langArray['SYSTEM_SETTING_EMAIL_ERROR']     = array('code'=>6017, 'title'=>"Email is invalid", 'description'=>"Email is invalid");
$langArray['SYSTEM_SETTING_UPDATION_ERROR']  = array('code'=>6018, 'title'=>"Error while updating System Settings information.", 'description'=>"Error while updating System Settings information.");
$langArray['INPUT_USER_ID_ERROR']            = array('code'=>6019, 'title'=>"Input request User Id is missing.", 'description'=>"Input request User Id is missing.");

// Reports module - Code sequence 7***
$langArray['NO_RECORDS_ERROR']      = array('code'=>7001, 'title'=>"No records Found.", 'description'=>"No records Found.");
$langArray['RETRIEVING_ERROR']      = array('code'=>7002, 'title'=>"Error retrieving Report details.", 'description'=>"Error retrieving Report details.");
$langArray['EXPORT_REPORT_ERROR']   = array('code'=>7003, 'title'=>"Error exporting the Report.", 'description'=>"Error exporting the Report.");
$langArray['TEST_RETRIEVING_ERROR'] = array('code'=>7004, 'title'=>"Error retrieving Quiz details.", 'description'=>"Error retrieving Quiz details.");

// Login module - Code sequence 8***
$langArray['TOKEN_EXPIRED_ERROR']           = array('code'=>8001, 'title'=>"Access Token expired.", 'description'=>"Access Token expired.");
$langArray['INVALID_CREDENTAILS_ERROR']     = array('code'=>8002, 'title'=>"Invalid credentials.", 'description'=>"Invalid Username or Password.");
$langArray['EMPTY_CREDENTIALS_ERROR']       = array('code'=>8003, 'title'=>"Input Credentials missing.", 'description'=>"Input credentials are missing.");
$langArray['INVALID_CLIENT_DETAILS_ERROR']  = array('code'=>8004, 'title'=>"Invalid Client Details.", 'description'=>"Invalid Client Code or Secret Key.");
$langArray['INVALID_CLIENT_CODE_ERROR']     = array('code'=>8005, 'title'=>"Invalid credentials.", 'description'=>"Invalid Client Code or Secret Key.");
$langArray['ACCESS_DENIED_ERROR']           = array('code'=>8006, 'title'=>"Access Denied.", 'description'=>"Invalid Access token, Please retry login.");
$langArray['USER_EMAIL_NOT_FOUND_ERROR']    = array('code'=>8007, 'title'=>"User doesn't exists.", 'description'=>"Email address not found.");
$langArray['SEND_MAIL_FAILED_ERROR']        = array('code'=>8008, 'title'=>"Failed to send mail.", 'description'=>"Failed to send email to the requested email address.");
$langArray['INVALID_INPUT_ERROR']           = array('code'=>8009, 'title'=>"Invalid input details.", 'description'=>"Invalid input details.");
$langArray['INVALID_EXPIRED_TOKEN_ERROR']   = array('code'=>8010, 'title'=>"Either Reset Token is invalid or its expired.", 'description'=>"Either Reset Token is invalid or its expired.");
$langArray['INVALID_RESET_TOKEN_ERROR']     = array('code'=>8011, 'title'=>"Invalid Reset Token.", 'description'=>"Invalid Reset Token.");
$langArray['PASSWORD_RESET_ERROR']          = array('code'=>8012, 'title'=>"Failed to reset the password.", 'description'=>"Failed to reset the password.");

// Offline Script module - Code sequence 9***
$langArray['READ_TAXONOMY_ERROR']   = array('code'=>9001, 'title'=>"Error reading Taxonomy.", 'description'=>"Error reading Taxonomy.");
$langArray['READ_SOLR_ERROR']       = array('code'=>9002, 'title'=>"Error retrieving records from Solr.", 'description'=>"Error retrieving records from Solr.");
$langArray['DELETE_SOLR_ERROR']     = array('code'=>9003, 'title'=>"Error deleting the Question from Solr.", 'description'=>"Error deleting the Question from Solr.");
$langArray['INDEX_SOLR_ERROR']      = array('code'=>9004, 'title'=>"Error indexing the records to Solr.", 'description'=>"Error indexing the records to Solr.");

//Role module - Code sequence 11**
$langArray['ROLE_ID_ERROR']                 = array('code'=>1101, 'title'=>"Role Id is missing.", 'description'=>"Role Id is missing.");
$langArray['ROLE_ASSOC_ERROR']              = array('code'=>1102, 'title'=>"Role deletion error.", 'description'=>"You cannot delete the Role since its associated with the User(s).");
$langArray['ROLE_DELETE_ERROR']             = array('code'=>1103, 'title'=>"Role deletion error.", 'description'=>"Error deleting the Role.");
$langArray['DUPLICATE_ROLE_NAME_ERROR']     = array('code'=>1108, 'title'=>"Duplicate Role name is not allowed.", 'description'=>"Duplicate Role name is not allowed.");
$langArray['ROLE_CREATION_ERROR']           = array('code'=>1109, 'title'=>"Failed to create a Role.", 'description'=>"Failed to create a Role.");
$langArray['INVALID_INPUT_ROLE_CREATE_ERROR']     = array('code'=>1110, 'title'=>"Invalid inputs for the Role creation.", 'description'=>"Invalid inputs for the Role creation.");
$langArray['CREATE_ROLE_EMPTY_ERROR']     = array('code'=>1111, 'title'=>"Empty request for Role creation.", 'description'=>"Empty request for Role creation.");


//Group Module - Code sequence 11**
$langArray['GROUP_ID_ERROR']        = array('code'=>1104, 'title'=>"Group Id is missing.", 'description'=>"Group id is missing.");
$langArray['GROUP_ASSOC_ERROR']     = array('code'=>1105, 'title'=>"Group deletion error.", 'description'=>"You cannot delete the Group since its associated with the user(s).");
$langArray['GROUP_DELETE_ERROR']    = array('code'=>1106, 'title'=>"Group deletion error.", 'description'=>"Error deleting the Group.");
$langArray['INVALID_GROUP_ID_ERROR']= array('code'=>1107, 'title'=>"Invalid Group Id.", 'description'=>"Invalid Group Id.");
$langArray['DUPLICATE_GROUP_NAME_ERROR']     = array('code'=>1112, 'title'=>"Duplicate Group Name is not allowed.", 'description'=>"Duplicate Group Name is not allowed.");
$langArray['GROUP_CREATION_ERROR']           = array('code'=>1113, 'title'=>"Failed to create Group.", 'description'=>"Failed to create Group.");
$langArray['INVALID_INPUT_GROUP_CREATE_ERROR']     = array('code'=>1114, 'title'=>"Invalid inputs for the Group creation.", 'description'=>"Invalid inputs for the Group creation.");
$langArray['CREATE_GROUP_EMPTY_ERROR']     = array('code'=>1115, 'title'=>"Empty request for Group creation.", 'description'=>"Empty request for Group creation.");



//Return the final langArray values
return $langArray;

?>
