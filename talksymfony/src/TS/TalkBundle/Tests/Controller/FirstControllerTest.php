<?php

namespace TS\TalkBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FirstControllerTest extends WebTestCase
{
    public function testWelcome()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Welcome');
    }

}
