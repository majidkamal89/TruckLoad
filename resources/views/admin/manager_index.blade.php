@extends('admin.layouts.manager')

@section('header')
    <h1>
        Dashboard
    </h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{!! $drivers !!}</h3>
                    <p>{{ trans('list.totalDrivers') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{ route('listDrivers') }}" class="small-box-footer">{{ trans('list.goToDrivers') }} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{!! $customers !!}</h3>
                    <p>Total Customers</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('listCustomers') }}" class="small-box-footer">{{ trans('list.goToCustomers') }} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{!! $projects !!}</h3>
                    <p>Total Projects</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('listCustomers') }}" class="small-box-footer">{{ trans('list.goToProjects') }} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner" style="height: 104px;">
                    <div style="width: 36%; float: left;">
                        <h3>{!! $trucklist !!}</h3>
                        <p>Truck list</p>
                    </div>

                    <div style="width: 36%; float: left;">
                        <h3>{!! $loads !!}</h3>
                        <p>Truck Loads</p>
                    </div>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{ route('lasslister') }}" class="small-box-footer">{{ trans('list.goToTruckList') }} <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
@endsection

@section('script')

@endsection