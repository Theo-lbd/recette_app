document.addEventListener('DOMContentLoaded', function() {
    var addButton = document.getElementById('add-ingredient');
    if (addButton) {
        addButton.addEventListener('click', function() {
            var ingredientsContainer = document.getElementById('ingredients-list') || document.getElementById('ingredients-container');

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
                    ingredientsContainer.removeChild(newIngredientInput);
                    ingredientsContainer.removeChild(removeButton);
                    ingredientsContainer.removeChild(brElement);
                });

                ingredientsContainer.appendChild(newIngredientInput);
                ingredientsContainer.appendChild(removeButton);

                var brElement = document.createElement('br');
                ingredientsContainer.appendChild(brElement);
            } else {
                console.error('Le conteneur d\'ingrédients n\'a pas été trouvé.');
            }
        });
    }
});
