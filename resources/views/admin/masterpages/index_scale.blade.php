@extends('admin.layouts.manager')

@section('header')
        <h1>
            {{ trans('list.scales') }}
        </h1>
@endsection
@section('content')
    @include('alerts.Alerts')
    <div class="row" ng-app="masterDataApp" ng-controller="masterController" ng-cloak>
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
                    <div class="col-md-4">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="project_id">{{ trans('list.filter') }} :</label>
                                <div class="col-sm-5">
                                    <select class="form-control" ng-model="is_active" ng-change="filterData(is_active)">
                                        <option value="!" selected>Active</option>
                                        <option value="!!">In-Active</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2 text-right">
                        <button type="button" ng-click="loadModal()" class="btn btn-primary btn-sm">{{ trans('list.addNewScale') }}</button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th ng-click="sort('value')" class="cursor-pointer">{{ trans('list.scale') }}
                                    <span class="glyphicon sort-icon" ng-show="sortKey=='value'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                                </th>
                                <th>{{ trans('list.description') }}</th>
                                <th>{{ trans('list.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr dir-paginate="record in scales|orderBy:sortKey:reverse|filter:{ deleted_at : is_active }|itemsPerPage:10">
                                <td>@{{ record.value }}</td>
                                <td>@{{ record.description }}</td>
                                <td style="width: 15%;">
                                    <button type="button" class="btn btn-small btn-xs btn-warning" ng-click="editAction(record)">{{ trans('list.edit') }}</button> |
                                    <button type="button" ng-if="!record.deleted_at" class="btn btn-small btn-xs btn-danger" ng-click="deleteModal(record.id)">{{ trans('list.delete') }}</button>
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
                <div id="addScale" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content border-radius">
                            <div class="modal-header">
                                <div class="col-md-1 loader" ng-if="isDisabled"><img src="{{ asset('admin/dist/img/loading.gif') }}" alt="Data uploading...." /></div>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" ng-if="newData.id">{{ trans('list.update') }} {{ trans('list.scale') }}</h4>
                                <h4 class="modal-title" ng-if="!newData.id">{{ trans('list.addNewScale') }}</h4>
                            </div>
                            <div class="modal-body">
                                <form ng-submit="submitForm()" class="form-horizontal" enctype="multipart/form-data">
                                    <input type="hidden" name="id" ng-model="newData.id" value="" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="value">{{ trans('list.scale') }}:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="value" ng-model="newData.value" placeholder="Scale" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="description">{{ trans('list.description') }}:</label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control custom-textarea-size" name="description" ng-model="newData.description"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12 text-center">
                                            <button type="submit" ng-if="newData.id" ng-disabled="isDisabled" class="btn btn-success">{{ trans('list.update') }}</button>
                                            <button type="submit" ng-if="!newData.id" ng-disabled="isDisabled" class="btn btn-primary">{{ trans('list.submit') }}</button>
                                            <button type="button" class="btn btn-danger" ng-click="closeForm()">Cancel</button>
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
                                <h4 class="modal-title">{{ trans('list.delete') }} {{ trans('list.scale') }}</h4>
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
    <script src="{{ asset('/js/ng-file-upload-shim.min.js') }}"></script>
    <script src="{{ asset('/js/ng-file-upload.min.js') }}"></script>
    <script src="{{ asset('/js/masterapp.js') }}"></script>
    <script src="{{ asset('/js/dirPagination.js') }}"></script>
    <script>
        myApp.constant("CSRF_TOKEN", '{!! csrf_token() !!}');
        myApp.constant("data_type", '1');
        myApp.constant("IMG_PATH", '{!! url('uploads').'/' !!}');
    </script>
    <script src="{{ asset('/js/controller/masterController.js') }}"></script>
    <script src="{{ asset('/js/service/masterService.js') }}"></script>
@endsection