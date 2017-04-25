<?php

namespace QuizzingPlatform\Admin\Users;

//Use silex webtestcase which act as a browser

use QuizzingPlatform\Services\TestWebtestcase;
use Silex\Application;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-09-20 at 15:06:06.
 */
class UsersControllerTest extends TestWebtestcase {
    /*
     * @covers QuizzingPlatform\Admin\Users\UsersController::testdeleteUser
     * @todo   Implement testdeleteUser().
     */

    public function testdeleteUser() {

        $client = $this->createClient();

        //Success Scenario
        $id = 1;
        $userId = 1;
        $url = $this->host . 'api/user/' . $id . '?userId=' . $userId;
        $client->request(
                'DELETE', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                '');        //Request the Url with Get method
        $this->assertEquals($this->app['cache']->fetch('HTTP_NOCONTENT'), $client->getResponse()->getStatusCode()); //checking the statusCode
        $this->assertNotNull($client->getResponse()->getContent(), "Response is true"); //Response content not null
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Check for id which is not exists
        $id = 4;
        $userId = 1;
        $url = $this->host . 'api/user/' . $id . '?userId=' . $userId;
        $client->request(
                'DELETE', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                '');
        $this->assertEquals($this->app['cache']->fetch('HTTP_NOTFOUND'), $client->getResponse()->getStatusCode()); //checking the statusCode
        //Check for permission issues.
        $id = 40;
        $userId = '';
        $url = $this->host . 'api/metadata/' . $id . '?userId=' . $userId;
        $client->request(
                'DELETE', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                '');
        $this->assertEquals($this->app['cache']->fetch('HTTP_FORBIDDEN'), $client->getResponse()->getStatusCode()); //checking the statusCode
    }

    /*
     * @covers QuizzingPlatform\Admin\Users\UsersController::testgetResourcePermissions
     * @todo   Implement testgetResourcePermissions().
     */

