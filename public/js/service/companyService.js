angular.module('companyService', [])
.factory('Company', function($http, Upload){
	
	return {
		get: function(){
			return $http.get('/api/company/list');
		},
		save: function(newData){
			return Upload.upload({
				method: 'POST',
				url: '/api/company/save',
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
				data: newData
			});
		},
		deleteRow: function(id){
			return $http({
				method: 'GET',
				url: '/api/company/delete/'+id,
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
			});
		}
	}
})