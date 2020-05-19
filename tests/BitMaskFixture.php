<?php

declare(strict_types=1);


namespace Cruxinator\BitMask\Tests;

use Cruxinator\BitMask\BitMask;

/**
 * @method self     ONE()
 * @method bool     isONE()
 * @method self     setONE(bool $onOrOff = true)
 * @method self     TWO()
 * @method bool     isTWO()
 * @method self     setTWO(bool $onOrOff = true)
 * @method self     FOUR()
 * @method bool     isFOUR()
 * @method self     setFOUR(bool $onOrOff = true)
 * @method self     EIGHT()
 * @method bool     isEIGHT()
 * @method self     setEIGHT(bool $onOrOff = true)
 * @method self     SIXTEEN()
 * @method bool     isSIXTEEN()
 * @method self     setSIXTEEN(bool $onOrOff = true)
 * @method self     THIRTYTWO()
 * @method bool     isTHIRTYTWO()
 * @method self     setTHIRTYTWO(bool $onOrOff = true)
 */
class BitMaskFixture extends BitMask
{
    protected const NON_FILTERABLE_VALUES = [2 => true, 4 => true];

    const ONE         = 1;
    const TWO         = 2;
    const FOUR        = 4;
    const EIGHT       = 8;
    const SIXTEEN     = 16;
    const THIRTYTWO   = 32;
    Const THIRTYTHREE = 32|1;
}
