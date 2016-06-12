<?php

namespace Linku\Linku;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class InlineCss
{

    /**
     * Filename of the view to render
     * @var string
     */
    private $view;
    /**
     * Data - passed to view
     * @var array
     */
    private $data;

    /**
     * @param string $view Filename/path of view to render
     * @param array $data Data of email
     */
    public function __construct($view, array $data)
    {
        // Render the email view
        $this->view = view($view, $data)->render();
        $this->data = $data;
    }

    /**
     * Convert to inlined CSS
     *
     * @return string
     * @throws \TijsVerkoyen\CssToInlineStyles\Exception
     */
    public function convert()
    {
        $converter = new CssToInlineStyles();
        $content =  $converter->convert($this->view);

        return $content;
    }

}