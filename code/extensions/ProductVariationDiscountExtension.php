<?php

class ProductVariationDiscountExtension extends DataExtension
{
    
    private static $casting = array(
        'TotalReduction' => 'Currency'
    );

    /**
     * Get the difference between the original price
     * and the new price.
     */
    public function getTotalReduction($original = "Price")
    {
        $reduction = $this->owner->{$original} - $this->owner->sellingPrice();
        //keep it above 0;
        $reduction = $reduction < 0 ? 0 : $reduction;
        return $reduction;
    }

    /**
     * Check if this variation has a reduced price.
     */
    public function IsReduced()
    {
        return (bool)$this->getTotalReduction();
    }
}
