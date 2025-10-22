
        function togglePassword(inputId, iconId) {
            const passwordField = document.getElementById(inputId);
            const passwordIcon = document.getElementById(iconId);

            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordIcon.classList.remove("bi-eye");
                passwordIcon.classList.add("bi-eye-slash");
            } else {
                passwordField.type = "password";
                passwordIcon.classList.remove("bi-eye-slash");
                passwordIcon.classList.add("bi-eye");
            }
        }
