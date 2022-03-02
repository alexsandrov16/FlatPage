<?php
defined('FLATPAGE') || die;

namespace FlatPage\Core\Html;

/**
 * undocumented class
 */
class HtmlContent
{
    public $time;
    public $blocks;
    public $editor;
    private $methods;

    public function __construct()
    {
        $this->methods = get_class_methods($this);
    }

    public function data(array $content)
    {
        $this->time = $content['time'];
        $this->blocks = $content['blocks'];
        $this->editor = $content['version'];

        return $this->render();
    }

    public function render()
    {
        $html = [];

        foreach ($this->blocks as $block) {
            if (in_array($block['type'], $this->methods)) {
                $html[] = call_user_func_array([$this, $block['type']], $block['data']);
            }
        }


        return implode($html);
    }

    public function paragraph(String $text)
    {
        return "<p>$text</p>";
    }

    public function header(String $text, Int $lavel)
    {
        return "<h$lavel>$text</h$lavel>";
    }

    public function image(array $file, String $caption, Bool $border = false, Bool $stretched = false, Bool $background = false)
    {
        $arr[]= 'fp-img';
        if ($border) $arr[] = 'border';
        if ($stretched) $arr[] = 'stretched';
        if ($background) $arr[] = 'background';

        $class = !empty($arr) ? $this->attClass($arr) : null;

        return "<div $class><img src='" . $file['url'] . "' alt='$caption'></div>";
    }

    public function list(String $style, array $items)
    {
        $class = $this->attClass($style);
        $list = "<ul $class>";
        foreach ($items as $item) {
            $list .= "<li>$item</li>";
        }
        $list .= '</ul>';

        return $list;
    }

    public function code($code)
    {
        return '<pre><code>' . htmlspecialchars($code) . '</code></pre>';
    }

    public function quote($text, $caption, $alignment)
    {
        return <<<HTML
        <blockquote {$this->attClass($alignment)}>
            <p>$text</p>
            <span>$caption</span>
        </blockquote>
        HTML;
    }

    /**
     * Atributo Clase
     *
     * Agrega el atributo clase al elemento especificado
     *
     * @param array|string $class Nombre de la clase
     * @return string
     **/
    public function attClass($class)
    {
        if (is_array($class)) {
            $class = implode(' ', $class);
        }
        return "class='$class'";
    }
}
