@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Master Settings</h2>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs" id="settingsTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general"
                    type="button" role="tab">General</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="whatsapp-tab" data-bs-toggle="tab" data-bs-target="#whatsapp"
                    type="button" role="tab">Whatsapp</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="sms-tab" data-bs-toggle="tab" data-bs-target="#sms"
                    type="button" role="tab">SMS</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment"
                    type="button" role="tab">Payment</button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3" id="settingsTabContent">
        <div class="tab-pane fade show active" id="general" role="tabpanel">
            @include('settings.general')
        </div>
        <div class="tab-pane fade" id="whatsapp" role="tabpanel">
            @include('settings.whatsapp')
        </div>
        <div class="tab-pane fade" id="sms" role="tabpanel">
            @include('settings.sms')
        </div>
        <div class="tab-pane fade" id="payment" role="tabpanel">
            @include('settings.payment')
        </div>
    </div>
</div>
@endsection
