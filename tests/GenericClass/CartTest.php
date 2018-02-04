<?php

namespace App\Test\GenericClass;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use App\GenericClass\Cart;

require_once('CartDefinitions.php');

class CartTest extends KernelTestCase
{
    private $cart;

    protected function setUp()
    {
        global $findModels;
        global $findMaterials;

        $this->cart = new Cart($findModels, $findMaterials);
    }

    public function testLoadEmptyCart()
    {
        $cart = $this->cart;
        $cart->deserialize([]);
        $this->assertEquals([], $cart->getCart());
    }

    public function testLoadNonEmptyCart()
    {
        global $materials;
        global $models;

        $cart = $this->cart;
        $cart->deserialize([[
            Cart::PRODUCT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => 1 ],
            Cart::COUNT_KEY => 1,
            ]]
        );
        $this->assertEquals([[
            Cart::COUNT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => $materials[0] ],
            Cart::PRODUCT_KEY => $models[0],
            Cart::ATTRS_FACTOR_KEY => [ 'main' => 1 ],
            ]],
            $cart->getCart()
        );
    }

    /**
     * @expectedException TypeError
     */
    public function testAddToCartUnInited(){

        $cart = $this->cart;
        $cart->addItem(null, []);
    }

    /**
     * @expectedException TypeError
     */
    public function testAddToCartNoProduct(){
        $cart = $this->cart;
        $cart->deserialize([]);
        $cart->addItem(null, []);
    }

    /**
     * @expectedException Exception
     */
    public function testAddToCartMissingPart(){
        global $models;

        $cart = $this->cart;
        $cart->deserialize([]);
        $cart->addItem($models[0], []);
    }

    /**
     * @expectedException Exception
     */
    public function testAddToCartExcessingPart(){
        global $models;
        global $materials;

        $cart = $this->cart;
        $cart->deserialize([]);
        $cart->addItem($models[0], ['main' => $materials[0], 'sec' => $materials[1]]);
    }

    public function testAddToCartNewItem(){
        global $materials;
        global $models;

        $cart = $this->cart;
        $cart->deserialize([]);

        // Test with materials as objects
        $cart->addItem($models[0], ['main' => $materials[0]]);
        $this->assertEquals([[
            Cart::COUNT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => $materials[0] ],
            Cart::PRODUCT_KEY => $models[0],
            Cart::ATTRS_FACTOR_KEY => [ 'main' => 1 ],
            ]],
            $cart->getCart()
        );

        // Test with materials as integers
        $cart->addItem($models[1], ['main' => 2]);
        $this->assertEquals([[
            Cart::COUNT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => $materials[0] ],
            Cart::PRODUCT_KEY => $models[0],
            Cart::ATTRS_FACTOR_KEY => [ 'main' => 1 ],
        ], [
            Cart::COUNT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => $materials[1] ],
            Cart::PRODUCT_KEY => $models[1],
            Cart::ATTRS_FACTOR_KEY => [ 'main' => 1 ],
            ]],
            $cart->getCart()
        );
    }

    public function testAddToCartExactSameItem(){
        global $materials;
        global $models;

        $cart = $this->cart;
        $cart->deserialize([]);

        // Test with materials as objects
        $cart->addItem($models[0], ['main' => $materials[0]]);
        $this->assertEquals([[
            Cart::COUNT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => $materials[0] ],
            Cart::PRODUCT_KEY => $models[0],
            Cart::ATTRS_FACTOR_KEY => [ 'main' => 1 ],
            ]],
            $cart->getCart()
        );

        // Test with materials as integers
        $cart->addItem($models[0], ['main' => 1]);
        $this->assertEquals([[
            Cart::COUNT_KEY => 2,
            Cart::ATTRS_KEY => [ 'main' => $materials[0] ],
            Cart::PRODUCT_KEY => $models[0],
            Cart::ATTRS_FACTOR_KEY => [ 'main' => 1 ],
            ]],
            $cart->getCart()
        );
    }

    public function testAddToCartSameItemDiffParams(){
        global $materials;
        global $models;

        $cart = $this->cart;
        $cart->deserialize([]);

        // Test with materials as objects
        $cart->addItem($models[0], ['main' => $materials[0]]);
        $this->assertEquals([[
            Cart::COUNT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => $materials[0] ],
            Cart::PRODUCT_KEY => $models[0],
            Cart::ATTRS_FACTOR_KEY => [ 'main' => 1 ],
            ]],
            $cart->getCart()
        );

        // Test with materials as integers
        $cart->addItem($models[0], ['main' => 2]);
        $this->assertEquals([[
            Cart::COUNT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => $materials[0] ],
            Cart::PRODUCT_KEY => $models[0],
            Cart::ATTRS_FACTOR_KEY => [ 'main' => 1 ],
        ], [
            Cart::COUNT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => $materials[1] ],
            Cart::PRODUCT_KEY => $models[0],
            Cart::ATTRS_FACTOR_KEY => [ 'main' => 1 ],
            ]],
            $cart->getCart()
        );
    }

    public function testRemoveFromCartModelMaterials(){
        global $materials;
        global $models;

        $cart = $this->cart;
        $cart->deserialize([[
            Cart::PRODUCT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => 1 ],
            Cart::COUNT_KEY => 1,
        ], [
            Cart::PRODUCT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => 2 ],
            Cart::COUNT_KEY => 1,
            ]]
        );
        $cart->removeItem($models[0], ['main' => $materials[1]]);
        $this->assertEquals([[
            Cart::COUNT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => $materials[0] ],
            Cart::PRODUCT_KEY => $models[0],
            Cart::ATTRS_FACTOR_KEY => [ 'main' => 1 ],
            ]],
            $cart->getCart()
        );
    }

    public function testRemoveFromCartModel(){
        global $materials;
        global $models;

        $cart = $this->cart;
        $cart->deserialize([[
            Cart::PRODUCT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => 1 ],
            Cart::COUNT_KEY => 1,
        ], [
            Cart::PRODUCT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => 2 ],
            Cart::COUNT_KEY => 1,
        ], [
            Cart::PRODUCT_KEY => 2,
            Cart::ATTRS_KEY => [ 'main' => 2 ],
            Cart::COUNT_KEY => 1,
            ]]
        );
        $cart->removeItem($models[0]);
        $this->assertEquals([[
            Cart::COUNT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => $materials[1] ],
            Cart::PRODUCT_KEY => $models[1],
            Cart::ATTRS_FACTOR_KEY => [ 'main' => 1 ],
            ]],
            $cart->getCart()
        );
    }

    public function testRemoveFromCartAll(){
        global $materials;
        global $models;

        $cart = $this->cart;
        $cart->deserialize([[
            Cart::PRODUCT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => 1 ],
            Cart::COUNT_KEY => 1,
        ], [
            Cart::PRODUCT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => 2 ],
            Cart::COUNT_KEY => 1,
        ], [
            Cart::PRODUCT_KEY => 2,
            Cart::ATTRS_KEY => [ 'main' => 2 ],
            Cart::COUNT_KEY => 1,
            ]]
        );
        $cart->removeItem(true);
        $this->assertEquals([], $cart->getCart());
    }

    public function testExportCartData()
    {
        global $materials;
        global $models;

        $cart = $this->cart;
        $data = [[
            Cart::PRODUCT_KEY => 1,
            Cart::ATTRS_KEY => [ 'main' => 1 ],
            Cart::COUNT_KEY => 1,
            ]
        ];
        $cart->deserialize($data);
        $this->assertEquals($data, $cart->serialize());
    }
}
