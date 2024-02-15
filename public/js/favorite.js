// Ce script gère l'ajout et la suppression des recettes favorites de manière asynchrone.
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.favorite-icon').forEach(function(icon) {
        icon.addEventListener('click', function() {
            const recetteId = this.getAttribute('data-recette-id');
            toggleFavoris(recetteId, this);
        });
    });
});

function toggleFavoris(recetteId, icon) {
    console.log("Basculer l'état des favoris pour la recette ID :", recetteId);

    fetch('index.php?action=toggleFavoris', {
        method: 'POST',
        body: JSON.stringify({ recetteId: recetteId }),
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            icon.classList.toggle('favoris'); // Change l'état de l'icône
        } else {
            console.error('Erreur lors de la mise à jour des favoris:', data.message);
        }
    }).catch(error => console.error('Erreur AJAX:', error));
}
