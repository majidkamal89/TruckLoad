angular.module('userService', [])
.factory('User', function($http){
	
	return {
		get: function(){
			return $http.get('/dashboard/settings/driver/list');
		},
		save: function(newData){
			return $http({
				method: 'POST',
				url: '/dashboard/create/user/save',
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
				data: $.param(newData)
			});
		},
		deleteRow: function(id){
			return $http({
				method: 'GET',
				url: '/dashboard/settings/driver/delete/'+id,
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
			});
		}
	}
})