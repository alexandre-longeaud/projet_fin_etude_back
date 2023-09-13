<?php

namespace App\DataFixtures\Provider;


class PictureProvider
{
    private $pictures = [
        'https://www.zupimages.net/up/23/18/3bwm.jpg',
        'https://www.zupimages.net/up/23/18/9b1v.jpg',
        'https://www.zupimages.net/up/23/18/x3az.jpg',
        'https://www.zupimages.net/up/23/18/4dla.jpg',
        'https://www.zupimages.net/up/23/18/4tlv.jpg',
        'https://www.zupimages.net/up/23/18/nwm7.jpg',
        'https://www.zupimages.net/up/23/18/3hf3.jpg',
        'https://www.zupimages.net/up/23/18/cb6t.jpg',
        'https://www.zupimages.net/up/23/18/0f2x.jpg',
        'https://www.zupimages.net/up/23/18/sn8q.jpg',
        'https://www.zupimages.net/up/23/18/gemk.jpg',
        'https://www.zupimages.net/up/23/18/qn02.jpg',
        'https://www.zupimages.net/up/23/18/lmmr.jpg',
        'https://www.zupimages.net/up/23/18/x3r4.jpg',
        'https://www.zupimages.net/up/23/18/gb8y.jpg',
        'https://www.zupimages.net/up/23/18/4a3q.jpg',
        'https://www.zupimages.net/up/23/18/vi6a.jpg',
        'https://www.zupimages.net/up/23/18/gh6c.jpg',
        'https://www.zupimages.net/up/23/18/8ptc.jpg',
        'https://www.zupimages.net/up/23/18/qs7v.jpg',
        'https://zupimages.net/up/23/18/bb7l.jpg',
        'https://zupimages.net/up/23/18/4th0.jpg',
        'https://zupimages.net/up/23/18/9md4.jpg',
        'https://zupimages.net/up/23/18/3bwm.jpg',
        'https://zupimages.net/up/23/18/nlza.jpg',
        'https://zupimages.net/up/23/18/nx4u.jpg',
        'https://zupimages.net/up/23/18/5hbn.jpg',
        'https://zupimages.net/up/23/18/9md4.jpg',
        'https://zupimages.net/up/23/18/81km.jpg',
        'https://zupimages.net/up/23/18/81km.jpg',


    ];

    /**
     * Retourne une image au hasard
     */
    public function pictureUrl()
    {
        return $this->pictures[array_rand($this->pictures)];
    }

}