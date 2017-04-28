<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require '/var/www/html/mytutorial/doctrine/app/Boostrap.php';
require '/var/www/html/mytutorial/doctrine/app/src/Entity/Product.php';

use Doctrine\ORM\EntityRepository;

class ProductController Extends EntityRepository {

    public function index() {

        $queryParams = $_SERVER['QUERY_STRING'];
        $actionType = $queryParams;

        if ($actionType == 'create') {
            $productDetails = array("title" => "Anotomy", "description" => "Anotomy", "price" => "100");
            $response = $userRepository->ProductCreate($productDetails);
        } elseif ($actionType == 'update') {
            $response = $userRepository->ProductUpdate($param);
        } elseif ($actionType == 'getall') {
            $userRepository = new ProductRepository();
            $response = $userRepository->ProductGet();
            print_r($response);
            die;
        } elseif ($actionType == 'get') {
            $response = $userRepository->ProductGetById($param);
        } elseif ($actionType == 'delete') {
            $response = $userRepository->ProductDelete($param);
        } else {
            $response = "Your are not selected any action type";
        }
        die;
        return $response;
    }

    public function ProductGet() {
        $sql = "select * from product";
        $query = $this->getEntityManager()->createQuery($sql);
        //$query->setMaxResults($number);
        $eee = $query->getResult();
        print_r($eee);
        die;
    }

}

$queryParams = $_SERVER['QUERY_STRING'];
$actionType = $queryParams;
$users = new ProductController();
$entityManager;
if ($actionType == 'create') {
    $productDetails = array("title" => "Anotomy", "description" => "Anotomy", "price" => "100");
    $response = $userRepository->ProductCreate($productDetails);
} elseif ($actionType == 'update') {
    $response = $userRepository->ProductUpdate($param);
} elseif ($actionType == 'getall') {

    $response = $users->ProductGet();
    print_r($response);
    die;
} elseif ($actionType == 'get') {
    $response = $userRepository->ProductGetById($param);
} elseif ($actionType == 'delete') {
    $response = $userRepository->ProductDelete($param);
} else {
    $response = "Your are not selected any action type";
}


