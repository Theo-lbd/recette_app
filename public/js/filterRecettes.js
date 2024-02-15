// Ce script permet de filtrer les recettes affichées selon leur catégorie sans recharger la page.
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.filter-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            var category = e.target.getAttribute('data-category');
            filterRecettes(category);
        });
    });
});

function filterRecettes(category) {
    var recettes = document.querySelectorAll('.recette-card');
    var hasVisibleRecettes = false; 
    
    recettes.forEach(function(recette) {
        var recetteCategory = recette.getAttribute('data-category');
        if (category === 'all' || recetteCategory === category) {
            recette.style.display = 'block';
            hasVisibleRecettes = true; 
        } else {
            recette.style.display = 'none';
        }
    });
    var noRecettesMessage = document.getElementById('no-recettes-message');
    noRecettesMessage.style.display = hasVisibleRecettes ? 'none' : 'block';
}

