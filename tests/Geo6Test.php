<?php

namespace TijsVerkoyen\Bpost\Geo6\test;

use TijsVerkoyen\Bpost\Geo6;

class Geo6Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Geo6
     */
    private $geo6;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->geo6 = new Geo6('999999', 'A001');
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->geo6 = null;
        parent::tearDown();
    }

    /**
     * Tests Geo6->getTimeOut()
     */
    public function testGetTimeOut()
    {
        $this->geo6->setTimeOut(5);
        $this->assertEquals(5, $this->geo6->getTimeOut());
    }

    /**
     * Tests Geo6->getUserAgent()
     */
    public function testGetUserAgent()
    {
        $this->geo6->setUserAgent('testing/1.0.0');
        $this->assertEquals('PHP Bpost Geo6/' . Geo6::VERSION . ' testing/1.0.0', $this->geo6->getUserAgent());
    }

    /**
     * Tests Geo6->getNearestServicePoint()
     */
    public function testGetNearestServicePoint()
    {
        $response = $this->geo6->getNearestServicePoint('Afrikalaan', '289', '9000');

        $this->assertInternalType('array', $response);

        foreach ($response as $item) {
            $this->assertArrayHasKey('poi', $item);
            $this->assertArrayHasKey('distance', $item);
            $this->assertInstanceOf('TijsVerkoyen\Bpost\Geo6\Poi', $item['poi']);
        }
    }

    /**
     * Tests Geo6->getServicePointDetails()
     */
    public function testGetServicePointDetails()
    {
        $id = '220000';
        $type = '1';
        $response = $this->geo6->getServicePointDetails($id, 'nl', $type);

        $this->assertInstanceOf('TijsVerkoyen\Bpost\Geo6\Poi', $response);
        $this->assertEquals($response->getId(), $id);
        $this->assertEquals($response->getType(), $type);

        try {
            $response = $this->geo6->getServicePointDetails('0');
        } catch (\Exception $e) {
            $this->assertInstanceOf('TijsVerkoyen\Bpost\Exception', $e);
            $this->assertEquals('No match for id : 0 and type : 3', $e->getMessage());
        }

    }

    /**
     * Tests Geo6->getServicePointPage()
     */
    public function testGetServicePointPage()
    {
        $id = '220000';
        $type = '1';
        $response = $this->geo6->getServicePointPage($id, 'nl', $type);

        $this->assertEquals(
            'http://taxipost.geo6.be/Locator?Id=' . $id . '&Language=nl&Type=' . $type . '&Function=page&Partner=999999&AppId=A001&Format=xml',
            $response
        );
    }
}
