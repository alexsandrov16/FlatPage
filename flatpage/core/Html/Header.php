<?php
defined('FLATPAGE') || die;

namespace FlatPage\Core\Html;

use DirectoryIterator;
use FlatPage\Core\App;

/**
 * Header class
 */
class Header
{
    protected $generator;
    protected $title;
    /**
     * 165 caracteres max
     */
    protected $description = "Sitio web creado exitosamente con ";
    protected $meta_tags;
    protected $favicon;



    public function __construct($title, $description = null)
    {

        $this->generator = App::$name;
        $this->title = $title;
        if (is_null($description)) {
            $this->description .= $this->generator;
        } else {
            $this->description = $description;
        }

        $this->favicon = self::favicon();



        $this->meta_tags = [
            $this->ogTitle(env('title')),
            $this->ogDescription($this->description),
            $this->ogType('website'),
            $this->ogUrl(env('base_url')),
            $this->ogImage(),
        ];

    }

    private function charset()
    {
        $charset = env('charset');
        return "<meta charset='$charset'>";
    }

    private function generator()
    {
        return '<meta name="generator" content='.$this->generator.'>';
    }

    private function keywords(String $keys = '')
    {
        return "<meta name='keyworks' content='$keys'>";
    }

    private function socialTag()
    {
        $arr = [];

        foreach ($this->meta_tags as $meta) {
            $arr[] = '<meta property="'.$meta['property'].'" content="'.$meta['content'].'">';
        }

        return implode($arr).'<meta name="twitter:card" content="summary_large_image">';
    }

    private function ogTitle(String $title)
    {
        return [
            'property' => 'og:title',
            'content' => $title
        ];
    }

    /**
     * 165 caracteres max
     */
    private function ogDescription(String $description)
    {
        return [
            'property' => 'og:description',
            'content' => $description
        ];
    }
    
    private function ogType(String $type)
    {
        return [
            'property' => 'og:type',
            'content' => $type
        ];
    }
    private function ogUrl(String $url)
    {
        return [
            'property' => 'og:url',
            'content' => $url
        ];
    }
    private function ogImage()
    {
        return [
            'property' => 'og:image',
            'content' => null
        ];
    }

    static function favicon()
    {
        $url = env('base_url');
        $d_favicon = "$url/flatpage/admin/assets/img/favicon.ico";
        if (preg_match("/admin/i", $_SERVER['REQUEST_URI'])) {
            return "<link rel='shortcut icon' href='{$d_favicon}' type='image/x-icon'>";
        }

        $iterator = new DirectoryIterator(ABS_PATH);
        foreach ($iterator as $finfo) {
            if ($finfo->isFile()) {
                foreach (['jpeg', 'jpg', 'png', 'ico'] as $value) {
                    if ($finfo->getExtension() == $value && $finfo->getBasename($value) == 'favicon.') {
                        return "<link rel='shortcut icon' href='{$url}/{$finfo->getBasename()}' type='image/{$value}'>";
                    }
                }
            }
        }
        return "<link rel='shortcut icon' href='{$d_favicon}' type='image/x-icon'>";
    }

    public function __toString()
    {
        return <<<HTML
        {$this->charset()}
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {$this->generator()}
        <title>{$this->title}</title>
        <meta name="description" content="{$this->description}">
        {$this->keywords(env('keywords'))}
        <!--Social-->
        {$this->socialTag()}
        <!--Favicon-->
        {$this->favicon}
        HTML;
    }
}