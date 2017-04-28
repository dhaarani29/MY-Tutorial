<?php

require '/var/www/html/mytutorial/doctrine/app/Boostrap.php';
require '/var/www/html/mytutorial/doctrine/app/src/Entity/Product.php';

class ProductRepository {

    public $entityManager;

    public function __construct() {
        
    }

    public function ProductCreate($userDetails) {
        $product = new Product();
        $product->setTitle(isset($title) ? $title : "Book1");
        $product->setDescription(isset($description) ? $description : "Book1 desc");
        $product->setPrice(isset($price) ? $price : 100);
        $product->setDate(NULL);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        echo "created successfully and the id is:" . $product->getId();
    }

    public function ProductUpdate($param) {
        
    }

    public function ProductGet() {

        $userRepository = $this->entityManager->getRepository('Product');
        print_r($userRepository);
        die;
    }

    public function ProductGetById($param) {
        
    }

    public function ProductDelete($param) {
        
    }

}
