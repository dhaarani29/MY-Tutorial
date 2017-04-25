<?php

namespace QuizzingPlatform\Admin\Dashboard;

//Use silex webtestcase which act as a browser

use QuizzingPlatform\Services\TestWebtestcase;
use Silex\Application;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-09-20 at 15:06:06.
 */
class DashboardControllerTest extends TestWebtestcase {

    /**
     * @covers QuizzingPlatform\Admin\Dashboard\DashboardController::testGetDashboardCount
     * @todo   Implement testGetAllGroups().
     */
    public function testGetDashboardCount() {

        $client = $this->createClient();
        //Success scenario
        $userId = 1;
        $url = $this->host . 'api/dashboard' . '?userId=' . $userId;
        $client->request(
                'GET', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                '');
        $this->assertEquals($this->app['cache']->fetch('HTTP_SUCCESS'), $client->getResponse()->getStatusCode());  //Asserting the status Code
        $this->assertTrue($client->getResponse()->isSuccessful()); //Checking the response is successfull
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json')); //Asserting the Content type
        $this->assertContains('url', $client->getResponse()->getContent());

        //Permission Error
        $userId = 12;
        $url = $this->host . 'api/dashboard';
        $client->request(
                'GET', //Method
                $url, //Request URL
                array(), //Parameters
                array(), //Files
                array('HTTP_AUTHORIZATION' => $this->adminAccessToken, 'HTTP_REQUESTFROM' => $this->requestFrom), //Header
                '');
        $this->assertEquals($this->app['cache']->fetch('HTTP_FORBIDDEN'), $client->getResponse()->getStatusCode());  //Asserting the status Code
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json')); //Asserting the Content type
        $this->assertContains('5500', $client->getResponse()->getContent());
    }

}
