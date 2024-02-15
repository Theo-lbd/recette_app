// Ce script gère l'envoi asynchrone des commentaires pour éviter le rechargement de la page.
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('comment-form'); 

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(form);
        formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function () {
            if (xhr.status === 200) {
                // Mettez à jour la liste de commentaires ici
                // Vous pouvez ajouter le nouveau commentaire directement ou recharger la liste
            } else {
                // Gérer les erreurs ici
            }
        };

        xhr.send(formData);
    });
});
