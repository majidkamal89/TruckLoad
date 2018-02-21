<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LassLister</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset('admin/bootstrap/css/bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admin/dist/css/AdminLTE.min.css') }}">
    <![endif]-->
    <!-- jQuery 2.2.3 -->
    <script src="{{ asset('admin/dist/js/jquery-2.2.3.min.js') }}"></script>
    <!-- Bootstrap 3.3.6 -->
</head>
<body>
<div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-yellow">
        <div class="inner">
            Welcome Driver
        </div>
        <div class="icon">
            <i class="ion ion-person-add"></i>
        </div>
        <span class="small-box-footer"><a href="#" class="btn btn-default btn-flat logout-click">Sign out</a></span>
    </div>


    <div class="hide">
        <form method="post" id="logoutForm" action="{{ URL::route('logout') }}">
            {{ csrf_field() }}
        </form>
    </div>
</div>
<script>
    $(document).on('click', '.logout-click', function(e){
        $("#logoutForm").submit();
        e.preventDefault();
    });
</script>
</body>
</html>