(function () {
	'use strict';
	angular
		.module('demoApp', ['angucomplete-alt'])
		.controller('ArticleController', ['$scope',
			function ($scope) {
				$scope.selectedArticle = function(item) {
					$scope.article = item && item.originalObject;
				}
			}
		]);
})();