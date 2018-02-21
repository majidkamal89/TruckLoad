@extends('admin.layouts.manager')

@section('header')
        <h1>
            {{ trans('list.truckLoads') }}
        </h1>
@endsection
@section('content')
    @include('alerts.Alerts')
    <div class="row" ng-app="loadsApp" ng-controller="loadController" ng-cloak>
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="col-md-1 data-loader" ng-if="isDisabled"><img src="{{ asset('admin/dist/img/loading.gif') }}" alt="Data uploading...." /></div>
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <div class="alert alert-success text-center custom-margin" ng-if="notification">
                            @{{ message }}
                        </div>
                    </div>
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-3 text-right">
                        <button type="button" ng-click="exportPDF()" class="btn btn-danger btn-sm">{{ trans('list.pdf') }}</button>
                        <button type="button" ng-click="loadModal()" class="btn btn-primary btn-sm">{{ trans('list.addNewLoad') }}
                        </button>
                        <a href="{{ route('lasslister') }}" class="btn btn-default btn-sm">{{ trans('list.back') }}</a>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>
                                    <span ng-click="sort('load_time')" class="cursor-pointer">
                                        {{ trans('list.time') }}
                                    <span class="glyphicon sort-icon" ng-show="sortKey=='load_time'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                    </span>
                                    <input type="text" class="form-control-custom" id="load_time_search" name="load_time" ng-model="searchData.load_time" ng-change="searchAction()" />
                                </th>
                                <th>{{ trans('list.from') }} <input type="text" class="form-control-custom" name="from_destination" ng-model="searchData.from_destination" ng-keyup="searchAction()" /></th>
                                <th>{{ trans('list.to') }} <input type="text" class="form-control-custom" name="to_destination" ng-model="searchData.to_destination" ng-keyup="searchAction()" /></th>
                                <th>{{ trans('list.massType') }} <input type="text" class="form-control-custom" name="las_master_data_load_id" ng-model="searchData.las_master_data_load_id" ng-keyup="searchAction()" /></th>
                                <th>{{ trans('list.scale') }} <input type="text" class="form-control-custom" name="las_master_data_volume_id" ng-model="searchData.las_master_data_volume_id" ng-keyup="searchAction()" /></th>
                                <th>{{ trans('list.quantity') }} <input type="text" class="form-control-custom" name="quantity" ng-model="searchData.quantity" ng-keyup="searchAction()" /></th>
                                <th>{{ trans('list.notes') }} </th>
                                <th style="width: 15%;">{{ trans('list.action') }} </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr dir-paginate="record in Loads|orderBy:sortKey:reverse|itemsPerPage:10">
                                <td>@{{ record.load_time | dateFormat }}</td>
                                <td>@{{ record.from_destination }}</td>
                                <td>@{{ record.to_destination }}</td>
                                <td>@{{ record.cargo_load }}</td>
                                <td>@{{ record.cargo_volume }}</td>
                                <td>@{{ record.quantity }}</td>
                                <td>@{{ record.notes }}</td>
                                <td style="width: 15%;">
                                    <button type="button" class="btn btn-small btn-xs btn-warning" ng-click="editAction(record)">{{ trans('list.edit') }} </button> |
                                    <button type="button" class="btn btn-small btn-xs btn-danger" ng-click="deleteModal(record.id)">{{ trans('list.delete') }} </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <dir-pagination-controls
                                max-size="100"
                                direction-links="true"
                                boundary-links="true" >
                        </dir-pagination-controls>
                    </div>
                </div>
                <!-- /.box-body -->
                <!-- Add/update Modal -->
                <div id="addLoad" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content border-radius">
                            <div class="modal-header">
                                <div class="col-md-1 loader" ng-if="isDisabled"><img src="{{ asset('admin/dist/img/loading.gif') }}" alt="Data uploading...." /></div>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" ng-if="newData.id">{{ trans('list.update') }}  {{ trans('list.truckLoad') }} </h4>
                                <h4 class="modal-title" ng-if="!newData.id">{{ trans('list.addNewLoad') }} </h4>
                            </div>
                            <div class="modal-body">
                                <form ng-submit="submitForm()" class="form-horizontal" enctype="multipart/form-data">
                                    <input type="hidden" name="id" ng-model="newData.id" value="" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="load_time">{{ trans('list.time') }} :</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="load_time" readonly="readonly" name="load_time" ng-model="newData.load_time" placeholder="Time" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="from_destination">{{ trans('list.from') }}  {{ trans('list.destination') }}:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="from_destination" ng-model="newData.from_destination" placeholder="From Destination" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="to_destination">{{ trans('list.to') }} {{ trans('list.destination') }}:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="to_destination" ng-model="newData.to_destination" placeholder="To Destination" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4">{{ trans('list.massTypes') }}:</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="las_master_data_load_id" ng-model="newData.las_master_data_load_id" ng-change="getVehicle(newData.las_master_data_load_id)"
                                                    ng-options="load.id as load.value for load in allLoads" required>
                                                <option value="" selected disabled>-- {{ trans('list.select') }} --</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4">{{ trans('list.scale') }}:</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="las_master_data_volume_id" ng-model="newData.las_master_data_volume_id" ng-change="getVehicle(newData.las_master_data_volume_id)"
                                                    ng-options="volume.id as volume.value for volume in allVolume" required>
                                                <option value="" selected disabled>-- {{ trans('list.select') }} --</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="quantity">{{ trans('list.quantity') }}(eg 20.6):</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control search-field" name="quantity" ng-model="newData.quantity" placeholder="Quantity">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="project_code">{{ trans('list.notes') }}:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="notes" ng-model="newData.notes" placeholder="Notes">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12 text-center">
                                            <button type="submit" ng-if="newData.id" ng-disabled="isDisabled" class="btn btn-success">{{ trans('list.update') }}</button>
                                            <button type="submit" ng-if="!newData.id" ng-disabled="isDisabled" class="btn btn-primary">{{ trans('list.submit') }}</button>
                                            <button type="button" class="btn btn-danger" ng-click="closeForm()">{{ trans('list.cancel') }}</button>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="alert @{{ alert_class }} text-center custom-margin" ng-if="isResponse">
                                                @{{ message }}
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
                <!--End Add Modal -->
                <!-- Delete Modal -->
                <div id="confirmDelete" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">{{ trans('list.delete') }} {{ trans('list.truckLoad') }}</h4>
                            </div>
                            <div class="modal-body">
                                <p>{{ trans('list.deleteWarning') }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('list.no') }}</button>
                                <button type="button" class="btn btn-danger" ng-click="deleteAction(delete_id)">{{ trans('list.yes') }}</button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- /.box -->

            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/loadapp.js') }}"></script>
    <script src="{{ asset('/js/dirPagination.js') }}"></script>
    <script>
        myApp.constant("truck_list_id", '{!! $truck_list_id !!}');
        myApp.constant("CSRF_TOKEN", '{!! csrf_token() !!}')
        $(".search-field").numeric();
        $('#load_time').datetimepicker({
            language:  'fr',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 1,
            minView: 0,
            maxView: 1,
            forceParse: 0
        });
        $('#load_time_search').datetimepicker({
            language:  'fr',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            inline: true,
            sideBySide: true
        });
    </script>
    <script src="{{ asset('/js/controller/loadController.js') }}"></script>
    <script src="{{ asset('/js/service/loadService.js') }}"></script>
@endsection