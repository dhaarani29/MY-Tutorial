<?php

/**
 * DBCommon - It's service file to handle additional phpunit functions.
 *
 * @Copyright 2016 Impelsys India Pvt Ltd.
 * @Author : Shreelakshmi U
 * @Since : 26-September-2016
 */
// Declare namespaces

namespace QuizzingPlatform\Services;

//Use silex webtestcase which act as a browser
use Silex\WebTestCase;

class TestWebtestcase extends WebTestCase {

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * createApplication method(), which returns your silex application instance
     */
    protected $host;

    public function createApplication() {
        //Mention the application environment.
        //Based on the environment, return the app or run the app

        $app_env = 'test';
        //Silex boostrap file
        $app = require __DIR__ . '/../../web/index_api.php';
        //Get the silex application instance

        $this->host = 'http://localhost/';

        //Access Token
        $this->accessToken = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOjY0LCJjbGllbnRVc2VySWQiOjY0LCJjbGllbnRJZCI6MSwidXNlclR5cGVOYW1lIjoiQURNSU4iLCJmaXJzdE5hbWUiOiJzaHJlZSIsIm1pZGRsZU5hbWUiOm51bGwsImxhc3ROYW1lIjoic2hyZWUiLCJleHAiOjE0OTIyNTA4MTB9.qXiMCKf6XkThYDIEIBMonA9SrP_miFKKoLtgIEfrm8w';
        //Admin Token

        $this->adminAccessToken = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOjY1LCJjbGllbnRVc2VySWQiOm51bGwsImNsaWVudElkIjoxLCJ1c2VyVHlwZU5hbWUiOiJBRE1JTiIsImZpcnN0TmFtZSI6InNyaSIsIm1pZGRsZU5hbWUiOm51bGwsImxhc3ROYW1lIjoic3JpIiwiZXhwIjoxNDkzNTM1MjM4fQ.97jtq6KGhpEEN3A5OX7tWJnESincH2vx85QzyDUMb9s';

               
        $this->requestFrom = 'admin';

        return $app;
    }

    public function setUp() {
        parent::setUp();
    }

    protected function tearDown() {
        
    }

}
