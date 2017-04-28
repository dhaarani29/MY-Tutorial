<?php

require '/var/www/html/mytutorial/doctrine/app/Boostrap.php';
require '/var/www/html/mytutorial/doctrine/app/src/Entity/Product.php';

//Get the request url
$url = $_SERVER['REQUEST_URI'];

//Get the parts of the url (url and query)
$parts = parse_url($url);

//Extract the query parameter and copy to one variable
parse_str($parts['query'], $query);

//Get the actiontype and id from query parameter
$productId = $query['id'];
$actionType = $query['actionType'];

//Create the object for product entity
$product = new Product();

/*
 * To create product 
 */
if ($actionType == 'create') {

    $product->setTitle("Anotomy");
    $product->setDescription("Anotomy Desc");
    $product->setPrice("100");
    $product->setDate();
    $entityManager->persist($product);
    $entityManager->flush();
    echo "Successfully created Id is :" . $product->getId();
}
/*
 * To update the product
 */ elseif ($actionType == 'update') {
    $productDetails = $entityManager->find("Product", $productId);
    $productDetails->setTitle("Anotomy update");
    $product->setDescription("Anotomy Desc");
    $product->setPrice("100");
    $product->setDate();
    $entityManager->flush();
    echo "Successfully Updated Id is :" . $productId;
}
/*
 * To get all the product details
 */ elseif ($actionType == 'getall') {
    $productDetails = $entityManager->getRepository('Product')->findAll();
    foreach ($productDetails as $key => $value) {
        echo $value->getId() . "|" . $value->getTitle() . "|" . $value->getDescription() . "|" . $value->getPrice();
        echo "<br>";
    }
}
/*
 * To get the product details by id
 */ elseif ($actionType == 'get') {

    $productDetails = $entityManager->find("Product", $productId);
    echo $productDetails->getId() . "|" . $productDetails->getTitle() . "|" . $productDetails->getDescription() . "|" . $productDetails->getPrice();
    echo "<br>";
}
/*
 * To delete the product
 */ elseif ($actionType == 'delete') {
    $sql = "DELETE FROM product WHERE id=" . $productId;
    $query = $entityManager->createQuery($sql);
    $query->getResult();
    echo "Deleted successfully" . $productId;
} else {
    $response = "Your are not selected any action type";
}