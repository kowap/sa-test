<?php

namespace App\DTO;


class OfferDTO
{
    public string $price;

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

}
