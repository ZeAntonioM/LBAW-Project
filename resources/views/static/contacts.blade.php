@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/static/contact.css') }}">
@endpush

@section('content')
    <section class="container">
        <img class="map" src="{{ asset('img/map.png') }}">
        <section class="contactInfo">
            <section>
                <h1>&nbsp Contacts</h1>
                <ul class="info">
                    <li>

                        <img class="icon" src="{{ asset('img/email_icon.png') }}" alt="default user icon">
                        <span>&nbsp admin@gmail.com </span>
                    </li>
                    <li>

                        <img class="icon" src="{{ asset('img/phone_icon.png') }}" alt="default user icon">
                        <span>&nbsp +351 224 119 040 </span>
                    </li>
                    <li>

                        <img class="icon" src="{{ asset('img/phone_icon.png') }}" alt="default user icon">
                        <span>&nbsp +351 934 119 040 </span>
                    </li>
                </ul>
            </section>
            <section>
                <h1>&nbsp Address</h1>
                <ul class="info">
                    <li>
                        R. Dr. Roberto Frias, 4200-465 Porto
                    </li>
                    <li>
                        41.17870964921629, -8.596014526238392
                    </li>
                </ul>
            </section>
        </section>
    </section>

@endsection
