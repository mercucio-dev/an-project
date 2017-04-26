$(document).ready(function($) {
    $('#zmien_haslo').click(function() {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        $('#zmiana_hasla').addClass('animated bounceInLeft zmiana_hasla_anim').one(animationEnd, function() {
            $('#zmiana_hasla').removeClass('animated bounceInLeft');
        });
    });

    $('#podsumowanie_link').click(function() {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        $('#podsumowanie').addClass('animated bounceInLeft zmiana_hasla_anim').one(animationEnd, function() {
            $('#podsumowanie').removeClass('animated bounceInLeft');
        });
    });

	$("#zmiana").click(function() {
	      $("#formularz_zmiany_hasla").validate({
	      	rules: {
		  	haslo1: {
		  	required: true,
			minlength: 4,
			maxlength: 10
		    	},
		  	haslo2: {
		  	required: true,
			equalTo: "#haslo1",
			minlength: 4,
			maxlength: 10
		    	}
		},
		messages: {
		  haslo1: {
		    required: '<span style="color:red; font-weight: bold">nie może być pustego hasła</span>',
		    minlength: '<span style="color:red; font-weight: bold">wpisz poprawne hasło od 4 do 10 znaków</span>',
		    maxlength: '<span style="color:red; font-weight: bold">wpisz poprawne hasło od 4 do 10 znaków</span>'
		    },
		  haslo2: {
		    required: '<span style="color:red; font-weight: bold">nie może być pustego hasła</span>',
		    equalTo: '<span style="color:red; font-weight: bold">hasła muszą być takie same</span>',
		    minlength: '<span style="color:red; font-weight: bold">wpisz poprawne hasło od 4 do 10 znaków</span>',
		    maxlength: '<span style="color:red; font-weight: bold">wpisz poprawne hasło od 4 do 10 znaków</span>'
		    }
		}
	      });
	});
// koniec pliku
});
