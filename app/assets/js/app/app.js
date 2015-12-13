angular.module('RomanNumeralConverterApp')

    .controller('IndexController', function ($scope, RomanNumeralConverterService) {

        //$scope.arabicNumber = null;
        //$scope.romanNumeral = null;
        $scope.arabicNumber = 3999;
        $scope.romanNumeral = 'MMMCMXCIX';
        $scope.errorMessage = null;

        $scope.arabicToRoman = function (keyEvent) {
            if (!$scope.arabicNumber) {
                $scope.romanNumeral = null;
                return;
            }
            RomanNumeralConverterService.arabicToRoman($scope.arabicNumber).then(function (response) {
                if (response.status !== 200) {
                    $scope.errorMessage = response.data.error;
                    $scope.romanNumeral = null;
                    return;
                }
                $scope.romanNumeral = response.data.data;
                $scope.errorMessage = null;
            });
        };

        $scope.romanToArabic = function (keyEvent) {
            if (!$scope.romanNumeral) {
                $scope.arabicNumber = null
                return;
            }
            RomanNumeralConverterService.romanToArabic($scope.romanNumeral).then(function (response) {
                if (response.status !== 200) {
                    $scope.errorMessage = response.data.error;
                    $scope.arabicNumber = null;
                    return;
                }
                $scope.arabicNumber = response.data.data;
                $scope.errorMessage = null;
            });
        };

    });


