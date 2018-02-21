@extends('admin.layouts.app')

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
                <div class="inner" style="height: 104px;">
                    <div style="width: 36%; float: left;">
                        <h3>{!! $managers !!}</h3>
                        <p>{{ trans('list.totalManagers') }}</p>
                    </div>
                    <div style="width: 36%; float: left;">
                        <h3>{!! $drivers !!}</h3>
                        <p>{{ trans('list.totalDrivers') }}</p>
                    </div>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <span class="small-box-footer">{{ trans('list.moreInfo') }} <i class="fa fa-arrow-circle-right"></i></span>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{!! $customers !!}</h3>

                    <p>{{ trans('list.totalCustomers') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <span class="small-box-footer">{{ trans('list.moreInfo') }} <i class="fa fa-arrow-circle-right"></i></span>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{!! $projects !!}</h3>

                    <p>{{ trans('list.totalProjects') }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <span class="small-box-footer">{{ trans('list.moreInfo') }} <i class="fa fa-arrow-circle-right"></i></span>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner" style="height: 104px;">
                    <div style="width: 36%; float: left;">
                        <h3>{!! $trucklist !!}</h3>
                        <p>{{ trans('list.truckList') }}</p>
                    </div>

                    <div style="width: 36%; float: left;">
                        <h3>{!! $loads !!}</h3>
                        <p>{{ trans('list.truckLoads') }}</p>
                    </div>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <span class="small-box-footer">{{ trans('list.moreInfo') }} <i class="fa fa-arrow-circle-right"></i></span>
            </div>
        </div>
        <!-- ./col -->
    </div>
@endsection

@section('script')

@endsection