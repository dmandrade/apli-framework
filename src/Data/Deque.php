<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file Deque.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 27/08/18 at 10:27
 */

/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 02/08/2018
 * Time: 10:16
 */

namespace Apli\Data;

/**
 * Class Deque is a sequence of values in a contiguous buffer
 * that grows and shrinks automatically.
 * @package Apli\Data
 */
class Deque implements Sequence
{
    use Traits\GenericCollection;
    use Traits\GenericSequence;
    use Traits\SquaredCapacity;

    const MIN_CAPACITY = 8;
}
