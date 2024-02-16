document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner le bouton d'ajout d'ingrédient sur la page
    var addButton = document.getElementById('add-ingredient');
    if (addButton) {
        addButton.addEventListener('click', function() {
            // Essayer de trouver le conteneur d'ingrédients dans la vue actuelle
            var ingredientsContainer = document.getElementById('ingredients-list') || document.getElementById('ingredients-container');

            // S'assurer que le conteneur d'ingrédients existe avant de continuer
            if (ingredientsContainer) {
                // Créer un nouvel input pour l'ingrédient
                var newIngredientInput = document.createElement('input');
                newIngredientInput.type = 'text';
                newIngredientInput.name = 'ingredients[]';

                // Créer un bouton de suppression pour ce nouvel input
                var removeButton = document.createElement('button');
                removeButton.innerText = 'Supprimer';
                removeButton.type = 'button';
                removeButton.addEventListener('click', function() {
                    // Supprimer l'input, le bouton de suppression, et le saut de ligne lors du clic
                    ingredientsContainer.removeChild(newIngredientInput);
                    ingredientsContainer.removeChild(removeButton);
                    ingredientsContainer.removeChild(brElement);
                });

                // Ajouter les nouveaux éléments au conteneur d'ingrédients
                ingredientsContainer.appendChild(newIngredientInput);
                ingredientsContainer.appendChild(removeButton);

                // Ajouter un élément de saut de ligne pour une meilleure mise en forme
                var brElement = document.createElement('br');
                ingredientsContainer.appendChild(brElement);
            } else {
                // Si le conteneur n'est pas trouvé, afficher une erreur dans la console
                console.error('Le conteneur d\'ingrédients n\'a pas été trouvé.');
            }
        });
    }
});
