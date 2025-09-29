<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookingController extends Controller
{
    protected BookingService $service;

    public function __construct(BookingService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $bookings = $this->service->getUserBookings($request->user());
        return BookingResource::collection($bookings);
    }

    public function store(BookingRequest $request): BookingResource
    {
        $booking = $this->service->createBooking($request->user(), $request->validated());
        return new BookingResource($booking);
    }

    public function confirm(Booking $booking): BookingResource
    {
        $booking = $this->service->changeStatusAsProvider($booking, 'confirmed');
        return new BookingResource($booking);
    }

    public function cancel(Booking $booking): BookingResource
    {
        $booking = $this->service->changeStatusAsProvider($booking, 'cancelled');
        return new BookingResource($booking);
    }

}
