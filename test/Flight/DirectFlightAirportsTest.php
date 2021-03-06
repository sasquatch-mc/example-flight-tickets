<?php
declare(strict_types=1);
namespace Flight;

use Airline\Airline;
use Airport\Airport;
use DateTimeImmutable;
use DateTimeInterface;
use FastMockTrait;
use PHPUnit\Framework\TestCase;

/**
 * Testing direct flight airport constraints
 */
class DirectFlightAirportsTest extends TestCase
{
    use FastMockTrait;

    public function testShouldThrowExceptionIfAirportsAreTheSame()
    {
        $this->expectException(Exception\SameAirportException::class);

        /** @var ReferenceID $referenceIDMock */
        /** @var Airline $airlineMock */
        /** @var DateTimeInterface $departureDTMock */
        /** @var DateTimeInterface $arrivalDTMock */
        /** @var Price $priceMock */
        $referenceIDMock = $this->mock(ReferenceID::class);
        $airlineMock = $this->mock(Airline::class);
        $departureDTMock = $this->mock(DateTimeImmutable::class);
        $arrivalDTMock = $this->mock(DateTimeImmutable::class);
        $priceMock = $this->mock(Price::class);

        $outboundAirport = new Airport('SOF');
        $inboundAirport = new Airport('SOF');

        new DirectFlight(
            $referenceIDMock, $outboundAirport, $inboundAirport,
            $departureDTMock, $arrivalDTMock, $airlineMock, $priceMock);
    }
}
