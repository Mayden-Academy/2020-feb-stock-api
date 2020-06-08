<?php


namespace App\Entities;

use App\Validators\NameValidator;
use App\Validators\PriceValidator;
use App\Validators\SkuValidator;
use App\Validators\StockLevelValidator;
use App\Validators\StringValidator;

use App\Interfaces\ProductEntityInterface;

class ProductEntity implements ProductEntityInterface
{
    private $sku;
    private $name;
    private $price;
    private $stockLevel;

    /**
     * ProductEntity constructor.
     */
    public function __construct($sku, $name, $price, $stockLevel)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->stockLevel = $stockLevel;
    }

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getStockLevel()
    {
        return $this->stockLevel;
    }

    private function sanitiseDatas()
    {
        $this->sku = StringValidator::sanitiseData($this->sku);
        $this->sku = StringValidator::validateExistsAndLength($this->sku, 50);
        $this->sku = StringValidator::validateSku($this->sku);

        $this->name = StringValidator::sanitiseData($this->name);
        $this->name = StringValidator::validateExistsAndLength($this->name, 50);

        $this->price = StringValidator::sanitiseData($this->price);
        $this->price = StringValidator::validateExistsAndLength($this->price, 50);
        $this->price = StringValidator::validatePrice($this->price);

        $this->stockLevel = StringValidator::sanitiseData($this->stockLevel);
        $this->stockLevel = StringValidator::validateExistsAndLength($this->stockLevel, 50);
        $this->stockLevel = StringValidator::validateStockLevel($this->stockLevel);
    }
}
