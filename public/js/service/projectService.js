angular.module('projectService', [])
.factory('Project', function($http){
	
	return {
		get: function(customer_id){
			return $http.get('/dashboard/project/detail/'+customer_id);
		},
		save: function(newData){
			return $http({
				method: 'POST',
				url: '/dashboard/project/save',
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
				data: $.param(newData)
			});
		},
		deleteRow: function(id){
			return $http({
				method: 'GET',
				url: '/dashboard/project/delete/'+id,
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
			});
		}
	}
})