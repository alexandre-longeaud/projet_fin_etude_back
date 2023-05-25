<?php

namespace App\DataFixtures\Provider;


class IaProvider
{
    private $iaName = [
        'Midjourney',
        'Bluewillow',
        'Dall-E',
        'Stable Diffusion UI',
        'Craiyon',
        'Bing',
        'Canva',
        'NightCafé'
        
    ];

    private $iaLink = [
        'https://www.midjourney.com',
        'https://www.bluewillow.ai',
        'https://openai.com/product/dall-e-2',
        'https://github.com/AUTOMATIC1111/stable-diffusion-webui',
        'https://www.craiyon.com/',
        'https://www.bing.com/create',
        'https://www.canva.com/your-apps/text-to-image',
        'https://nightcafe.studio'
    ];


    /**
     * Retourne une ia au hasard
     */
    public function pictureiaName()
    {
        return $this->iaName[array_rand($this->iaName)];
    }

        /**
     * Retourne un lien ia au hasard
     */
    public function pictureiaLink()
    {
        return $this->iaLink[array_rand($this->iaLink)];
    }

}
