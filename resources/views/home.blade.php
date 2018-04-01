@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                   <form>
                     <div class="form-group">
                       <label for="exampleInputEmail1">Email address</label>
                       <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                     </div>

                     <div class="form-group">
                       <label for="exampleInputEmail1">ID</label>
                       <input type="id" class="form-control" value="{{ $user->id }}" readonly>
                     </div>

                     <div class="form-group">
                       <label for="exampleInputEmail1">API Key</label>
                       <input type="api_key" class="form-control" value="{{ $user->api_key }}" readonly>
                     </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