    public function testgetResourcePermissions() {
        $client = $this->createClient();

        //success Scenario
        $userId = 1;
        $resource = 'user';
        $url = $this->host . 'api/getpermissions' . '?userId=' . $userId . '&resource=' . $resource;
        $client->request(
                'GET', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                '');
        $this->assertEquals($this->app['cache']->fetch('HTTP_SUCCESS'), $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'), "Content type should be Application/json"); //Content type is json
        $this->assertNotNull($client->getResponse()->getContent(), "Permission response"); //Response content not null
        //Permission issue for Resource 
        $userId = 1;
        $resource = 'question_collection';
        $url = $this->host . 'api/getpermissions' . '?userId=' . $userId . '&resource=' . $resource;
        $client->request(
                'GET', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                '');
        $this->assertEquals($this->app['cache']->fetch('HTTP_NOTFOUND'), $client->getResponse()->getStatusCode());

        //Request Empty
        $url = $this->host . 'api/getpermissions';
        $client->request(
                'GET', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                '');
        $this->assertEquals($this->app['cache']->fetch('HTTP_BADREQUEST'), $client->getResponse()->getStatusCode());
    }

    /*
     * @covers QuizzingPlatform\Admin\Users\UsersController::testgetAllUsers
     * @todo   Implement testgetAllUsers().
     */

    public function testgetAllUsers() {
        $client = $this->createClient();

        //Success scenario
        $userId = 1;
        $url = $this->host . 'api/user' . '?userId=' . $userId;
        $client->request(
                'GET', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                '');
        $this->assertEquals($this->app['cache']->fetch('HTTP_SUCCESS'), $client->getResponse()->getStatusCode());

        //Permission issue
        $userId = 845;
        $url = $this->host . 'api/user' . '?userId=' . $userId;
        $client->request(
                'GET', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                '');
        $this->assertEquals($this->app['cache']->fetch('HTTP_FORBIDDEN'), $client->getResponse()->getStatusCode());

        //Query Param
        // Sample parameters for filtering,pagination,sorting
        $firstName = '';
        //$userName = '';
        $lastName = '';
        $emailaddress = '';
        $perPage = 10;
        //$sort = "%2bemailAddress"; // +id sort
        $page = 1;
        $userId = 1;
        $role = 'super';
        $group = '';

        $queryParam = '?perPage=' . $perPage
                . '&userId=' . $userId . '&page=' . $page;
        if ($firstName != '') {
            $queryParam .= '&firstName=' . $firstName;
        }
        if ($lastName != '') {
            $queryParam .= '&lastName=' . $lastName;
        }
        if ($emailaddress != '') {
            $queryParam .= '&emailAddress=' . $emailaddress;
        }
        if ($role != '') {
            $queryParam .= '&role=' . $role;
        }
        if ($group != '') {
            $queryParam .= '&group=' . $group;
        }
        $url = $this->host . 'api/user' . $queryParam; //echo $url;die;
        $client->request(
                'GET', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                ''); //Request the Url with Post method and posting sample json content
        $response = $client->getResponse()->getContent();
        $res = get_object_vars(json_decode($response));
        $reqTotal = $res['total'];

        if ($reqTotal > 0) {


            if ($firstName != '') {
                $this->assertContains($firstName, $response);
            }
            if ($lastName != '') {
                $this->assertContains($lastName, $response);
            }
            if ($emailaddress != '') {
                $this->assertContains($emailaddress, $response);
            }
        }

        $this->assertEquals($this->app['cache']->fetch('HTTP_SUCCESS'), $client->getResponse()->getStatusCode()); //checking the statusCode
        $this->assertNotNull($client->getResponse()->getContent(), "All list Metadata response "); //Response content not null
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'), "Content type should be Application/json"); //Content type is json
    }

    /*
     * @covers QuizzingPlatform\Admin\Users\UsersController::testgetUserById
     * @todo   Implement testgetUserById().
     */

    public function testgetUserById() {
        //Calling the creatclient method which acts as a browser, and allows you to interact with your application

        $client = $this->createClient();

        // Check for possitive case
        $id = 66;
        $userId = 66;
        $url = $this->host . 'api/user/' . $id . '?userId=' . $userId;
        $client->request(
                'GET', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                ''); //Request the Url with Post method and posting sample json content
        $this->assertEquals($this->app['cache']->fetch('HTTP_SUCCESS'), $client->getResponse()->getStatusCode()); //checking the statusCode
        $this->assertNotNull($client->getResponse()->getContent(), "User by Id response"); //Response content not null
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'), "Content type should be Application/json"); //Content type is json
        $this->assertContains('2', $client->getResponse()->getContent()); //checking the response contains Id which is passed by Url
        // Check for permission error
        $id = 2;
        $userId = '';
        $url = $this->host . 'api/user/' . $id . '?userId=' . $userId;
        $client->request(
                'GET', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                '');      //Request the Url with Post method and posting sample json content
        $this->assertEquals($this->app['cache']->fetch('HTTP_FORBIDDEN'), $client->getResponse()->getStatusCode()); //checking the statusCode
        // Check if metadata id not exists.
        $id = 140;
        $userId = 1;
        $url = $this->host . 'api/user/' . $id . '?userId=' . $userId;
        $client->request(
                'GET', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                '');      //Request the Url with Post method and posting sample json content
        $this->assertEquals($this->app['cache']->fetch('HTTP_NOTFOUND'), $client->getResponse()->getStatusCode()); //checking the statusCode
    }

    /*
     * @covers QuizzingPlatform\Admin\Users\UsersController::testCreateUserDetails
     * @todo   Implement testCreateUserDetails().
     */

    public function testCreateUserDetails() {
        //Calling the creatclient method which acts as a browser, and allows you to interact with your application
        $client = $this->createClient();

        $url = $this->host . 'api/user';

        //Json input for sample User details
        $sampleJson = '{
                            "userType":1,
                            "userName":"srinivasum@impelsys.com",
                            "password":"Imp@1234",
                            "firstName":"Srinivasu",
                            "lastName":"M",
                            "status":1,
                            "userId":1,
                            "address1":"6th floor, Tower c",
                            "address2":"Diamond district, HAL Road",
                            "address3":"Domlur",
                            "address4":"near domlur bridge",
                            "city":"Bangalore",
                            "countryId":236,
                            "stateId":3805,
                            "postalcode":"560008",
                            "phone1":"9874569871",
                            "phone2":"4569871236",
                            "getRoles":"1,2",
                            "selectedRoleGroup":"1"
                         }';
        //Request the Url with Post method and posting sample json content
        $client->request(
                'POST', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                $sampleJson); //Raw content
        $this->assertEquals($this->app['cache']->fetch('HTTP_CREATED'), $client->getResponse()->getStatusCode()); //checking the statusCode
        $this->assertTrue(is_numeric($client->getResponse()->getContent())); //Checking Reponse is numeric
        $this->assertNotNull($client->getResponse()->getContent(), "User Id as response."); //Response content not null
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'), "Content type should be Application/json"); //Content type is json
        // check for duplicate metadata.
        $sampleJson = '{
                            "userType":1,
                            "userName":"srinivasum@impelsys.com",
                            "password":"Imp@1234",
                           "firstName":"Srinivasu",
                            "lastName":"M",
                            "status":1,
                            "userId":66,
                            "address1":"6th floor, Tower c",
                            "address2":"Diamond district, HAL Road",
                            "address3":"Domlur",
                            "address4":"near domlur bridge",
                           "city":"Bangalore",
                            "countryId":236,
                            "stateId":3805,
                            "postalcode":"560008",
                            "phone1":"9874569871",
                            "phone2":"4569871236",
                            "getRoles":"1,2",
                            "selectedRoleGroup":"1"
                         }';
        //Request the Url with Post method and posting sample json content
        $client->request(
                'POST', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                $sampleJson); //Raw content
        $this->assertEquals($this->app['cache']->fetch('HTTP_DUPLICATE'), $client->getResponse()->getStatusCode()); //checking the statusCode
        //Check for permission error.
        $sampleJson = '{
                            "userType":1,
                            "userName":"srinivasum@impelsys.com",
                            "password":"Imp@1234",
                            "firstName":"Srinivasu",
                            "lastName":"M",
                            "status":1,
                            "userId":1067,
                            "address1":"6th floor, Tower c",
                            "address2":"Diamond district, HAL Road",
                            "address3":"Domlur",
                            "address4":"near domlur bridge",
                            "city":"Bangalore",
                            "countryId":236,
                            "stateId":3805,
                            "postalcode":"560008",
                            "phone1":"9874569871",
                            "phone2":"4569871236",
                            "getRoles":"1,2",
                            "selectedRoleGroup":"1"
                         }';
        $client->request(
                'POST', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                $sampleJson); //Raw content
        $this->assertEquals($this->app['cache']->fetch('HTTP_FORBIDDEN'), $client->getResponse()->getStatusCode()); //checking the statusCode
        //Check if empty input is passed.
        $sampleJson = '';
        $client->request(
                'POST', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                $sampleJson); //Raw content
        $this->assertEquals($this->app['cache']->fetch('HTTP_BADREQUEST'), $client->getResponse()->getStatusCode()); //checking the statusCode 
    }

    /*
     * @covers QuizzingPlatform\Admin\Users\UsersController::testupdateUser
     * @todo   Implement testupdateUser().
     */

    public function testupdateUser() {

        //Calling the creatclient method which acts as a browser, and allows you to interact with your application
        $client = $this->createClient();

        $id = 4;
        $url = $this->host . 'api/user/' . $id;

        //Json input for sample User details
        $sampleJson = '{
                        "userType": 1,
                        "userId":1,
                        "userName": "jaya",
                         "userEmail":"jaya@123.com",
                         "firstName": "pavi",
                         "middleName":"pavi",
                         "lastName": "jaya",
                        "status":"1",
                         "changePassword":"true",
                         "password": "123",
                         "selectedRoleGroup": "1",
                        "getGroups": "1",
                         "address1": "add1",
                         "address2": "add2",
                         "address3": "add3",
                        "address4": "add4",
                         "city": "city",
                        "countryId": 3,
                         "stateId": 1,
                          "postalcode":  "122"}';

        //Request the Url with Post method and posting sample json content
        $client->request(
                'PUT', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                $sampleJson); //Raw content 
        $this->assertEquals($this->app['cache']->fetch('HTTP_NOCONTENT'), $client->getResponse()->getStatusCode()); //checking the statusCode
        $this->assertNotNull($client->getResponse()->getContent(), "Response is true"); //Response content not null
        $this->assertTrue($client->getResponse()->isSuccessful());
        // check for duplicate user information which exists
        //Json input for sample User details
        $id = 63;
        $url = $this->host . 'api/user/' . $id;
        $sampleJson = '{
                        "userType": 1,
                        "userId":1,
                        "userName": "abdul.rahman@impelsys.com",
                         "userEmail":"jaya@123.com",
                         "firstName": "pavi",
                         "middleName":"pavi",
                         "lastName": "jaya",
                        "status":"1",
                         "changePassword":"true",
                         "password": "123",
                         "selectedRoleGroup": "1",
                        "getGroups": "1",
                         "address1": "add1",
                         "address2": "add2",
                         "address3": "add3",
                        "address4": "add4",
                         "city": "city",
                        "countryId": 3,
                         "stateId": 1,
                          "postalcode":  "122"}';

        //Request the Url with Post method and posting sample json content
        $client->request(
                'PUT', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                $sampleJson); //Raw content 
        $this->assertEquals($this->app['cache']->fetch('HTTP_DUPLICATE'), $client->getResponse()->getStatusCode()); //checking the statusCode
        //Check for permission issue 
        $sampleJson = '{
                        "userType": 1,
                        "userId":2456,
                        "userName": "swetha",
                         "userEmail":"swetha@123.com",
                         "firstName": "pavi",
                         "middleName":"pavi",
                         "lastName": "jaya",
                        "status":"1",
                         "changePassword":"true",
                         "password": "123",
                         "selectedRoleGroup": "1",
                        "getGroups": "1",
                         "address1": "add1",
                         "address2": "add2",
                         "address3": "add3",
                        "address4": "add4",
                         "city": "city",
                        "countryId": 3,
                         "stateId": 1,
                          "postalcode":  "122"}';
        $id = 63;
        $url = $this->host . 'api/user/' . $id;
        //Request the Url with Post method and posting sample json content
        $client->request(
                'PUT', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                $sampleJson); //Raw content 
        $this->assertEquals($this->app['cache']->fetch('HTTP_FORBIDDEN'), $client->getResponse()->getStatusCode()); //checking the statusCode
        // Check if metadata id not exitsts and trying to update that metadata.
        $id = '10';
        $url = $this->host . 'api/metadata/' . $id;
        $sampleJson = '{ "userId":1}';

        //Request the Url with Post method and posting sample json content
        $client->request(
                'PUT', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                $sampleJson); //Raw content
        $this->assertEquals($this->app['cache']->fetch('HTTP_NOTFOUND'), $client->getResponse()->getStatusCode()); //checking the statusCode
    }

    /*
     * @covers QuizzingPlatform\Admin\Users\UsersController::testAssociateUser
     * @todo   Implement testAssociateUser().
     */

    public function testAssociateUser() {

        //Calling the creatclient method which acts as a browser, and allows you to interact with your application
        $client = $this->createClient();

        //Success scenario
        $id = 63;
        $url = $this->host . 'api/associateuser/' . $id;
        $sampleJson = '{"userId":1,
                        "getRoles":"4,5",
                        "selectedRoleGroup":"1"}';
        $client->request(
                'PUT', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                $sampleJson); //Raw content 
        $this->assertEquals($this->app['cache']->fetch('HTTP_NOCONTENT'), $client->getResponse()->getStatusCode()); //checking the statusCode
        $this->assertNotNull($client->getResponse()->getContent(), "Response is true"); //Response content not null
        $this->assertTrue($client->getResponse()->isSuccessful());

        //Check for permission issue 
        $id = 10;
        $url = $this->host . 'api/associateuser/' . $id;
        $sampleJson = '{"userId":1012,
                        "getRoles":"4,5",
                        "selectedRoleGroup":"1"}';
        $client->request(
                'PUT', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                $sampleJson);  //Raw content 
        $this->assertEquals($this->app['cache']->fetch('HTTP_FORBIDDEN'), $client->getResponse()->getStatusCode()); //checking the statusCode
        $this->assertContains('5500', $client->getResponse()->getContent());



        //Id not exist
        $id = 1780;
        $url = $this->host . 'api/associateuser/' . $id;
        $sampleJson = '{"userId":1,
                        "getRoles":"4,5",
                        "selectedRoleGroup":"1"}';
        $client->request(
                'PUT', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                $sampleJson); //Raw content
        $this->assertEquals($this->app['cache']->fetch('HTTP_NOTFOUND'), $client->getResponse()->getStatusCode()); //checking the statusCode
        $this->assertContains('5006', $client->getResponse()->getContent());
    }

}