angular.module('loadService', [])
.factory('Load', function($http){
	
	return {
		get: function(truck_list_id){
			return $http.get('/dashboard/lasslister/loads/list/'+truck_list_id);
		},
		getTypeLoad: function(type){
			return $http.get('/dashboard/lasslister/masterData/'+type);
		},
		getTypeVolume: function(type){
			return $http.get('/dashboard/lasslister/masterData/'+type);
		},
		save: function(newData){
			return $http({
				method: 'POST',
				url: '/dashboard/lasslister/loads/save',
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
				data: $.param(newData)
			});
		},
		deleteRow: function(id){
			return $http({
				method: 'GET',
				url: '/dashboard/lasslister/loads/delete/'+id,
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
			});
		},
		searchRecord: function(truck_list_id, searchData){
			return $http({
				method: 'POST',
				url: '/dashboard/lasslister/loads/list/'+truck_list_id,
				headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
				data: $.param(searchData)
			});
		},
	}
})