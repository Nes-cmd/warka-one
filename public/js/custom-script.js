function togglePasswordVisibility() {
    var passwordInput = document.getElementById('passwordInput');
    var togglePasswordIcon = document.getElementById('togglePasswordIcon');
    var showIcon = document.getElementById('showIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        showIcon.style.display = 'block';
    } else {
        passwordInput.type = 'password';
        showIcon.style.display = 'none';
    }
}