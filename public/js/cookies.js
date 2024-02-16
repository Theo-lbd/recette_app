document.addEventListener('DOMContentLoaded', () => {
    const consentContainer = document.querySelector('.cookie-consent-container');
    const acceptButton = document.getElementById("acceptCookieConsent");

    // Vérifie si l'utilisateur a déjà accepté les cookies
    if (!getCookie("cookiesAccepted")) {
        // Affiche la modale après un délai pour s'assurer que la page est chargée
        setTimeout(() => {
            consentContainer.style.transform = 'translateY(0)'; // Fait apparaître la modale
            consentContainer.style.opacity = '1';
        }, 1000);
    }

    acceptButton.onclick = () => {
        setCookie("cookiesAccepted", "true", 365); // Enregistrer le cookie pour 1 an
        consentContainer.style.transform = 'translateY(100%)'; // Fait disparaître la modale
        consentContainer.style.opacity = '0';
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
});
