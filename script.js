document.getElementById('registrationForm').addEventListener('submit', function(event) {
    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var mobile = document.getElementById('mobile').value;
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;

    if (name.trim() === '' || email.trim() === '' || mobile.trim() === '' || username.trim() === '' || password.trim() === '') {
        alert('Please fill in all fields');
        event.preventDefault();
        return;
    }

    if (!validateMobileNumber(mobile)) {
        alert('Please enter a valid mobile number');
        event.preventDefault();
        return;
    }
    
});

function validateMobileNumber(mobile) {
    var mobileRegex = /^[0-9]{10}$/;
    return mobileRegex.test(mobile);
}

let captcha;
function generate() {

	document.getElementById("submit").value = "";

	captcha = document.getElementById("image");
	let uniquechar = "";

	const randomchar =
"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	for (let i = 1; i < 5; i++) {
		uniquechar += randomchar.charAt(
			Math.random() * randomchar.length)
	}

	captcha.innerHTML = uniquechar;
}

function printmsg() {
	const usr_input = document
		.getElementById("submit").value;

	if (usr_input == captcha.innerHTML) {
		let s = document.getElementById("key")
			.innerHTML = "Matched";
		generate();
	}
	else {
		let s = document.getElementById("key")
			.innerHTML = "not Matched";
		generate();
	}
}
