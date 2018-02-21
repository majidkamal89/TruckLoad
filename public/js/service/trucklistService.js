angular.module('trucklistService', [])
.factory('Trucklist', function($http, Upload){
	
	return {
		get: function(){
			return $http.get('/dashboard/lasslister/list');
		},
		getCustomers: function(){
			return $http.get('/dashboard/lasslister/customers');
		},
		getDrivers: function(){
			return $http.get('/dashboard/lasslister/drivers');
		},
		getProjects: function(customer_id){
			return $http.get('/dashboard/lasslister/projects/'+customer_id);
		},
		getVehicles: function(project_id){
			return $http.get('/dashboard/lasslister/vehicles/'+project_id);
		},
		save: function(newData){
			return $http({
				method: 'POST',
				url: '/dashboard/lasslister/save',
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
				data: $.param(newData)
			});
		},
		deleteRow: function(id){
			return $http({
				method: 'GET',
				url: '/dashboard/lasslister/delete/'+id,
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
			});
		},
		saveAttachment: function(newData){
			return Upload.upload({
				method: 'POST',
				url: '/dashboard/lasslister/attachment/save',
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
				data: newData
			});
		}
	}
})