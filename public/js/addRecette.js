// Ce script gère l'ajout dynamique de champs d'ingrédients dans le formulaire d'ajout de recette.
document.addEventListener('DOMContentLoaded', function() {
    var addButton = document.getElementById('add-ingredient');
    if (addButton) {
        addButton.addEventListener('click', function() {
            var ingredientsList = document.getElementById('ingredients-list');
            var newIngredientInput = document.createElement('input');
            newIngredientInput.type = 'text';
            newIngredientInput.name = 'ingredients[]';
            ingredientsList.appendChild(newIngredientInput);
            
            var removeButton = document.createElement('button');
            removeButton.innerText = 'Supprimer';
            removeButton.type = 'button';
            removeButton.addEventListener('click', function() {
                ingredientsList.removeChild(newIngredientInput);
                ingredientsList.removeChild(removeButton);
            });
            ingredientsList.appendChild(removeButton);

            var brElement = document.createElement('br');
            ingredientsList.appendChild(brElement);
        });
    }
});
