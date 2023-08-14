<?php

namespace App\Services\Scraper;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Panther\Client;
use Symfony\Component\Validator\Constraints\Url as UrlConstraint;
use Symfony\Component\Validator\Validation;

class Parser
{
    private string $url;
    private Client $client;
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->client = Client::createChromeClient(null, [ '--headless', '--disable-dev-shm-usage', '--no-sandbox' ]);
        $this->params = $params;
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

    public function processing(): array
    {
        $result = [];

        $validator = Validation::createValidator();
        $urlConstraint = new UrlConstraint();
        $violations = $validator->validate($this->url, $urlConstraint);
        if (count($violations) > 0) {
            return [];
        }

        $this->client->start();
        $this->client->executeScript('window.scrollTo(0, document.body.scrollHeight);');
        $this->client->request('GET', $this->url);

        $scriptContent = $this->client->executeScript('return document.querySelector(\'script[type="application/ld+json"][data-seo="Product"][data-module-key="script_seo_markupProduct"]\').textContent;');
        $productInfo = json_decode($scriptContent);
        $result['name'] = $productInfo->name;
        $result['image'] = $this->saveImageByUrl($productInfo->image[0]);
        $result['description'] = $productInfo->description;
        $result['price'] = $productInfo->offers->price;

        return $result;
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
