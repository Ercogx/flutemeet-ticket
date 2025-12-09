@php /** @var \App\Models\Event $event */ @endphp

@extends('layout')

@section('content')
    <div class="container mx-auto px-4 py-12 max-w-xl">
        <div class="text-center mb-8 animate-fade-in">
            <a href="https://flutemeet.org">
                <img src="/img/logo.png" alt="logo" class="h-24 mx-auto mb-4">
            </a>
            <div class="flex items-center justify-center mb-4">
                <h1 class="text-3xl font-bold text-brand-dark">
                    Waitlist
                </h1>
            </div>

            <livewire:white-list-available :$event />
        </div>
    </div>
@endsection
