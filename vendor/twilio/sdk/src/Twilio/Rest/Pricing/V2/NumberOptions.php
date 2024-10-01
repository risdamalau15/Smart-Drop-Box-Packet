<?php
/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Pricing
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Twilio\Rest\Pricing\V2;

use Twilio\Options;
use Twilio\Values;

abstract class NumberOptions
{
    /**
     * @param string $originationNumber The origination phone number, in [E.164](https://www.twilio.com/docs/glossary/what-e164) format, for which to fetch the origin-based voice pricing information. E.164 format consists of a + followed by the country code and subscriber number.
     * @return FetchNumberOptions Options builder
     */
    public static function fetch(
        
        string $originationNumber = Values::NONE

    ): FetchNumberOptions
    {
        return new FetchNumberOptions(
            $originationNumber
        );
    }

}

class FetchNumberOptions extends Options
    {
    /**
     * @param string $originationNumber The origination phone number, in [E.164](https://www.twilio.com/docs/glossary/what-e164) format, for which to fetch the origin-based voice pricing information. E.164 format consists of a + followed by the country code and subscriber number.
     */
    public function __construct(
        
        string $originationNumber = Values::NONE

    ) {
        $this->options['originationNumber'] = $originationNumber;
    }

    /**
     * The origination phone number, in [E.164](https://www.twilio.com/docs/glossary/what-e164) format, for which to fetch the origin-based voice pricing information. E.164 format consists of a + followed by the country code and subscriber number.
     *
     * @param string $originationNumber The origination phone number, in [E.164](https://www.twilio.com/docs/glossary/what-e164) format, for which to fetch the origin-based voice pricing information. E.164 format consists of a + followed by the country code and subscriber number.
     * @return $this Fluent Builder
     */
    public function setOriginationNumber(string $originationNumber): self
    {
        $this->options['originationNumber'] = $originationNumber;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        $options = \http_build_query(Values::of($this->options), '', ' ');
        return '[Twilio.Pricing.V2.FetchNumberOptions ' . $options . ']';
    }
}

