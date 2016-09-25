@extends('layout.app')
@section('content')
<div style="padding-top: 10%;"></div>
<div class="container-fluid">
    <div class="row">
        @if (session('success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Successful Register!</strong> Please Login.
            </div>
        @endif
        @include('errors/validation')
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">

                    <form class="form-horizontal" role="form" method="POST" action="/auth/login">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-4 control-label">E-Mail address</label>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
