<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Orm\Generator\Type;

/**
 * Mapping type for TINYINT SQL values
 *
 * @author Romain Cottard
 */
class TypeTinyint extends TypeAbstract
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->type           = 'int';
        $this->castDb         = '(int)';
        $this->castMethod     = '(int)';
        $this->emptyValue     = '0';
        $this->validatorClass = \Eureka\Component\Validation\Validator\IntegerValidator::class;
    }
}
