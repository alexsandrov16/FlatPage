<?php
defined('FLATPAGE') || die;

namespace FlatPage\App\Controller;

use FlatPage\Core\File\Json;

/**
 * undocumented class
 */
class Home 
{
    public function index()
    {
        view(__FUNCTION__, [
            'title'       => env('title'),
            'description' => env('description'),
            'content'     => Json::get(FP_PAGES . 'home'),
            'menu'        => Json::get(FP_CFG.'data')['menu']
        ]);
    }
}
