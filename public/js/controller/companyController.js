
angular.module('companyController', [])
.controller('companyController', function($scope, $http, Company, CSRF_TOKEN, IMG_PATH){

	$scope.isResponse = false;
	$scope.alert_class = '';
	$scope.message = '';
	$scope.delete_id = '';
	$scope.notification = false;
	$scope.isDisabled = false;
	$scope.newData = {};
	
	var loadData = function(){
		$scope.isDisabled = true;
		Company.get()
		.success(function(data){
			$scope.Companies = data;
			$scope.isDisabled = false;
		});
	};
	loadData();
	$scope.sort = function(keyname){
		$scope.sortKey = keyname;   //set the sortKey to the param passed
		$scope.reverse = !$scope.reverse; //if true make it false and vice versa
	},
	
	$scope.loadModal = function(){
		$scope.openModal();
		$scope.newData = {};
	},
	$scope.submitForm = function(file){
		$scope.isDisabled = true;
		$scope.newData._token = CSRF_TOKEN;
		$scope.newData.logo = file;
		Company.save($scope.newData)
		.success(function(data){
			if(data.success){
				loadData();
				$scope.isDisabled = false;
				$scope.isResponse = true;
				$scope.alert_class = 'alert-success';
				$scope.message = data.success;
				$scope.newData = {};
				setTimeout(function(){
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
				setTimeout(function(){
					$scope.isResponse = false;
					$scope.alert_class = '';
					$scope.message = '';
				}, 1500);
			}
		});
	},
	$scope.editAction = function(data){
		$scope.newData = {};
		$scope.newData.company_name = data.company_name;
		$scope.newData.address = data.address;
		$scope.newData.city = data.city;
		$scope.newData.state = data.state;
		$scope.newData.zip_postal = data.zip_postal;
		$scope.newData.logo = IMG_PATH+''+data.logo;
		$scope.newData.id = data.id;
		$scope.openModal();
	},
	$scope.deleteModal = function(id){
		$("#confirmDelete").modal('show');
		$scope.delete_id = id;
	},
	$scope.deleteAction = function(id){
		Company.deleteRow(id)
		.success(function(response){
			loadData();
			$scope.closeForm();
			$scope.notification = true;
			$scope.message = 'Record deleted successfully!';
			setTimeout(function(){
				$scope.notification = false;
				$scope.message = '';
			}, 2000);
		});
	},
	$scope.closeForm = function(){
		$("#addCompany,#confirmDelete").modal('hide');
		$scope.isResponse = false;
		$scope.alert_class = '';
		$scope.message = '';
		$scope.newData = {};
	},
	$scope.openModal = function(){
		$("#addCompany").modal('show');
	}
	
})