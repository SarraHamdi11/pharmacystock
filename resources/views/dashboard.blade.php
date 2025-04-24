@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="display-4 mb-4 text-center">{{ __('Welcome to Stock  ') }}</h2>
    <p class="lead mb-4 text-center">{{ __('Manage your inventory and customers efficiently') }}</p>
    
    <div class="d-flex justify-content-center gap-3 flex-wrap mb-5">
        <a href="/customers" class="btn btn-primary btn-lg shadow-sm">{{ __('Customers') }}</a>
        <a href="/suppliers" class="btn btn-success btn-lg shadow-sm">{{ __('Suppliers') }}</a>
        <a href="/products" class="btn btn-info btn-lg shadow-sm">{{ __('Products') }}</a>
        <a href="/categories" class="btn btn-warning btn-lg shadow-sm">{{ __('Categories') }}</a>
        <a href="/maladies" class="btn btn-secondary btn-lg shadow-sm">{{ __('Diseases') }}</a>
        <a href="{{ route('orders.index') }}" class="btn btn-danger btn-lg shadow-sm">{{ __('Orders') }}</a>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ __('welcome') }}
                        @if(Cookie::has("UserName"))
                            {{Cookie::get("UserName")}}
                        @endif
                    </h5>
                    <form method="POST" action="saveCookie" class="d-flex flex-column align-items-center">
                        @csrf
                        <label for="txtCookie" class="mb-2">{{__('Type your name')}}</label>
                        <input type="text" id="txtCookie" name="txtCookie" class="form-control mb-2 w-75" />
                        <button class="btn btn-primary">{{__('Save Cookie') }}</button>
                    </form>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title">Hello
                        @if(Session::has("SessionName"))
                            {{Session("SessionName")}}
                        @endif
                    </h5>
                    <form method="POST" action="saveSession" class="d-flex flex-column align-items-center">
                        @csrf
                        <label for="txtSession" class="mb-2">{{__('Type your name')}}</label>
                        <input type="text" id="txtSession" name="txtSession" class="form-control mb-2 w-75" />
                        <button class="btn btn-primary">{{__('Save Session') }}</button>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body text-center">
                    <form method="POST" action="saveAvatar" enctype="multipart/form-data" class="d-flex flex-column align-items-center">
                        @csrf
                        <label for="avatarFile" class="mb-2">{{__('Upload your avatar')}}</label>
                        <input type="file" id="avatarFile" name="avatarFile" class="form-control mb-2 w-75" />
                        <button class="btn btn-info">{{__('Save Avatar')}}</button>
                    </form>
                    @if(isset($pic) && $pic)
                        <img src="{{ asset('storage/avatars/'.$pic) }}" alt="Avatar" class="rounded-circle mt-3" width="100" height="100">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
