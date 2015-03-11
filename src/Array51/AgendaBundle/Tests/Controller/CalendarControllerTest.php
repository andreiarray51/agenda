<?php

namespace Array51\AgendaBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalendarControllerTest extends WebTestCase
{
    /**
     * @var string
     */
    private $route = '/calendar';

    public function testCalendarActionWithInvalidDateFormat()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            $this->route,
            [
                'month' => '201512',
                'v' => '1.1',
                'token' => 'tokenforagendarestapplication',
                '_format' => 'json',
            ]
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $content = json_decode($client->getResponse()->getContent(), true);
        $expected = [
            'success' => false,
            'errorMessage' => 'Invalid format for month, required format is YYYY-mm (2015-03)',
        ];
        $this->assertEquals($expected, $content);
    }

    public function testCalendarActionWithNoAccessToken()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            $this->route,
            [
                'month' => '2015-12',
                'v' => '1.1',
                '_format' => 'json',
            ]
        );

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $content = json_decode($client->getResponse()->getContent(), true);
        $expected = [
            'success' => false,
            'errorMessage' => 'A valid token is required',
        ];
        $this->assertEquals($expected, $content);
    }

    public function testCalendarActionWithNoVersion()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            $this->route,
            [
                'month' => '2015-12',
                'token' => 'tokenforagendarestapplication',
                '_format' => 'json',
            ]
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertFalse($content['success']);
    }

    public function testCalendarActionSuccess()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            $this->route,
            [
                'month' => '2015-12',
                'v' => '1.1',
                'token' => 'tokenforagendarestapplication',
                '_format' => 'json',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $content = $client->getResponse()->getContent();
        $expected = '{
              "success": true,
              "calendar": [
                {
                  "day": "2015-12-07T00:00:00+0100",
                  "events": 2
                },
                {
                  "day": "2015-12-11T00:00:00+0100",
                  "events": 1
                },
                {
                  "day": "2015-12-12T00:00:00+0100",
                  "events": 17
                },
                {
                  "day": "2015-12-28T00:00:00+0100",
                  "events": 1
                }
              ]
            }';
        $this->assertJsonStringEqualsJsonString($expected, $content);
    }
}
