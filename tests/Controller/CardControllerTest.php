<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CardControllerTest extends WebTestCase
{
    public function testDrawCardsSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/random/cards/draw');

        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $response['header']['code']);
        $this->assertCount(10, $response['response']);
        $this->assertArrayHasKey('color', $response['response'][0]);
        $this->assertArrayHasKey('value', $response['response'][0]);
    }

    public function testSortCardsSuccess(): void
    {
        $client = static::createClient();

        $payload = [
            'cards' => [
                ['color' => 'Cœur', 'value' => 'Dame'],
                ['color' => 'Carreaux', 'value' => 'AS'],
                ['color' => 'Trèfle', 'value' => '4'],
                ['color' => 'Pique', 'value' => '9'],
            ]
        ];

        $client->request(
            'POST',
            '/random/cards/sort',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(200, $response['header']['code']);
        $this->assertCount(4, $response['response']);

        $firstCard = $response['response'][0];
        $this->assertEquals('Carreaux', $firstCard['color']);
        $this->assertEquals('AS', $firstCard['value']);
    }

    public function testSortCardsValidationFails(): void
    {
        $client = static::createClient();

        $payload = [
            'cards' => [
                ['colors' => ''],
            ]
        ];

        $client->request(
            'POST',
            '/random/cards/sort',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $responseContent = $client->getResponse()->getContent();
        $response = json_decode($responseContent, true);
        $this->assertEquals(400, $response['header']['code']);
        $this->assertEquals('Les paramètres soumis sont incorrects', $response['header']['message']);
    }


    public function testSortCardsValidationPasses(): void
    {
        $client = static::createClient();

        $payload = [
            'cards' => [
                ['color' => 'Cœur', 'value' => 'Dame'],
                ['color' => 'Carreaux', 'value' => 'AS'],
                ['color' => 'Trèfle', 'value' => '4'],
                ['color' => 'Pique', 'value' => '9'],
            ]
        ];

        $client->request(
            'POST',
            '/random/cards/sort',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $response['header']['code']);
        $this->assertCount(4, $response['response']);

        // Optionally assert that cards are sorted correctly
        $this->assertEquals('Carreaux', $response['response'][0]['color']);
        $this->assertEquals('AS', $response['response'][0]['value']);
    }

}
