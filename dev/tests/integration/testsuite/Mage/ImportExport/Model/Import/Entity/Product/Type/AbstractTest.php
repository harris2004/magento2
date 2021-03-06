<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_ImportExport
 * @subpackage  integration_tests
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_ImportExport_Model_Import_Entity_Product_Type_AbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_ImportExport_Model_Import_Entity_Product_Type_Abstract
     */
    protected $_model;

    /**
     * On product import abstract class methods level it doesn't matter what product type is using.
     * That is why current tests are using simple product entity type by default
     */
    public function setUp()
    {
        $arguments = array(array(new Mage_ImportExport_Model_Import_Entity_Product, 'simple'));
        $this->_model = $this->getMockForAbstractClass(
            'Mage_ImportExport_Model_Import_Entity_Product_Type_Abstract',
            $arguments
        );
    }

    protected function tearDown()
    {
        $this->_model = null;
    }

    /**
     * @dataProvider prepareAttributesWithDefaultValueForSaveDataProvider
     */
    public function testPrepareAttributesWithDefaultValueForSave($rowData, $withDefaultValue, $expectedAttributes)
    {
        $actualAttributes = $this->_model->prepareAttributesWithDefaultValueForSave($rowData, $withDefaultValue);
        foreach ($expectedAttributes as $key => $value) {
            $this->assertArrayHasKey($key, $actualAttributes);
            $this->assertEquals($value, $actualAttributes[$key]);
        }
    }

    public function prepareAttributesWithDefaultValueForSaveDataProvider()
    {
        return array(
            'Updating existing product with attributes that don\'t have default values' => array(
                array('sku' => 'simple_product_1', 'price' => 55, '_attribute_set' => 'Default', '_type' => 'simple'),
                false,
                array('price' => 55)
            ),
            'Updating existing product with attributes that do have default values' => array(
                array(
                    'sku' => 'simple_product_2', 'price' => 65, '_attribute_set' => 'Default', '_type' => 'simple',
                    'visibility' => 1, 'msrp_enabled' => 'Yes', 'tax_class_id' => ''
                ),
                false,
                array('price' => 65, 'visibility' => 1, 'msrp_enabled' => 1, 'tax_class_id' => ''),
            ),
            'Adding new product with attributes that don\'t have default values' => array(
                array(
                    'sku' => 'simple_product_3', '_store' => '', '_attribute_set' => 'Default', '_type' => 'simple',
                    '_category' => '_root_category', '_product_websites' => 'base', 'name' => 'Simple Product 3',
                    'price' => 150, 'status' => 1, 'tax_class_id' => '0', 'weight' => 1, 'description' => 'a',
                    'short_description' => 'a', 'visibility' => 1
                ),
                true,
                array(
                    'name' => 'Simple Product 3',
                    'price' => 150, 'status' => 1, 'tax_class_id' => '0', 'weight' => 1, 'description' => 'a',
                    'short_description' => 'a', 'visibility' => 1, 'options_container' => 'container2',
                    'msrp_enabled' => 2, 'msrp_display_actual_price_type' => 4
                )
            ),
            'Adding new product with attributes that do have default values' => array(
                array(
                    'sku' => 'simple_product_4', '_store' => '', '_attribute_set' => 'Default', '_type' => 'simple',
                    '_category' => '_root_category', '_product_websites' => 'base', 'name' => 'Simple Product 4',
                    'price' => 100, 'status' => 1, 'tax_class_id' => '0', 'weight' => 1, 'description' => 'a',
                    'short_description' => 'a', 'visibility' => 2, 'msrp_enabled' => 'Yes',
                    'msrp_display_actual_price_type' => 'In Cart'
                ),
                true,
                array(
                    'name' => 'Simple Product 4',
                    'price' => 100, 'status' => 1, 'tax_class_id' => '0', 'weight' => 1, 'description' => 'a',
                    'short_description' => 'a', 'visibility' => 2, 'options_container' => 'container2',
                    'msrp_enabled' => 1, 'msrp_display_actual_price_type' => 2
                )
            ),
        );
    }
}
