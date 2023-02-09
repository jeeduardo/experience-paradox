@extends('layouts.frontend-novue')

@section('content')
    <div class="success-message">
        Thank you for your order, {{ $fullname }}! Your order reference # is {{ $orderIncrementId }}
        <a class="btn-primary" href="/catalog/gear">Continue to Website</a>
    </div>
 @endsection