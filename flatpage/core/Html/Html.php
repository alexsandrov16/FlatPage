<?php
defined('FLATPAGE') || die;

namespace FlatPage\Core\Html;

/**
 * Html class
 */
class Html
{
    private $header;

    public function __construct()
    {
        //echo 'algo';
    }

    public function metaTags($title, $description = null)
    {
        return new Header($title, $description);
    }

    public function favicon()
    {
        return Header::favicon();
    }

    public function lastUpdate()
    {
        return date ("d/m/Y", filemtime(FP_PAGES.'home.json'));
    }

    public function blockContent($content)
    {
        return (new HtmlContent)->data($content);
    }

    public function stylesheet(String $style, bool $bool = false)
    {
        if (preg_match("/http/i", $style)) {
            return '<link rel="stylesheet" href="' . env('base_url') . $style . '">';
        }

        if ($bool) {
            return  '<link rel="stylesheet" href="' . env('base_url') . '/flatpage/admin/assets/css/' . $style . '">';
        }
        return '<link rel="stylesheet" href="' . env('base_url') . '/template/' . env('template') . '/css/' . $style . '">';
    }

    public function script(String $script, bool $bool = false)
    {
        if (preg_match("/http/i", $script)) {
            return "<script src='{$script}'></script>";
        }

        if ($bool) {
            return  '<script src="' . env('base_url') . '/flatpage/admin/assets/js/' . $script . '"></script>';
        }
        return '<script src="' . env('base_url') . '/template/' . env('template') . '/js/' . $script . '"></script>';
    }
}
