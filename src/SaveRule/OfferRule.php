<?php

namespace App\SaveRule;

use App\Entity\Image;
use App\Entity\Offer;
use Symfony\Component\HttpFoundation\Session\Session;

class OfferRule
{
    public const MAX_IMAGES = 5;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $images;

    public function __construct(Offer $offer, Session $session)
    {
        $this->images = $offer->getImages()->map(function (Image $image) {return $image;});
        $this->session = $session;
    }

    public function addRules(Offer $offer): Offer
    {
        $this->addImageRule($offer);

        return $offer;
    }

    private function addFlash(string $type, string $message): void
    {
        $this->session->getFlashBag()->add($type, $message);
    }

    /**
     * @param Offer $offer
     */
    private function addImageRule(Offer $offer): void
    {
        $newImages = $offer->getImages()->map(function (Image $image) {
            return $image;
        });
        $images = array_merge($this->images->toArray(), $newImages->toArray());
        $offer->clearImages();
        for ($i = 0; $i < self::MAX_IMAGES; $i++) {
            $newImage = $images[$i] ?? null;
            if ($newImage) {
                $offer->addImage($newImage);
            }
        }

        if (self::MAX_IMAGES < count($newImages)) {
            $this->addFlash('danger', 'warning.offer.you_can_asign_up_to_5_image_per_offer');
        }
    }
}
