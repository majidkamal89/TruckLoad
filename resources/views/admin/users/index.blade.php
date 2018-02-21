@extends('admin.layouts.manager')

@section('header')
    <h1>
        {!! $user_type !!}
    </h1>
@endsection
@section('content')
    <div class="row" ng-app="userApp" ng-controller="userController" ng-cloak>
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
                                <label class="control-label col-sm-4">{{ trans('list.search') }}:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" ng-model="search">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2 text-right">
                        <button type="button" ng-click="loadModal()" class="btn btn-primary btn-sm">{{ trans('list.addNewDriver') }}</button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th ng-click="sort('first_name')" class="cursor-pointer">{{ trans('list.firstName') }}
                                <span class="glyphicon sort-icon" ng-show="sortKey=='first_name'" ng-class="{'glyphicon-chevron-up':reverse,'glyphicon-chevron-down':!reverse}"></span>
                            </th>
                            <th>{{ trans('list.firstName') }}</th>
                            <th>{{ trans('list.emailAddress') }}</th>
                            <th>{{ trans('list.userType') }}</th>
                            <th>{{ trans('list.createdDate') }}</th>
                            <th>{{ trans('list.action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr dir-paginate="record in Users|orderBy:sortKey:reverse|filter:search|itemsPerPage:10">
                            <td>@{{ record.first_name }}</td>
                            <td>@{{ record.last_name }}</td>
                            <td>@{{ record.email }}</td>
                            <td>@{{ record.user_type == 3 ?  'Sjåfør' :  'Administrator' }}</td>
                            <td>@{{ record.created_at }}</td>
                            <td style="width: 15%;">
                                <button type="button" class="btn btn-small btn-xs btn-warning" ng-click="editAction(record)">Edit</button> |
                                <button type="button" class="btn btn-small btn-xs btn-danger" ng-click="deleteModal(record.id)">Delete</button>
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
                <!-- /.box-body -->
                <!-- Add/update Modal -->
                <div id="addDriver" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content border-radius">
                            <div class="modal-header">
                                <div class="col-md-1 loader" ng-if="isDisabled"><img src="{{ asset('admin/dist/img/loading.gif') }}" alt="Data uploading...." /></div>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" ng-if="newData.id">Update Driver</h4>
                                <h4 class="modal-title" ng-if="!newData.id">Add New Driver</h4>
                            </div>
                            <div class="modal-body">
                                <form ng-submit="submitForm()" class="form-horizontal" enctype="multipart/form-data">
                                    <input type="hidden" name="id" ng-model="newData.id" value="" />
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="first_name">{{trans('list.firstName')}}:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="first_name" ng-model="newData.first_name" placeholder="First Name" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="last_name">{{trans('list.lastName')}}:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="last_name" ng-model="newData.last_name" placeholder="Last Name" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="email">{{trans('list.emailAddress')}}:</label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control" name="email" ng-model="newData.email" placeholder="E-Mail Address" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="last_name">{{trans('list.password')}}:</label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" name="password" ng-model="newData.password" placeholder="Password" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="password_confirmation">{{trans('list.confirmPassword')}}:</label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" name="password_confirmation" ng-model="newData.password_confirmation" placeholder="Confirm Password" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12 text-center">
                                            <button type="submit" ng-if="newData.id" ng-disabled="isDisabled" class="btn btn-success">{{trans('list.update')}}</button>
                                            <button type="submit" ng-if="!newData.id" ng-disabled="isDisabled" class="btn btn-primary">{{trans('list.submit')}}</button>
                                            <button type="button" class="btn btn-danger" ng-click="closeForm()">{{trans('list.cancel')}}</button>
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
                                <h4 class="modal-title">{{trans('list.delete')}} {{trans('list.driver')}}</h4>
                            </div>
                            <div class="modal-body">
                                <p>{{trans('list.deleteWarning')}}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('list.no')}}</button>
                                <button type="button" class="btn btn-danger" ng-click="deleteAction(delete_id)">{{trans('list.yes')}}</button>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/userapp.js') }}"></script>
    <script src="{{ asset('/js/dirPagination.js') }}"></script>
    <script>
        myApp.constant("CSRF_TOKEN", '{!! csrf_token() !!}')
        myApp.constant("user_type", '3')
    </script>
    <script src="{{ asset('/js/controller/userController.js') }}"></script>
    <script src="{{ asset('/js/service/userService.js') }}"></script>
@endsection