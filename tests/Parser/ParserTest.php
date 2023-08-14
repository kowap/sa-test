<?php


namespace App\Tests\Parser;

use App\Services\Scraper\Parser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ParserTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testParserResponse()
    {
        /**
         * @group ignore-deprecations
         */
        $service = $this->getContainer()->get(Parser::class);


        $result = $service->setUrl('https://rozetka.com.ua/361128564/p361128564/')->getData();

        $this->assertIsArray($result);

        $this->assertNotEmpty($result);
    }

}
