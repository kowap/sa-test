<?php

namespace App\DTO;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class RequestDTO
{
    private string $url;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($url, new Assert\Url());

        if (count($violations) > 0) {
            throw new \InvalidArgumentException('Invalid URL provided');
        }

        $this->url = $url;
    }
}
