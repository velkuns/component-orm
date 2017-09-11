<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Orm\Type;

/**
 * Mapping type for BIGINT SQL values
 *
 * @author Romain Cottard
 */
class TypeBigint extends TypeAbstract
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->type       = 'int';
        $this->castDb     = '(int)';
        $this->castMethod = '(int)';
        $this->emptyValue = '0';
    }
}
