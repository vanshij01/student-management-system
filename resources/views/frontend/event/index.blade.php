@extends('frontend.layouts.app')
@section('title', 'Event')
@section('styles')
@endsection
@section('content')
    <section class="upcoming-events-page sec-space ">
        <div class="container">
            <a href="{{ route('student.dashboard') }}" class="go-back">
                <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9.76172 1.63672L2.39844 9L9.76172 16.3633L8.86328 17.2617L1.05078 9.44922L0.621094 9L1.05078 8.55078L8.86328 0.738281L9.76172 1.63672Z"
                        fill="#1D1D1B" />
                </svg>
                Go Back</a>
            <div class="com-space ">
                <h3 class="admission_form_title">List of Events</h3>
            </div>

            <div class="upcoming-events-wrapper">
                @foreach ($events as $event)
                    <div class="upcoming-events-box">
                        <div class="event-box">
                            <span class="event-name">{{ $event->name }}</span>
                            <div class="event-box-info">
                                <p><strong>Event Date:</strong>
                                    @if (date('Y-m-d', strtotime($event->start_datetime)) == date('Y-m-d', strtotime($event->end_datetime)))
                                        {{ date('Y-m-d', strtotime($event->start_datetime)) }}
                                    @else
                                        {{ date('Y-m-d', strtotime($event->start_datetime)) }} -
                                        {{ date('Y-m-d', strtotime($event->end_datetime)) }}
                                    @endif
                                </p>
                                <p><strong>Event Time:</strong>
                                    {{ date('h:iA', strtotime($event->start_datetime)) }} -
                                    {{ date('h:iA', strtotime($event->end_datetime)) }}
                                </p>
                                <p><strong>Location:</strong> {{ $event->location }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
@section('scripts')
@endsection
