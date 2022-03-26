@extends('layout')

@section('content')
    <main class="login-form col d-flex justify-content-center">
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">

                          {{ $message }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
