<?php
namespace App\Tests\Parser;
use App\DTO\RequestDTO;
use App\DTO\ResponseDTO;
use App\Services\Scraper\Parser;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\Serializer\SerializerInterface;

class ParserTest extends PantherTestCase
{
    public function testParsing()
    {
        $mockedParameterBag = $this->createMock(ParameterBagInterface::class);
        $mockedSerializer = $this->createMock(SerializerInterface::class);

        $parser = new Parser($mockedParameterBag, $mockedSerializer);

        $requestDTO = new RequestDTO();
        $requestDTO->setUrl('https://rozetka.com.ua/361128564/p361128564/');

        $responseDTO = $parser->parse($requestDTO);

        $this->assertInstanceOf(ResponseDTO::class, $responseDTO);

        $this->assertNotEmpty($responseDTO->getName());
        $this->assertNotEmpty($responseDTO->getDescription());
        $this->assertNotEmpty($responseDTO->getFirstImage());
    }
}
