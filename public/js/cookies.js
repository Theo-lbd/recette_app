window.onload = function() {
    var acceptButton = document.getElementById("acceptCookieConsent");
    var consentContainer = document.getElementById("cookieConsentContainer");

    // Vérifier si l'utilisateur a déjà accepté les cookies
    if (!getCookie("cookiesAccepted")) {
        consentContainer.style.display = "block";
    }

    acceptButton.onclick = function() {
        setCookie("cookiesAccepted", "true", 365); // Enregistrer le cookie pour 1 an
        consentContainer.style.display = "none";
    };

    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
};

document.addEventListener('DOMContentLoaded', () => {
    const consentContainer = document.querySelector('.cookie-consent-container');
    const acceptButton = consentContainer.querySelector('.button');
  
    setTimeout(() => {
      consentContainer.classList.add('active');
    }, 1000);
  
    acceptButton.addEventListener('click', () => {
      consentContainer.classList.remove('active');
  
      setTimeout(() => {
        consentContainer.style.display = 'none';
      }, 500); 
    });
  });
  
  