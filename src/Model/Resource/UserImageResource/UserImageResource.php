<?php

namespace Jikan\Model\Resource\UserImageResource;

/**
 * Class UserImageResource
 * @package Jikan\Model\Resource\UserImageResource
 */
class UserImageResource
{
    /**
     * @var Jpg
     */
    private Jpg $jpg;

    /**
     * @var Webp
     */
    private Webp $webp;

    /**
     * @param string|null $imageUrl
     * @return UserImageResource
     */
    public static function factory(?string $imageUrl): self
    {
        $instance = new self();

        $instance->jpg = Jpg::factory($imageUrl);
        $instance->webp = Webp::factory($imageUrl);

        return $instance;
    }

    /**
     * @return Jpg
     */
    public function getJpg(): Jpg
    {
        return $this->jpg;
    }

    /**
     * @return Webp
     */
    public function getWebp(): Webp
    {
        return $this->webp;
    }
}
