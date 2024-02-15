<?php include "view/header.php"; ?>
<main>
  <section>
    <h1>Modifier la recette</h1>
    <!-- Formulaire pour modifier une recette existante -->
    <form action="index.php?action=updateRecipe" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($recette['id']); ?>">
        <div>
            <label for="nom">Nom de la recette :</label>
            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($recette['nom']); ?>" required>
        </div>
        <div>
            <label for="ingredients">Ingrédients :</label>
            <textarea id="ingredients" name="ingredients" required><?php echo htmlspecialchars($recette['ingredients']); ?></textarea>
        </div>
        <div>
            <label for="instructions">Instructions :</label>
            <textarea id="instructions" name="instructions" required><?php echo htmlspecialchars($recette['instructions']); ?></textarea>
        </div>
        <div>
            <label for="prep_time">Temps de préparation (en minutes) :</label>
            <input type="number" id="prep_time" name="prep_time" class="form-control" value="<?php echo htmlspecialchars($recette['prep_time']); ?>" required>
        </div>
        <div>
            <label for="serving_size">Nombre de portions :</label>
            <input type="number" id="serving_size" name="serving_size" class="form-control" value="<?php echo htmlspecialchars($recette['serving_size']); ?>" required>
        </div>
        <div>
            <label for="category">Catégorie :</label>
            <select id="category" name="category" class="form-control" required>
                <option value="soupe" <?php echo $recette['category'] == 'soupe' ? 'selected' : ''; ?>>Soupe</option>
                <option value="entrée" <?php echo $recette['category'] == 'entrée' ? 'selected' : ''; ?>>Entrée</option>
                <option value="plat" <?php echo $recette['category'] == 'plat' ? 'selected' : ''; ?>>Plat principal</option>
                <option value="dessert" <?php echo $recette['category'] == 'dessert' ? 'selected' : ''; ?>>Dessert</option>
            </select>
        </div>     
        <div>
            <label for="image">Image :</label>
            <input type="file" id="image" name="image" class="form-control">
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
    </form>
</div>
<?php include "view/footer.php"; ?>
