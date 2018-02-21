angular.module('customerService', [])
.factory('Customer', function($http){
	
	return {
		get: function(){
			return $http.get('/dashboard/customer/list');
		},
		save: function(newData){
			return $http({
				method: 'POST',
				url: '/dashboard/customer/save',
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
				data: $.param(newData)
			});
		},
		deleteRow: function(id){
			return $http({
				method: 'GET',
				url: '/dashboard/customer/delete/'+id,
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
			});
		}
	}
})