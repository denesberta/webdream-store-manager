<?php

include_once __DIR__ . '/../vendor/autoload.php';

use denesberta\StoreManager\Models\Car;
use denesberta\StoreManager\Models\Food;
use denesberta\StoreManager\Models\Store;
use denesberta\StoreManager\Models\Brand;
use denesberta\StoreManager\StoreManager;
use denesberta\StoreManager\Models\Clothing;
use denesberta\StoreManager\Exception\StoreIsFullException;
use denesberta\StoreManager\Exception\NotFoundStoreException;
use denesberta\StoreManager\Exception\NotFoundProductInStoreException;

/**
 * Remove a product from a store which is not exist
 */
function test_1()
{
    echo "Test 1";
    //Create Stores
    $carStore = new Store('Car Store', 'Teszt utca 6', 10);

    //Create Brands
    $BMW = new Brand('BMW', Brand::QUALITY_EXPENSIVE);

    //Create Products
    $car = new Car('BMW 3', 'EM213123', 5000000, $BMW, Car::GASOLINE);

    $storeService = new StoreManager();
    $storeService->addStore($carStore);

    try {
        $storeService->removeProduct($car);
    } catch (NotFoundProductInStoreException $e) {
        echo '<br><strong>' . $e->getMessage() . '</strong><br>';
    }
}

/**
 * Remove a product from a store which store is not added to the store manager
 */
function test_2()
{
    echo "Test 2";
    //Create Stores
    $carStore = new Store('Car Store', 'Teszt utca 6', 10);

    //Create Brands
    $BMW = new Brand('BMW', Brand::QUALITY_EXPENSIVE);

    //Create Products
    $car = new Car('BMW 3', 'EM213123', 5000000, $BMW, Car::GASOLINE);

    $storeService = new StoreManager();

    try {
        $storeService->removeProductFromStore($car, $carStore);
    } catch (NotFoundProductInStoreException|NotFoundStoreException $e) {
        echo '<br><strong>' . $e->getMessage() . '</strong><br>';
    }
}

/**
 * Add a product to a store which is not exist
 */
function test_3()
{
    echo "Test 3";
    //Create Stores
    $carStore = new Store('Car Store', 'Teszt utca 6', 10);

    //Create Brands
    $BMW = new Brand('BMW', Brand::QUALITY_EXPENSIVE);

    //Create Products
    $car = new Car('BMW 3', 'EM213123', 5000000, $BMW, Car::DIESEL);

    $storeService = new StoreManager();

    try {
        $storeService->addProductToStore($car, $carStore);
    } catch (StoreIsFullException|NotFoundStoreException $e) {
        echo '<br><strong>' . $e->getMessage() . '</strong><br>';
    }
}

/**
 * Remove too many products from a store
 */
function test_4()
{
    echo "Test 4";
    //Create Stores
    $carStore = new Store('Car Store', 'Teszt utca 6', 10);

    //Create Brands
    $BMW = new Brand('BMW', Brand::QUALITY_EXPENSIVE);

    //Create Products
    $car = new Car('BMW 3', 'EM213123', 5000000, $BMW, Car::DIESEL);

    $storeService = new StoreManager();
    $storeService->addStore($carStore);

    try {
        for ($i = 0; $i < 10; $i++) {
            $storeService->addProduct($car);
        }
    } catch (StoreIsFullException $e) {
        echo '<br><strong>' . $e->getMessage() . '</strong><br>';
    }

    $storeService->printStoreWithProducts();

    try {
        for ($i = 0; $i < 11; $i++) {
            $storeService->removeProduct($car);
        }
    } catch (NotFoundProductInStoreException $e) {
        echo '<br><strong>' . $e->getMessage() . '</strong><br>';
    }

    $storeService->printStoreWithProducts();
}

/**
 * Create 2 stores and add products to them without specification which store get these items and also remove products from them
 */
function test_5()
{
    echo "Test 5";
    //Create Stores
    $genericStore = new Store('Generic Store', 'Teszt utca 5', 10);
    $genericStore2 = new Store('Generic Store 2', 'Teszt utca 3', 5);

    //Create Brands
    $hungarian = new Brand('Hungarian', Brand::QUALITY_NORMAL);

    //Create Products
    $bread = new Food('Bread', 'A123456', 1000, $hungarian, '2022-10-10');

    //Add Stores to StoreManager
    $storeService = new StoreManager();
    $storeService->addStore($genericStore);
    $storeService->addStore($genericStore2);

    try {
        $storeService->addProduct($bread);
    } catch (StoreIsFullException $e) {
        echo $e->getMessage();
    }
    $storeService->printStoreWithProducts();

    try {
        $storeService->removeProduct($bread);
    } catch (NotFoundProductInStoreException $e) {
        echo '<br><strong>' . $e->getMessage() . '</strong><br>';
    }
    $storeService->printStoreWithProducts();
}

/**
 * Create 2 stores and add products to them with specification which store get these items and also remove products from them
 */
function test_6()
{
    echo "Test 6";
    //Create Stores
    $genericStore = new Store('Generic Store', 'Teszt utca 5', 10);
    $genericStore2 = new Store('Generic Store 2', 'Teszt utca 3', 5);

    //Create Brands
    $Zara = new Brand('Zara', Brand::QUALITY_EXPENSIVE);

    //Create Products
    $clothing = new Clothing('T-Shirt','EM213123', 5000, $Zara, 'M');

    //Add Stores to StoreManager
    $storeService = new StoreManager();
    $storeService->addStore($genericStore);
    $storeService->addStore($genericStore2);

    try {
        $storeService->addProductToStore($clothing, $genericStore2);
    } catch (StoreIsFullException|NotFoundStoreException $e) {
        echo '<br><strong>' . $e->getMessage() . '</strong><br>';
    }
    $storeService->printStoreWithProducts();

    try {
        $storeService->removeProductFromStore($clothing, $genericStore2);
    } catch (NotFoundProductInStoreException|NotFoundStoreException $e) {
        echo '<br><strong>' . $e->getMessage() . '</strong><br>';
    }
    $storeService->printStoreWithProducts();
}


/**
 * Create 2 stores and add too many products for both of them
 */
function test_7()
{
    echo "Test 7";
    //Create Stores
    $clothingStore = new Store('Clothing Store', 'Teszt utca 5', 100);
    $clothingStore2 = new Store('Clothing Store 2', 'Teszt utca 3', 200);

    //Create Brands
    $Zara = new Brand('Zara', Brand::QUALITY_EXPENSIVE);
    $NewYorker = new Brand('New Yorker', Brand::QUALITY_NORMAL);

    //Create Products
    $clothing = new Clothing('T-Shirt','EM213123', 5000, $Zara, 'M');
    $clothing2 = new Clothing('T-Shirt','EM213123', 5000, $NewYorker, 'M');

    //Add Stores to StoreManager
    $storeService = new StoreManager();
    $storeService->addStore($clothingStore);
    $storeService->addStore($clothingStore2);

    try {
        for ($i = 0; $i < 301; $i++) {
            $storeService->addProduct($clothing);
        }
    } catch (StoreIsFullException $e) {
        echo '<br><strong>' . $e->getMessage() . '</strong><br>';
    }

    $storeService->printStoreWithProducts();

    try {
        $storeService->removeProduct($clothing);
    } catch (NotFoundProductInStoreException $e) {
        echo $e->getMessage();
    }

    $storeService->printStoreWithProducts();
}


test_1();
test_2();
test_3();
test_4();
test_5();
test_6();
test_7();
