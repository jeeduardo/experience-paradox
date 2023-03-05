@extends('layouts.admin')

@section('content')

<div class="flex flex-row">
    <div class="w-11/12 m-auto my-8">

        <h1 style="text-transform: uppercase;">Admin Login</h1>

        <form action="{{ route('login.action') }}" class="my-8" method="post">
            <div class="login-form-container">
                <div class="grid grid-cols-1 gap-4">
                    <div class="mb-2">
                        <input type="text" class="form-control w-full" style="border: 1px solid #000;" name="username" placeholder="Username" required="required">
                    </div>
        
                    <div class="mb-2">
                        <input type="password" class="form-control w-full" style="border: 1px solid #000;" name="password" placeholder="Password" required="required">
                    </div>
        
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button class="btn btn-primary" title="Login">Login</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection