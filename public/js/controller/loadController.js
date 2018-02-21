
angular.module('loadController', [])
.controller('loadController', function($scope, $http, Load, truck_list_id, CSRF_TOKEN, $timeout){

	$scope.isResponse = false;
	$scope.alert_class = '';
	$scope.message = '';
	$scope.delete_id = '';
	$scope.notification = false;
	$scope.isDisabled = false;
	$scope.newData = {};
	$scope.searchData = {};

	var loadData = function(){
		$scope.isDisabled = true;
		Load.get(truck_list_id)
		.success(function(data){
			$scope.Loads = data;
			$scope.isDisabled = false;
		});
	};
	loadData();
	var cargoLoad = function(){
		Load.getTypeLoad(2)
			.success(function(data){
				$scope.allLoads = data;
			});
	};
	cargoLoad();
	var cargoVolume = function(){
		Load.getTypeVolume(1)
			.success(function(data){
				$scope.allVolume = data;
			});
	};
	cargoVolume();
	$scope.sort = function(keyname){
		$scope.sortKey = keyname;   //set the sortKey to the param passed
		$scope.reverse = !$scope.reverse; //if true make it false and vice versa
	},
	
	$scope.loadModal = function(){
		$scope.openModal();
		$scope.newData = {};
	},
	$scope.submitForm = function(){
		$scope.isDisabled = true;
		$scope.newData._token = CSRF_TOKEN;
		$scope.newData.truck_list_id = truck_list_id;
		Load.save($scope.newData)
		.success(function(data){
			if(data.success){
				loadData();
				$scope.isDisabled = false;
				$scope.isResponse = true;
				$scope.alert_class = 'alert-success';
				$scope.message = data.success;
				$scope.newData = {};
				$timeout(function(){
					$scope.isResponse = false;
					$scope.alert_class = '';
					$scope.message = '';
					$scope.closeForm();
				}, 1500);
			} else {
				$scope.isDisabled = false;
				$scope.isResponse = true;
				$scope.alert_class = 'alert-danger';
				$scope.message = data.error;
				$timeout(function(){
					$scope.isResponse = false;
					$scope.alert_class = '';
					$scope.message = '';
				}, 1500);
			}
		});
	},
	$scope.editAction = function(data){
		$scope.openModal();
		$scope.newData = {};
		$scope.newData.load_time = data.load_time;
		$scope.newData.from_destination = data.from_destination;
		$scope.newData.to_destination = data.to_destination;
		$scope.newData.las_master_data_load_id = data.las_master_data_load_id;
		$scope.newData.las_master_data_volume_id = data.las_master_data_volume_id;
		$scope.newData.quantity = data.quantity;
		$scope.newData.notes = data.notes;
		$scope.newData.id = data.id;
	},
	$scope.deleteModal = function(id){
		$("#confirmDelete").modal('show');
		$scope.delete_id = id;
	},
	$scope.deleteAction = function(id){
		Load.deleteRow(id)
		.success(function(response){
			loadData();
			$scope.closeForm();
			$scope.notification = true;
			$scope.message = 'Record deleted successfully!';
			$timeout(function(){
				$scope.notification = false;
				$scope.message = '';
			}, 2000);
		});
	},
	$scope.closeForm = function(){
		$("#addLoad,#confirmDelete").modal('hide');
		$scope.isResponse = false;
		$scope.alert_class = '';
		$scope.message = '';
		$scope.newData = {};
	},
	$scope.openModal = function(){
		$("#addLoad").modal('show');
	},
	$scope.searchAction = function() {
		Load.searchRecord(truck_list_id, $scope.searchData)
			.success(function (response) {
				$scope.Loads = response;
			});
	},
	$scope.exportPDF = function(){
		$scope.searchData.is_pdf = 1;
		Load.searchRecord(truck_list_id, $scope.searchData)
			.success(function (response) {
				window.location = '/dashboard/lasslister/download/pdf/'+response;
			});
	}
});

myApp.filter('dateFormat', function dateFormat($filter){
	return function(text){
		var  tempdate= new Date(text.replace(/-/g,"/"));
		return $filter('date')(tempdate, "MMMM d, y HH:mm:ss");
	}
});