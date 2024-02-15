document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('comment-form'); // Assurez-vous que votre formulaire a cet ID

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(form);
        formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value); // Ajoutez le jeton CSRF
        var xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // Pour identifier la requête AJAX

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
