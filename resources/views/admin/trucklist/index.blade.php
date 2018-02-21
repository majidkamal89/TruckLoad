@extends('admin.layouts.manager')

@section('header')
        <div class="row">
            <div class="col-md-4">
                <h1>
                    {{ trans('list.truckListAndLoads') }}
                </h1>
            </div>
            <div class="col-md-4">
                @include('alerts.Alerts')
            </div>
        </div>
@endsection
@section('content')
    <div class="row" ng-app="trucklistApp" ng-controller="trucklistController" ng-cloak>
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
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-sm-4">{{ trans('list.search') }}:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" ng-model="search">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3 text-right">
                        <button type="button" ng-click="loadModal()" class="btn btn-primary btn-sm">{{ trans('list.addNewTruckList') }}</button>
                        <button type="button" class="btn btn-danger btn-sm exportPdf" disabled>{{ trans('list.exportToPDF') }}</button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>
                                    <div class="checkbox margin-zero">
                                        <label><input type="checkbox" class="check-all" value="all">All</label>
                                    </div>
                                </th>
                                <th ng-click="sort('customer_name')" class="cursor-pointer">{{ trans('list.customerName') }}
                                    <span class="glyphicon sort-icon" ng-show="sortKey=='customer_name'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>
                                <th ng-click="sort('project_name')" class="cursor-pointer">{{ trans('list.projectName') }}
                                    <span class="glyphicon sort-icon" ng-show="sortKey=='project_name'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>
                                <th ng-click="sort('vehicle_name')" class="cursor-pointer">{{ trans('list.vehicleName') }}
                                    <span class="glyphicon sort-icon" ng-show="sortKey=='vehicle_name'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>
                                <th>{{ trans('list.createdDate') }}</th>
                                <th>{{ trans('list.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr dir-paginate="record in TruckLists|orderBy:sortKey:reverse|filter:search|itemsPerPage:10">
                                <td style="width:70px;">
                                    <div class="checkbox margin-zero">
                                        <label><input type="checkbox" class="is-checked" data-id="@{{ record.id }}"></label>
                                    </div>
                                </td>
                                <td>@{{ record.customer_name }}</td>
                                <td>@{{ record.project_name }}</td>
                                <td>@{{ record.vehicle_name }}</td>
                                <td>@{{ record.created_at }}</td>
                                <td style="width: 26%;">
                                    <button type="button" class="btn btn-small btn-xs btn-warning" ng-click="editAction(record)">{{ trans('list.edit') }}</button> |
                                    <button type="button" class="btn btn-small btn-xs btn-danger" ng-click="deleteModal(record.id)">{{ trans('list.delete') }}</button> |
                                    <button type="button" class="btn btn-small btn-xs btn-info" ng-click="attachmentModal(record)">{{ trans('list.addAttachment') }}</button> |
                                    <a href="{{ URL::to('/dashboard/lasslister/loads/') }}/@{{ record.id }}" class="btn btn-small btn-xs btn-success">{{ trans('list.viewLoads') }}</a>
                                    <a href="{{ URL::to('/dashboard/lasslister/pdf/') }}/@{{ record.id }}" class="btn btn-small btn-xs btn-default">{{ trans('list.pdf') }}</a>
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
                <div id="addTruck" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content border-radius">
                            <div class="modal-header">
                                <div class="col-md-1 loader" ng-if="isDisabled"><img src="{{ asset('admin/dist/img/loading.gif') }}" alt="Data uploading...." /></div>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" ng-if="newData.id">{{ trans('list.updateTruckList') }}</h4>
                                <h4 class="modal-title" ng-if="!newData.id">{{ trans('list.addNewTruckList') }}</h4>
                            </div>
                            <div class="modal-body">
                                <form ng-submit="submitForm()" class="form-horizontal" enctype="multipart/form-data">
                                    <input type="hidden" name="id" ng-model="newData.id" value="" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="customer_id">{{ trans('list.customers') }}:</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="customer_id" ng-model="newData.customer_id" ng-change="getProject(newData.customer_id)"
                                                    ng-options="customer.id as customer.customer_name for customer in Customers" required>
                                                <option value="" selected disabled>-- {{ trans('list.select') }} --</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" ng-if="Projects">
                                        <label class="control-label col-sm-4" for="project_id">{{ trans('list.projects') }}:</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="project_id" ng-model="newData.project_id" ng-change="getVehicle(newData.project_id)"
                                                    ng-options="project.id as project.project_name for project in Projects" required>
                                                <option value="" selected disabled>-- {{ trans('list.select') }} --</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" ng-if="Vehicles">
                                        <label class="control-label col-sm-4" for="vehicle_id">{{ trans('list.vehicles') }}:</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="vehicle_id" ng-model="newData.vehicle_id"
                                                    ng-options="vehicle.id as vehicle.description for vehicle in Vehicles" required>
                                                <option value="" selected disabled>-- {{ trans('list.select') }} --</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{--<div class="form-group">
                                        <label class="control-label col-sm-4">Signature:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="signature" ng-model="newData.signature" placeholder="Signature">
                                        </div>
                                    </div>--}}
                                    <div class="form-group" ng-if="Drivers">
                                        <label class="control-label col-sm-4" for="user_id"> {{ trans('list.drivers') }} :</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="user_id" ng-model="newData.user_id"
                                                    ng-options="driver.id as driver.first_name for driver in Drivers" required>
                                                <option value="" selected disabled>--  {{ trans('list.select') }}  --</option>
                                            </select>
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
                                <h4 class="modal-title">{{ trans('list.deleteTruckList')}}</h4>
                            </div>
                            <div class="modal-body">
                                <p>{{ trans('list.deleteWarning')}}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('list.no')}}</button>
                                <button type="button" class="btn btn-danger" ng-click="deleteAction(delete_id)">{{ trans('list.yes')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Delete Modal -->
                <!-- Attachment Modal -->
                <div id="addAttachment" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content border-radius">
                            <div class="modal-header">
                                <div class="col-md-1 loader" ng-if="isDisabled"><img src="{{ asset('admin/dist/img/loading.gif') }}" alt="Data uploading...." /></div>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Truck list</h4>
                            </div>
                            <div class="modal-body">
                                <form ng-submit="submitAttachment(newData.logo)" class="form-horizontal" enctype="multipart/form-data">
                                    <input type="hidden" name="id" ng-model="newData.id" value="" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-4">{{ trans('list.attachmentType')}}:</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="attachment_type" ng-model="newData.attachment_type" required>
                                                <option value="" disabled selected> --{{ trans('list.select') }}-- </option>
                                                <option value="image" selected> {{ trans('list.truckListImage') }} </option>
                                                <option value="signature"> {{ trans('list.signature') }} </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">{{ trans('list.image') }}:</label>
                                        <div class="col-sm-8">
                                            <input type="file" ngf-select ng-model="newData.logo" name="logo"
                                                   accept="image/*" ngf-max-size="3MB" ngf-model-invalid="newData.errorFile" required>
                                            <i ng-show="newData.errorFile" class="alert-danger"> {{ trans('list.maxFileSize') }}.</i>
                                            <div class="col-sm-12 pad">
                                                <img ng-show="newData.logo.$valid" ngf-thumbnail="newData.logo" style="width: 300px;" class="thumb">
                                            </div>
                                            <div class="col-sm-12 pad">
                                                <button ng-if="!newData.id" class="btn btn-default btn-small" ng-click="newData.logo = null" ng-show="newData.logo">{{ trans('list.delete') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12 text-center">
                                            <button type="submit" ng-disabled="isDisabled" class="btn btn-primary">{{ trans('list.submit') }}</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('list.cancel') }}</button>
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
                <!-- End Modal -->

            </div>
            <!-- /.box -->

            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/ng-file-upload-shim.min.js') }}"></script>
    <script src="{{ asset('/js/ng-file-upload.min.js') }}"></script>
    <script src="{{ asset('/js/trucklistapp.js') }}"></script>
    <script src="{{ asset('/js/dirPagination.js') }}"></script>
    <script>
        myApp.constant("CSRF_TOKEN", '{!! csrf_token() !!}')
        $(document).ready(function(){
            setTimeout(function(){
                $('.alert-dismissable').remove();
            }, 3000);
        });
        $(document).on('click', '.check-all', function(){
            if ($(this).is(":checked")){
                $(".is-checked").prop('checked', true);
                $('.exportPdf').prop('disabled', false);
            } else {
                $(".is-checked").each(function(){
                    $(this).prop("checked", false);
                });
                $('.exportPdf').prop('disabled', true);
            }
        });
        $(document).on('click', '.is-checked', function(){
            $(".check-all").prop('checked', false);
            $('.exportPdf').prop('disabled', false);
            if(!$(this).is(":checked")){
                if(!$(".is-checked").is(":checked")){
                    $('.exportPdf').prop('disabled', true);
                }
            }
        });
        $(document).on('click', '.exportPdf', function(){
            if ($('.check-all').is(":checked")){
                var id = 'all';
            } else {
                var id = $(".is-checked").map(function(){
                    if($(this).is(":checked")){
                        return $(this).attr('data-id');
                    }
                }).toArray();
            }
            window.location = '/dashboard/lasslister/pdf/'+id;
        });
    </script>
    <script src="{{ asset('/js/controller/trucklistController.js') }}"></script>
    <script src="{{ asset('/js/service/trucklistService.js') }}"></script>
@endsection