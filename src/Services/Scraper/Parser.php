<?php

namespace App\Services\Scraper;

use App\DTO\RequestDTO;
use App\DTO\ResponseDTO;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Panther\Client;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Url as UrlConstraint;
use Symfony\Component\Validator\Validation;

class Parser
{
    private string $url;
    private Client $client;
    private $params;
    private $serializer;

    public function __construct(ParameterBagInterface $params, SerializerInterface $serializer)
    {
        $this->client = Client::createChromeClient(null, [ '--headless', '--disable-dev-shm-usage', '--no-sandbox' ]);
        $this->params = $params;
        $this->serializer = $serializer;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getData()
    {
        return $this->processing();
    }

    public function parse(RequestDTO $requestDTO): ResponseDTO
    {
        return $this->processing($requestDTO);
    }

    public function processing(RequestDTO $requestDTO): ResponseDTO
    {
        $this->client->start();
        $this->client->executeScript('window.scrollTo(0, document.body.scrollHeight);');
        $this->client->request('GET', $requestDTO->getUrl());

        $scriptContent = $this->client->executeScript('return document.querySelector(\'script[type="application/ld+json"][data-seo="Product"][data-module-key="script_seo_markupProduct"]\').textContent;');
        $responseDTO = $this->serializer->deserialize($scriptContent, ResponseDTO::class, 'json');

        if (is_array($responseDTO->images) && count($responseDTO->images) > 0) {
            $responseDTO->setFirstImage($this->saveImageByUrl($responseDTO->images[0]));
        }

        return $responseDTO;
    }

    private function saveImageByUrl($url): ?string
    {
        $imageContent = file_get_contents($url);
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        $imageName = uniqid('parsed', true) .'.' . $extension;

        $destinationPath = $this->params->get('photo_directory') . '/'. $imageName;

        if ($imageContent !== false) {
            file_put_contents($destinationPath, $imageContent);
            return $imageName;
        }

        return null;
    }
}
