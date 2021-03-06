<?php
declare(strict_types=1);
namespace Flight;

use Airline\Airline;
use Airport\Airport;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use FastMockTrait;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class CompositeFlightDurationTest extends TestCase
{
    use FastMockTrait;

    public function testShouldThrowExceptionWhenThereIsDateTimeOverlap()
    {
        $this->expectException(Exception\DepartureArrivalException::class);

        $timezone = new DateTimeZone('Europe/Sofia');
        $initialFlight = $this->newDirectFlight(
            new DateTimeImmutable('2017-01-01T02:30:00', $timezone),
            new DateTimeImmutable('2017-01-01T03:30:00', $timezone)
        );
        $finalFlight = $this->newDirectFlight(
            new DateTimeImmutable('2017-01-01T01:30:00', $timezone),
            new DateTimeImmutable('2017-01-01T05:30:00', $timezone)
        );

        new CompositeFlight($this->priceMock(), $initialFlight, $finalFlight);
    }

    public function testShouldReturnDurationOfFullFlight()
    {
        $timezone = new DateTimeZone('Europe/Sofia');
        $initialFlight = $this->newDirectFlight(
            new DateTimeImmutable('2017-01-01T02:30:00', $timezone),
            new DateTimeImmutable('2017-01-01T03:30:00', $timezone)
        );
        $finalFlight = $this->newDirectFlight(
            new DateTimeImmutable('2017-01-01T04:00:00', $timezone),
            new DateTimeImmutable('2017-01-01T07:30:00', $timezone)
        );

        $fullFlight = new CompositeFlight($this->priceMock(), $initialFlight, $finalFlight);

        $actualInterval = $fullFlight->duration();

        self::assertEquals(5, $actualInterval->h);
        self::assertEquals(0, $actualInterval->i);
        self::assertEquals(0, $actualInterval->m);
        self::assertEquals(0, $actualInterval->y);
        self::assertEquals(0, $actualInterval->d);
        self::assertEquals(false, $actualInterval->invert);
    }

    private function newDirectFlight(DateTimeInterface $departure, DateTimeInterface $arrival): DirectFlight
    {
        /** @var ReferenceID $referenceIDMock */
        /** @var Airport $airportMock */
        /** @var Airline $airlineMock */
        /** @var Price $priceMock */
        $referenceIDMock = $this->mock(ReferenceID::class);
        $airportMock = $this->mock(Airport::class);
        $airlineMock = $this->mock(Airline::class);
        $priceMock = $this->priceMock();

        return new DirectFlight(
            $referenceIDMock, $airportMock, $airportMock, $departure, $arrival, $airlineMock, $priceMock);
    }

    /**
     * @return Price|PHPUnit_Framework_MockObject_MockObject
     */
    private function priceMock(): Price
    {
        return $this->mock(Price::class);
    }
}
