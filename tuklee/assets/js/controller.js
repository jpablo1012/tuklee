$(document).ready(main);

var Controller = function(){
	this.server = "http://localhost:8000";
};

Controller.prototype = {
	main : function(){
		this.loadLogin();
		//this.loadRegister();
		//this.loadHome();
	},

	load : function(id, data, callback){
		var source = $(id).html();
		var template = Handlebars.compile(source);
		var html = template(data);
		var superContainer = $("#super-container");
		superContainer.fadeOut(100);
		superContainer.html(html);
		superContainer.fadeIn(400);
		if(typeof callback === "function"){
			callback();
		}
	},

	loadLogin : function(){
		initController.load("#login-page-template", {}, function(){
			slide_run();
			$("#goToRegister").click(function(){
				initController.loadRegister();
			});
		});
	},

	loadRegister : function(){
		initController.load("#register-page-template", {}, function(){
			$("#register-back").click(function(){
				initController.loadLogin();
			});
		});
	},

	loadHome : function(){
		initController.load("#home-page-template", {});
	},

	validateUser: function() {
        var user = $('#userName').val();
        var password = $('#password').val();
        if (user !== "" && password !== "") {
            var get = initController.server + "/users/login/" + user + "/" + password + "?callback=?";
            $.getJSON(get, {
                format: "json"
            }).done(function(data) {
                if (data.status === 0) {
                    $('#errorMessageLogin').fadeOut(100);
                    var get2 = initController.server + "/users/profileIsCompleted?callback=?";
                    $.getJSON(get2, {
                    }).done(function(data) {
                        if (data.status === 0) {
                            console.log(data);
                            initController.load("#home-page-template", {}, function() {
                                $("#home_back").click(function() {
                                    initController.loadLogin();
                                });
                            });
                        } else {
                            console.log(data);
                            initController.load("#card-page-template", {}, function() {
                                $("#card_back").click(function() {
                                    initController.loadLogin();
                                });
                            });
                        }
                    });
                } else {
                    $('#errorMessageLogin').html('Usuario y/o contraseña son incorrectos');
                    $('#errorMessageLogin').fadeIn(100);
                }
            });
        } else {
            $('#errorMessageLogin').html('Campos vacios');
            $('#errorMessageLogin').fadeIn(100);
        }
    },

    fillCard: function() {
        var name = $("#card_name").val();
        var lastName = $("#card_lastName").val();
        var email = $("#card_email").val();
        var company = $("#card_company").val();
        var phone = $("#card_phone").val();
        var position = $("#card_position").val();

        if (name !== "" && lastName !== "" && email !== "" && company !== "" && phone !== "" && position !== "") {
            var smsAPI = initController.server + "/users/createCard?callback=?";
            $.getJSON(smsAPI, {
                name: name + ' ' + lastName,
                email: email,
                phone: company,
                position: position

            }).done(function(data) {
                if (data.status == 0) {
                    $('#errorMessageCard').fadeOut(100);
                    initController.load("#home-page-template", {}, function() {
                        $("#home_back").click(function() {
                            initController.loadLogin();
                        });
                    });
                } else {
                    $('#errorMessageCard').html('Error servidor');
                    $('#errorMessageCard').fadeIn(100);
                }
            });
        } else {
            $('#errorMessageCard').html('Campos vacios');
            $('#errorMessageCard').fadeIn(100);
        }

    },

	validateRegister : function(){
		var username = $("#register-username").val();
		var lastName = $("#register-lastName").val();
		var name = $("#register-name").val();
		var password = $("#register-password").val();
		var email = $("#register-email").val();

		if(!username.isFullEmpty() && !lastName.isFullEmpty() && !name.isFullEmpty() && !password.isFullEmpty() && !email.isFullEmpty()){
			var registerURL = this.server + "/users/register?callback=?";
			$.getJSON(registerURL, {
				"username" : username,
				"name" : (name + " " + lastName),
				"email" : email,
				"password" : password
			}).done(function(data){
				if(data.status == 2){
					showSomething("#register-errorMessage", "Algunos campos son incorrectos");
				}else if(data.status == 1){
					showSomething("#register-errorMessage", "El nombre de usuario o el correo electrónico ya esta usado");
					$("#register-username").val("");
					$("#register-email").val("");
				}else{
					showSomething("#register-successMessage", "Bienvenido a tuklee, serás redirigido al inicio de sesión");
					setTimeout(function(){
						initController.loadLogin();
					}, 1100);
				}
			});

		}else{
			showSomething("#register-errorMessage", "Faltan campos por llenar");
		}
	}
};


var initController = null;
function main () {
	initController = new Controller();
	initController.main();
}

function showSomething(id, message){
	$(id).fadeOut(100);
	$(id).html(message);
	$(id).fadeIn(100);
}