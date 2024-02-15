// Ce script permet de rechercher des recettes en temps réel et d'afficher les résultats sans recharger la page.
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('search-input');
    searchInput.addEventListener('keyup', function() {
        var query = searchInput.value;

        if (query.length < 3) { // Vous pouvez ajuster ce nombre selon vos besoins
            document.getElementById('search-results').innerHTML = '';
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('search-results').innerHTML = xhr.responseText;
            }
        };
        xhr.open('GET', '././ajax/searchRecettes.php?query=' + encodeURIComponent(query), true);
        xhr.send();
    });
});
