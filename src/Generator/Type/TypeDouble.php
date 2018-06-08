<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Orm\Generator\Type;

/**
 * Mapping type for DOUBLE SQL values
 *
 * @author Romain Cottard
 */
class TypeDouble extends TypeAbstract
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->type           = 'float';
        $this->castDb         = '(float)';
        $this->castMethod     = '(float)';
        $this->emptyValue     = '0.0';
        $this->validatorClass = \Eureka\Component\Validation\Validator\FloatValidator::class;
    }
}
