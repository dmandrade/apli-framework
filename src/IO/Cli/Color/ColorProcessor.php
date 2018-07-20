<?php

namespace Apli\IO\Cli\Color;

/**
 * Class ColorProcessor.
 */
class ColorProcessor implements ColorProcessorInterface
{
    /**
     * Regex used for removing color codes
     *
     * @var    string
     */
    protected static $stripFilter = '/<[\/]?[a-z=;]+>/';
    /**
     * Flag to remove color codes from the output
     *
     * @var    boolean
     */
    public $noColors = false;
    /**
     * Regex to match tags
     *
     * @var    string
     */
    protected $tagFilter = '/<([a-z=;]+)>(.*?)<\/\\1>/s';
    /**
     * Array of ColorStyle objects
     *
     * @var    array
     */
    protected $styles = [];

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->addPredefinedStyles();
    }

    /**
     * Adds predefined color styles to the ColorProcessor object
     *
     * @return  static  Instance of $this to allow chaining.
     */
    protected function addPredefinedStyles()
    {
        $this->addStyle(
            'info',
            new ColorStyle('green', '', ['bold'])
        );

        $this->addStyle(
            'comment',
            new ColorStyle('yellow', '', ['bold'])
        );

        $this->addStyle(
            'question',
            new ColorStyle('black', 'cyan')
        );

        $this->addStyle(
            'error',
            new ColorStyle('white', 'red')
        );

        return $this;
    }

    /**
     * Add a style.
     *
     * @param   string $name The style name.
     * @param   ColorStyle $style The color style.
     *
     * @return  ColorProcessor  Instance of $this to allow chaining.
     */
    public function addStyle($name, ColorStyle $style)
    {
        $this->styles[$name] = $style;

        return $this;
    }

    /**
     * Strip color tags from a string.
     *
     * @param   string $string The string.
     *
     * @return  string
     */
    public static function stripColors($string)
    {
        return preg_replace(static::$stripFilter, '', $string);
    }

    /**
     * Process a string.
     *
     * @param   string $string The string to process.
     *
     * @return  string
     */
    public function process($string)
    {
        preg_match_all($this->tagFilter, $string, $matches);

        if (!$matches) {
            return $string;
        }

        foreach ($matches[0] as $i => $m) {
            if (array_key_exists($matches[1][$i], $this->styles)) {
                $string = $this->replaceColors($string, $matches[1][$i], $matches[2][$i],
                    $this->styles[$matches[1][$i]]);
            } // Custom format
            elseif (strpos($matches[1][$i], '=')) {
                $string = $this->replaceColors($string, $matches[1][$i], $matches[2][$i],
                    ColorStyle::fromString($matches[1][$i]));
            }
        }

        return $string;
    }

    /**
     * Replace color tags in a string.
     *
     * @param   string $text The original text.
     * @param   string $tag The matched tag.
     * @param   string $match The match.
     * @param   ColorStyle $style The color style to apply.
     *
     * @return  mixed
     */
    protected function replaceColors($text, $tag, $match, ColorStyle $style)
    {
        $replace = $this->noColors
            ? $match
            : "\033[".$style."m".$match."\033[0m";

        return str_replace('<'.$tag.'>'.$match.'</'.$tag.'>', $replace, $text);
    }

    /**
     * Method to get property NoColors
     *
     * @return  boolean
     */
    public function getNoColors()
    {
        return $this->noColors;
    }

    /**
     * Method to set property noColors
     *
     * @param   boolean $noColors
     *
     * @return  static  Return self to support chaining.
     */
    public function setNoColors($noColors)
    {
        $this->noColors = $noColors;

        return $this;
    }
}
