    function togglePassword(inputId, iconId){
      const input = document.getElementById(inputId);
      const icon  = document.getElementById(iconId);
      const isPwd = input.type === "password";
      input.type  = isPwd ? "text" : "password";
      icon.classList.toggle("bi-eye", !isPwd);
      icon.classList.toggle("bi-eye-slash", isPwd);
    }
