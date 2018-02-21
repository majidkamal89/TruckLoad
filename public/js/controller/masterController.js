
angular.module('masterController', [])
.controller('masterController', function($scope, $http, Master, CSRF_TOKEN, data_type, $timeout, IMG_PATH){

	$scope.isResponse = false;
	$scope.alert_class = '';
	$scope.message = '';
	$scope.delete_id = '';
	$scope.notification = false;
	$scope.isDisabled = false;
	$scope.newData = {};
	$scope.is_active = '!';
	
	var loadData = function(){
		$scope.isDisabled = true;
		Master.get(data_type)
		.success(function(data){
			if(data_type == 'company'){
				$scope.Companies = data;
			} else {
				$scope.scales = data;
			}
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
	$scope.submitForm = function(){
		$scope.isDisabled = true;
		$scope.newData._token = CSRF_TOKEN;
		$scope.newData.type = data_type;
		Master.save($scope.newData)
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
		$scope.newData = {};
		$scope.newData.value = data.value;
		$scope.newData.description = data.description;
		$scope.newData.id = data.id;
		$scope.openModal();
	},
	$scope.deleteModal = function(id){
		$("#confirmDelete").modal('show');
		$scope.delete_id = id;
	},
	$scope.deleteAction = function(id){
		Master.deleteRow(id)
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
		$("#addScale,#confirmDelete,#editCompany").modal('hide');
		$scope.isResponse = false;
		$scope.alert_class = '';
		$scope.message = '';
		$scope.newData = {};
	},
	$scope.openModal = function(){
		$("#addScale,#editCompany").modal('show');
	},
	$scope.editCompany = function(data){
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
	$scope.submitCompanyForm = function(file){
		$scope.isDisabled = true;
		$scope.newData._token = CSRF_TOKEN;
		$scope.newData.logo = file;
		Master.saveCompany($scope.newData)
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
	$scope.filterData = function(val){
		$scope.is_active = val;
	}
	
})