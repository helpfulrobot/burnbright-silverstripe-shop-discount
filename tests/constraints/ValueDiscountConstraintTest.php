<?php

use Shop\Discount\Calculator;

class ValueDiscountConstraintTest extends SapphireTest
{

    protected static $fixture_file = array(
        'shop/tests/fixtures/shop.yml'
    );

    public function setUp()
    {
        parent::setUp();
        ShopTest::setConfiguration();
        $this->cart = $this->objFromFixture("Order", "cart");
        $this->othercart = $this->objFromFixture("Order", "othercart");
        $this->placedorder = $this->objFromFixture("Order", "unpaid");
    }

    public function testMinOrderValue()
    {
        $coupon = OrderCoupon::create(array(
            "Title" => "Orders 200 and more",
            "Code" => "200PLUS",
            "Type" => "Amount",
            "Amount" => 35,
            "ForItems" => 0,
            "ForCart" => 1,
            "MinOrderValue" => 200
        ));
        $coupon->write();
        $context = array("CouponCode" => $coupon->Code);
        $this->assertFalse($coupon->validateOrder($this->cart, $context), "$8 order isn't enough");
        $this->assertTrue($coupon->validateOrder($this->othercart, $context), "$200 is enough");
        $this->assertTrue($coupon->validateOrder($this->placedorder, $context), "$500 order is enough");

        $calculator = new Calculator($this->cart, $context);
        $this->assertEquals(0, $calculator->calculate());
        $calculator = new Calculator($this->othercart, $context);
        $this->assertEquals(35, $calculator->calculate());
        $calculator = new Calculator($this->placedorder, $context);
        $this->assertEquals(35, $calculator->calculate());
    }
}
