document.getElementById('add-ingredient').addEventListener('click', function() {
    var newIngredientInput = document.createElement('input');
    newIngredientInput.type = 'text';
    newIngredientInput.name = 'ingredients[]';
    newIngredientInput.required = true;
    
    var ingredientsList = document.getElementById('ingredients-list');
    ingredientsList.appendChild(document.createElement('br'));
    ingredientsList.appendChild(newIngredientInput);
});
