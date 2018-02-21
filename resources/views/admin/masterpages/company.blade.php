@extends('admin.layouts.manager')

@section('header')
        <h1>
            {{ trans('list.company') }}
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
                    <div class="col-md-4"></div>
                    <div class="col-md-2 text-right"></div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>{{ trans('list.companyName') }}</th>
                                <th>{{ trans('list.address') }}</th>
                                <th>{{ trans('list.zip') }}</th>
                                <th>{{ trans('list.city') }}</th>
                                <th>{{ trans('list.logo') }}</th>
                                <th>{{ trans('list.createdDate') }}</th>
                                <th>{{ trans('list.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr dir-paginate="record in Companies|orderBy:sortKey:reverse|filter:search|itemsPerPage:10">
                                <td>@{{ record.company_name }}</td>
                                <td>@{{ record.address }}</td>
                                <td>@{{ record.zip_postal }}</td>
                                <td>@{{ record.city }}</td>
                                <td><img src="{{ url('uploads') }}/@{{ record.logo }}" alt="No File" class="row-img-size" /></td>
                                <td>@{{ record.created_at }}</td>
                                <td style="width: 7%;">
                                    <button type="button" class="btn btn-small btn-xs btn-warning" ng-click="editCompany(record)">{{ trans('list.edit') }}</button>
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
                <div id="editCompany" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content border-radius">
                            <div class="modal-header">
                                <div class="col-md-1 loader" ng-if="isDisabled"><img src="{{ asset('admin/dist/img/loading.gif') }}" alt="Data uploading...." /></div>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" ng-if="newData.id">{{ trans('list.update') }} {{ trans('list.company') }}</h4>
                            </div>
                            <div class="modal-body">
                                <form ng-submit="submitCompanyForm(newData.logo)" class="form-horizontal" enctype="multipart/form-data">
                                    <input type="hidden" name="id" ng-model="newData.id" value="" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="email">{{ trans('list.companyName') }}:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="company_name" ng-model="newData.company_name" placeholder="Company Name" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">{{ trans('list.address') }}:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="address" ng-model="newData.address" placeholder="Address">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">{{ trans('list.zip') }}:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control search-field" name="zip" ng-model="newData.zip_postal" placeholder="Zip Code">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">{{ trans('list.city') }}:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="city" ng-model="newData.city" placeholder="City">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="pwd">{{ trans('list.logo') }}:</label>
                                        <div class="col-sm-8">
                                            <input type="file" ngf-select ng-model="newData.logo" name="logo"
                                                   accept="image/*" ngf-max-size="3MB" ngf-model-invalid="newData.errorFile">
                                            <i ng-show="newData.errorFile" class="alert-danger">{{ trans('list.maxFileSize') }}</i>
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
                                            <button type="submit" ng-if="newData.id" ng-disabled="isDisabled" class="btn btn-success">{{ trans('list.update') }}</button>
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
        myApp.constant("data_type", 'company');
        myApp.constant("IMG_PATH", '{!! url('uploads').'/' !!}');
    </script>
    <script src="{{ asset('/js/controller/masterController.js') }}"></script>
    <script src="{{ asset('/js/service/masterService.js') }}"></script>
@endsection