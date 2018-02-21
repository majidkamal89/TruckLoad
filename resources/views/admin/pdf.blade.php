<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LassLister</title>
    <style>
        .container {
            min-height: 550px;
            width: 1550px;
        }

        table.scroll {
            width: 100%;
        }

        h3 {
            margin-top: 0px !important;
        }

        .pad-left {
            padding-left: 0px !important;
        }

        .pad-right {
            padding-right: 0px !important;
        }

        .col-md-4 {
            width: 100%;
        }

        .table-bordered {
            border: 1px solid #ddd;
        }

        .table {
            margin-bottom: 20px;
            max-width: 100%;
            width: 100%;
        }

        table {
            background-color: transparent;
        }

        .table-css {
            text-align: center;
            border: 1px solid rgb(198, 197, 194);
            border-left: 1px solid rgb(198, 197, 194);
            border-bottom: 1px solid rgb(198, 197, 194);
            padding: 7px;
        }

        .table-header {
            text-align: center;
            font-weight: bold;
            border-bottom: 1px solid rgb(198, 197, 194);
            padding: 7px;
        }

        h2 {
            margin-bottom: 5px !important;
            margin-top: 5px !important;
            font-weight: normal !important;
        }

        h1 {
            margin-bottom: 10px !important;
        }
    </style>
</head>
<body class="container">
<div class="row" style="width:100%;padding: 0 4px 0 4px;page-break-after:always;">

    @foreach($truckListData as $data)
        <div style="float:left; position: relative; width:35%; margin-top: 50px;">
            <h1>{!! ucfirst($company_info->company_name) !!}</h1>
            <h2>{!! $company_info->address !!}</h2>
            <h2>{!! $company_info->zip_postal.' '.$company_info->city !!}</h2>
        </div>
        <div style="float:left; position: relative; width:30%; margin-top: 85px; text-align: center;">
            @if($company_info->logo)
                <img src="{{ public_path('/uploads/'.$company_info->logo.'') }}" style="width: 150px;"
                     alt="No Signature"/>
            @endif
        </div>
        <div style="float:left; position: relative; width:35%; text-align: right; margin-top: 50px;">
            <h1>{!! ucfirst($data->customer_name) !!}</h1>
            <h2>{!! $data->project_name.' - '.$data->project_code !!}</h2>
            <h2>{!! $data->vehicle_name !!}</h2>
            <h2>{!! date('F d, Y', strtotime($data->created_at)) !!}</h2>
            <h2>{{ trans('list.truckListID') }} :{!! $data->id !!}</h2>
        </div>

        <div class="col-md-4" style="position: relative; width:100%;">
            <table style="width:100%;" class="table scroll table-bordered table-striped">
                <thead>
                <tr>
                    <th class="table-css">{{ trans('list.nr') }}</th>
                    <th class="table-css">{{ trans('list.time') }}</th>
                    <th class="table-css">{{ trans('list.from') }}From</th>
                    <th class="table-css">{{ trans('list.to') }}</th>
                    <th class="table-css">{{ trans('list.massType') }}</th>
                    <th class="table-css">{{ trans('list.scale') }}</th>
                    <th class="table-css">{{ trans('list.quantity') }}</th>
                </tr>
                </thead>
                <tbody>
                @php
                $i=10001;
                $scaleArray = [];
                $total_scale = 0;
                @endphp
                @foreach($truckLoadData as $loaddata)
                    @if($loaddata->truck_list_id == $data->id)
                        <tr>
                            <td class="table-css">{!! $i !!}</td>
                            <td class="table-css">{!! $loaddata->load_time !!}</td>
                            <td class="table-css">{!! $loaddata->from_destination !!}</td>
                            <td class="table-css">{!! $loaddata->to_destination !!}</td>
                            <td class="table-css">{!! $loaddata->cargo_load !!}</td>
                            <td class="table-css">{!! $loaddata->cargo_volume !!}</td>
                            <td class="table-css">{!! $loaddata->quantity !!}</td>
                        </tr>
                        @php
                        $i++;
                        $total_scale += $loaddata->quantity;
                        if($loaddata->cargo_volume){
                        if(!isset($scaleArray[$loaddata->cargo_volume])){
                        $scaleArray[$loaddata->cargo_volume] = 0;
                        }
                        $scaleArray[$loaddata->cargo_volume] = $scaleArray[$loaddata->cargo_volume] + $loaddata->quantity;
                        }
                        @endphp
                    @endif
                @endforeach

                @foreach($scaleArray as $key => $value)
                    <tr>
                        <td class="table-css" colspan="5"></td>
                        <td class="table-css">Total {!! $key !!}</td>
                        <td class="table-css">{!! number_format($value,2) !!}</td>
                    </tr>
                @endforeach
                @if($total_scale > 0)
                    <tr>
                        <td class="table-css" colspan="5"></td>
                        <td class="table-css">Total</td>
                        <td class="table-css">{!! number_format($total_scale,2) !!}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        <div style="position: relative; width:100%;">
            @if($data->signature_image)
                <div style="max-width: 1400px; text-align: center;">
                    <h1 style="width: 100%; text-align: center; font-weight: bold; font-size:26px;">{{ trans('list.truckListSignature') }}</h1>
                    <img src="{{ public_path('/uploads'.$data->signature_image.'') }}" alt="No Signature"/>
                </div>
            @else
                <p>{{ trans('list.truckListImage') }}</p>
            @endif
        </div>

        <div style="position: relative; width:100%; page-break-inside: avoid;">
            @foreach($trucklistImages as $image)
                @if($image->truck_list_id == $data->id)
                    @if($image->image_name)
                        <div style="width: 1400px; text-align: center;">
                            <h1 style="width: 100%; text-align: center; font-weight: bold; font-size:26px;">{{ trans('list.truckListSignature') }}</h1>
                            <img src="{{ public_path('/uploads/'.$image->image_name.'') }}" alt="No Signature"/>
                        </div>
                    @else
                        <p>{{ trans('list.noImage') }}</p>
                    @endif
                @endif
            @endforeach
        </div>
    @endforeach
</div>
</body>
</html>
