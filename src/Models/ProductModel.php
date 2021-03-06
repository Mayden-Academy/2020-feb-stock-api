<?php

namespace App\Models;

use App\Interfaces\ProductEntityInterface;
use App\Interfaces\ProductModelInterface;

class ProductModel implements ProductModelInterface
{
    private $db;

    /**
     * ProductModel constructor.
     * @param $db
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Gets all products from Database
     * @return array contains all active products in DB
     */
    public function getAllProducts(): array
    {
        $query = $this->db->query('SELECT `sku`, `name`, `price`, `stockLevel` 
                                                FROM `products` 
                                                WHERE `deleted` = 0;');

        return $query->fetchAll();
    }

    /**
     * Adds a product to the Database
     * @param ProductEntityInterface $productEntity
     * @return bool if product has been added successfully to DB
     */
    public function addProduct(ProductEntityInterface $productEntity): bool
    {
        $array = [
            "sku"=>$productEntity->getSku(),
            "name"=>$productEntity->getName(),
            "price"=>$productEntity->getPrice(),
            "stockLevel"=>$productEntity->getStockLevel()
        ];

        $query = $this->db->prepare("INSERT INTO `products`
                                        (`sku`, `name`, `price`, `stockLevel`)
                                            VALUES (:sku, :name, :price, :stockLevel)");

        return $query->execute($array);
    }

    /**
     * @param ProductEntityInterface $productEntity
     * @return bool if product stock level has been updated successfully in DB
     */
    public function updateProductStock(array $productData)
    {
        $query = $this->db->prepare("UPDATE `products` 
                                        SET `stockLevel` = :stockLevel
                                        WHERE `sku` = :sku");

        return $query->execute($productData);
    }

    /**
     * @return bool if product has been updated successfully in DB
     */
    public function updateProduct(ProductEntityInterface $productEntity): bool
    {
        $array = [
            "sku"=>$productEntity->getSku(),
            "name"=>$productEntity->getName(),
            "price"=>$productEntity->getPrice(),
            "stockLevel"=>$productEntity->getStockLevel()
        ];

        $query = $this->db->prepare("UPDATE `products`
                                        SET `name` = :name,
                                            `price` = :price,
                                            `stockLevel` = :stockLevel
                                        WHERE `sku` = :sku;");

        return $query->execute($array);
    }

    /**
     * Checks if product exists in Database
     * @param string $sku
     * @return array containing the existing product's SKU and deleted status.
     * @return false if product doesn't exist
     */
    public function checkProductExists(string $sku)
    {
        $query = $this->db->prepare("SELECT `sku`, `deleted` FROM `products` WHERE `sku` = ?");
        $query->execute([$sku]);
        return $query->fetch();
    }

    /**
     * @param string $sku
     * @return bool if product has been "undeleted" successfully in DB
     */
    public function reinstateProduct(string $sku): bool
    {
        $query = $this->db->prepare("UPDATE `products`
                                        SET `deleted` = 0
                                        WHERE `sku` = ?;");

        return $query->execute([$sku]);
    }

    /**
     * Soft deletes product from Database and updates stock to 0 for said product
     * @param string $sku
     * @return bool whether the operation was successful or not
     */
    public function deleteProductBySku(string $sku): bool
    {
        $query = $this->db->prepare("UPDATE `products`
                                        SET `deleted` = 1,
                                            `stockLevel` = 0
                                        WHERE `sku` = ?");

        return $query->execute([$sku]);
    }

    /**
     * @param string $sku
     * @return mixed array of product if exists or false if it doesn't
     */
    public function getProductBySKU(string $sku)
    {
        $query = $this->db->prepare("SELECT `sku`, `name`, `price`, `stockLevel` FROM `products` WHERE `sku` = ? AND `deleted` = 0");
        $query->execute([$sku]);

        return $query->fetch();
    }

    /**
     * Gets all stock levels for the SKUs provided in the parameter.
     * @param array $productSKUs
     * @return array containing the product SKUs and their stockLevels
     */
    public function getMultipleStockLevelsBySKUs(array $productSKUs): array
    {
        $skusList = '("' . implode('", "', $productSKUs) . '")';
        $query = $this->db->prepare("SELECT `sku`, `name`, `price`, `stockLevel` 
                                                FROM `products` 
                                                WHERE `deleted` = 0 AND `sku` IN " . $skusList);
        $query->execute();

        return $query->fetchAll();
    }
}
