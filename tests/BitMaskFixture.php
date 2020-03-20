<?php

declare(strict_types=1);


namespace Cruxinator\BitMask\Tests;

use Cruxinator\BitMask\BitMask;

/**
 * @method self     ONE()
 * @method bool     isONE()
 * @method self     setONE(bool $onOrOff)
 * @method self     TWO()
 * @method bool     isTWO()
 * @method self     setTWO(bool $onOrOff)
 * @method self     FOUR()
 * @method bool     isFOUR()
 * @method self     setFOUR(bool $onOrOff)
 * @method self     EIGHT()
 * @method bool     isEIGHT()
 * @method self     setEIGHT(bool $onOrOff)
 * @method self     SIXTEEN()
 * @method bool     isSIXTEEN()
 * @method self     setSIXTEEN(bool $onOrOff)
 * @method self     THIRTYTWO()
 * @method bool     isTHIRTYTWO()
 * @method self     setTHIRTYTWO(bool $onOrOff)
 */
class BitMaskFixture extends BitMask
{
    const ONE       = 1;
    const TWO       = 2;
    const FOUR      = 4;
    const EIGHT     = 8;
    const SIXTEEN   = 16;
    const THIRTYTWO = 32;
}
