<?php

namespace App\DTO;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ResponseDTO
{
    #[SerializedName("name")]
    public string $name;
    #[SerializedName("image")]
    public array $images;
    public string $firstImage;
    #[SerializedName("description")]
    public string $description;
    #[SerializedName("price")]
    public int $price;
    #[SerializedName("offers")]
    public OfferDTO $offers;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): void
    {
        $this->images = $images;
    }


    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getOffers(): OfferDTO
    {
        return $this->offers;
    }

    public function setOffers(OfferDTO $offers): void
    {
        $this->offers = $offers;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getFirstImage(): string
    {
        return $this->firstImage;
    }

    public function setFirstImage(string $firstImage): void
    {
        $this->firstImage = $firstImage;
    }
}


