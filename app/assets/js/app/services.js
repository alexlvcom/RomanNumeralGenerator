angular.module('RomanNumeralConverterApp')

    .service('RomanNumeralConverterService', function ($http) {

        this.arabicToRoman = function (arabicNumber) {
            return this.apiRequest('/api/generate/' + arabicNumber);
        };

        this.romanToArabic = function (romanNumeral) {
            return this.apiRequest('/api/parse/' + romanNumeral);
        };

        this.apiRequest = function (url) {

            console.log('Sending API request to ' + url);

            return $http({
                method: 'GET',
                url: url
            }).then(function successCallback(response) {
                console.log(response);
                return response;
            }, function errorCallback(response) {
                console.error(response);
                return response;
            });
        }
    });


