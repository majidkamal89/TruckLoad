
angular.module('trucklistController', [])
.controller('trucklistController', function($scope, $http, Trucklist, CSRF_TOKEN, $timeout){

	$scope.isResponse = false;
	$scope.alert_class = '';
	$scope.message = '';
	$scope.delete_id = '';
	$scope.notification = false;
	$scope.isDisabled = false;
	$scope.newData = {};
	
	var loadData = function(){
		$scope.isDisabled = true;
		Trucklist.get()
		.success(function(data){
			$scope.TruckLists = data;
			$scope.isDisabled = false;
		});
	};
	loadData();

	var loadCustomers = function(){
		Trucklist.getCustomers()
			.success(function(data){
				$scope.Customers = data;
			});
	};
	loadCustomers();

	var loadDrivers = function(){
		Trucklist.getDrivers()
			.success(function(data){
				$scope.Drivers = data;
			});
	};
	loadDrivers();

	$scope.getProject = function(id){
		if(id){
			Trucklist.getProjects(id)
				.success(function(data){
					$scope.Projects = data;
				});
		} else {
			$scope.Projects = false;
		}
	},

	$scope.getVehicle = function(id){
		if(id){
			Trucklist.getVehicles(id)
				.success(function(data){
					$scope.Vehicles = data;
				});
		} else {
			$scope.Vehicles = false;
		}
	},

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
		Trucklist.save($scope.newData)
		.success(function(data){
			if(data.success){
				loadData();
				$scope.isResponse = true;
				$scope.isDisabled = false;
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
		$scope.newData = {};
		$scope.getProject(data.customer_id);
		$scope.getVehicle(data.project_id);
		loadDrivers();
		$scope.newData.customer_id = data.customer_id;
		$scope.newData.project_id = data.project_id;
		$scope.newData.vehicle_id = data.vehicle_id;
		$scope.newData.signature = data.signature;
		$scope.newData.user_id = data.user_id;
		$scope.newData.id = data.id;
		$("#addTruck").modal('show');
	},
	$scope.deleteModal = function(id){
		$("#confirmDelete").modal('show');
		$scope.delete_id = id;
	},
	$scope.deleteAction = function(id){
		Trucklist.deleteRow(id)
		.success(function(response){
			loadData();
			loadCustomers();
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
		$("#addTruck,#confirmDelete,#addAttachment").modal('hide');
		$scope.isResponse = false;
		$scope.alert_class = '';
		$scope.message = '';
		$scope.newData = {};
		$scope.Projects = false;
		$scope.Vehicles = false;
	},
	$scope.openModal = function(){
		$("#addTruck").modal('show');
		$scope.isResponse = false;
		$scope.alert_class = '';
		$scope.message = '';
		$scope.newData = {};
		$scope.Projects = false;
		$scope.Vehicles = false;
	},
	$scope.attachmentModal = function(data){
		$scope.newData = {};
		$scope.newData.id = data.id;
		$("#addAttachment").modal('show');
	},
	$scope.submitAttachment = function(file){
		$scope.isDisabled = true;
		$scope.newData._token = CSRF_TOKEN;
		$scope.newData.logo = file;
		Trucklist.saveAttachment($scope.newData)
		.success(function(data){
			if(data.success){
				$scope.isResponse = true;
				$scope.isDisabled = false;
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
	}
	
})