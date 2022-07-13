<?php

namespace App\ServiceClasses;

use App\Models\CompositProduct;
use App\Models\Product;
use App\Models\SellOrderedProduct;

class ActivityTimeCalc
{
    public $family,
        $quantity,
        $components;

    public function __construct(SellOrderedProduct $sop)
    {
        $this->quantity = $sop->quantity;
        if ($sop->productForSell->model_name == Product::class) {
            $product = Product::find($sop->productForSell->model_id);
            $this->components = 0;
            $this->family = $product->product_family_id;
        } else {
            $product = CompositProduct::find($sop->productForSell->model_id);
            $this->components = $product->compositProductDetails->count();
            $this->family = $product->product_family_id;
        }
    }
    /**
     * Calcute time for ordered product in terms of family and components.
     *
     * @param  mix  $image
     * @return string
     */
    public function calculateTime()
    {
        switch ($this->family) {
            case 1:
                return $this->_forEmblems();
                break;
            case 2:
                return $this->_forABS();
                break;
            case 3:
                return $this->_forMetalics();
                break;
            case 4:
                return $this->_forKeyChains();
                break;
            case 5:
                return $this->_forDocumentHolder();
                break;
            case 7:
                return $this->_forEstiren();
                break;
            case 9:
                return $this->_forCarRudeMat();
                break;
            case 10:
                return $this->_forCarFoamyMat();
                break;
            case 12:
                return $this->_forSticker();
                break;
            default:
                return 90;
        }
    }

    protected function _forEmblems()
    {
        return round($this->quantity * 0.2);
    }

    protected function _forABS()
    {
        $prepare_station_time = 90;
        $serigraphy_time = 0.2 * $this->quantity;
        $separate_time = 0.06 * $this->quantity;
        $number_of_packages = ($this->quantity / 250);
        $package_time = 6 * $number_of_packages;
        $clean = 15;
        if ($this->components) {
            $time = $prepare_station_time + (($this->components-1) * $serigraphy_time) + $separate_time
                + $package_time + $clean;
        } else {
            $time = $package_time;
        }

        return round($time);
    }
    
    protected function _forEstiren()
    {
        $prepare_station_time = 90;
        $serigraphy_time = 0.15 * $this->quantity;
        $separate_time = 0.06 * $this->quantity;
        $number_of_packages = ($this->quantity / 250);
        $package_time = 6 * $number_of_packages;
        $clean = 15;
        if ($this->components) {
            $time = $prepare_station_time + (($this->components-1) * $serigraphy_time) + $separate_time
                + $package_time + $clean;
        } else {
            $time = $package_time;
        }

        return round($time);
    }
    
    protected function _forCarFoamyMat()
    {
        $prepare_station_time = 90;
        $serigraphy_time = 0.4 * $this->quantity;
        $separate_time = 0.06 * $this->quantity;
        $package_time = 0.1 * $this->quantity;
        $clean = 15;
        if ($this->components) {
            $time = $prepare_station_time + (($this->components-1) * $serigraphy_time) + $separate_time
                + $package_time + $clean;
        } else {
            $time = $package_time;
        }

        return round($time);
    }

    protected function _forCarRudeMat()
    {
        $prepare_time = 12.16 * $this->quantity;
        $package_time = 1.16 * $this->quantity;
        if ($this->components) {
            $time = $prepare_time + $package_time;
        } else {
            $time = $package_time;
        }

        return round($time);
    }

    protected function _forMetalics()
    {
        $clean_time = $this->quantity;
        $set_emblems_time = 0.25 * $this->quantity;
        $package_time = 0.3 * $this->quantity;
        if ($this->components) {
            $time = $clean_time + (($this->components-1) * $set_emblems_time) + $package_time;
        } else {
            $time = $package_time;
        }

        return round($time);
    }

    protected function _forKeyChains()
    {
        $engrave_time = 1.3 * $this->quantity;
        $package_time = 0.3 * $this->quantity;
        if ($this->components) {
            $time = $engrave_time + $package_time;
        } else {
            $time = $package_time;
        }

        return round($time);
    }

    protected function _forDocumentHolder()
    {
        return 20;
    }

    protected function _forSticker()
    {
        $time = 3.2 * $this->quantity;
        return round($time);
    }
}
