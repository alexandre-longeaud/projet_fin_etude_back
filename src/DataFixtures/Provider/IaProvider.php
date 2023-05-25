<?php

namespace App\DataFixtures\Provider;


class IaProvider
{
    private $iaData = [
        [
            'name' => 'Midjourney',
            'link' => 'https://www.midjourney.com'
        ],
        [
            'name' => 'Bluewillow',
            'link' => 'https://www.bluewillow.ai'
        ],
        [
            'name' => 'Dall-E',
            'link' => 'https://openai.com/product/dall-e-2'
        ],
        [
            'name' => 'Stable Diffusion UI',
            'link' => 'https://github.com/AUTOMATIC1111/stable-diffusion-webui'
        ],
        [
            'name' => 'Craiyon',
            'link' => 'https://www.craiyon.com/'
        ],
        [
            'name' => 'Bing',
            'link' => 'https://www.bing.com/create'
        ],
        [
            'name' => 'Canva',
            'link' => 'https://www.canva.com/your-apps/text-to-image'
        ],
        [
            'name' => 'NightCafé',
            'link' => 'https://nightcafe.studio'
        ]
    ];

 /**
     * Retourne le tableau contenant les noms et liens des IAs
     */
    public function getIaData()
    {
        return $this->iaData;
    }
}