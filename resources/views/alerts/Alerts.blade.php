@if (session('success'))
    <div class="alert alert-success alert-dismissable" style="margin:0; color:White;">
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissable" style="margin:0; color:White;">
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        {{ session('error') }}
    </div>
@endif
{{-- for old code--}}
@if (Session::has('message'))
    <div class="alert alert-success alert-dismissable" style="margin:0; color:White;">
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        {{ Session::get('message') }}
    </div>
@endif
