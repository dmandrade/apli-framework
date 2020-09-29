<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file ColorStyle.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

namespace Apli\IO\Cli\Color;

/**
 * Class ColorStyle.
 */
final class ColorStyle
{
    /**
     * Known colors.
     *
     * @var array
     */
    private static $knownColors = [
        'black'   => 0,
        'red'     => 1,
        'green'   => 2,
        'yellow'  => 3,
        'blue'    => 4,
        'magenta' => 5,
        'cyan'    => 6,
        'white'   => 7,
    ];

    /**
     * Known styles.
     *
     * @var array
     */
    private static $knownOptions = [
        'bold'       => 1,
        'underscore' => 4,
        'blink'      => 5,
        'reverse'    => 7,
    ];

    /**
     * Foreground base value.
     *
     * @var int
     */
    private static $fgBase = 30;

    /**
     * Background base value.
     *
     * @var int
     */
    private static $bgBase = 40;

    /**
     * Foreground color.
     *
     * @var int
     */
    private $fgColor = 0;

    /**
     * Background color.
     *
     * @var int
     */
    private $bgColor = 0;

    /**
     * Array of style options.
     *
     * @var array
     */
    private $options = [];

    /**
     * Constructor.
     *
     * @param string $fg      Foreground color.
     * @param string $bg      Background color.
     * @param array  $options Style options.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($fg = '', $bg = '', $options = [])
    {
        if ($fg) {
            if (false == array_key_exists($fg, static::$knownColors)) {
                throw new \InvalidArgumentException(
                    sprintf('Invalid foreground color "%1$s" [%2$s]',
                        $fg,
                        implode(', ', $this->getKnownColors())
                    )
                );
            }

            $this->fgColor = static::$fgBase + static::$knownColors[$fg];
        }

        if ($bg) {
            if (false == array_key_exists($bg, static::$knownColors)) {
                throw new \InvalidArgumentException(
                    sprintf('Invalid background color "%1$s" [%2$s]',
                        $bg,
                        implode(', ', $this->getKnownColors())
                    )
                );
            }

            $this->bgColor = static::$bgBase + static::$knownColors[$bg];
        }

        foreach ($options as $option) {
            if (false == array_key_exists($option, static::$knownOptions)) {
                throw new \InvalidArgumentException(
                    sprintf('Invalid option "%1$s" [%2$s]',
                        $option,
                        implode(', ', $this->getKnownOptions())
                    )
                );
            }

            $this->options[] = $option;
        }
    }

    /**
     * Get the known colors.
     *
     * @return string
     */
    public function getKnownColors()
    {
        return array_keys(static::$knownColors);
    }

    /**
     * Get the known options.
     *
     * @return array
     */
    public function getKnownOptions()
    {
        return array_keys(static::$knownOptions);
    }

    /**
     * Create a color style from a parameter string.
     *
     * Example: fg=red;bg=blue;options=bold,blink
     *
     * @param string $string The parameter string.
     *
     * @throws \RuntimeException
     *
     * @return ColorStyle Instance of $this to allow chaining.
     */
    public static function fromString($string)
    {
        $fg = '';
        $bg = '';
        $options = [];

        $parts = explode(';', $string);

        foreach ($parts as $part) {
            $subParts = explode('=', $part);

            if (count($subParts) < 2) {
                continue;
            }

            switch ($subParts[0]) {
                case 'fg':
                    $fg = $subParts[1];
                    break;

                case 'bg':
                    $bg = $subParts[1];
                    break;

                case 'options':
                    $options = explode(',', $subParts[1]);
                    break;

                default:
                    throw new \RuntimeException('Invalid option');
                    break;
            }
        }

        return new self($fg, $bg, $options);
    }

    /**
     * Convert to a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getStyle();
    }

    /**
     * Get the translated color code.
     *
     * @return string
     */
    public function getStyle()
    {
        $values = [];

        if ($this->fgColor) {
            $values[] = $this->fgColor;
        }

        if ($this->bgColor) {
            $values[] = $this->bgColor;
        }

        foreach ($this->options as $option) {
            $values[] = static::$knownOptions[$option];
        }

        return implode(';', $values);
    }
}
