<?php include './admin/view/header.php'; ?>

<main>
    <section>
        <h2>Modifier la Recette</h2>

        <?php if (!empty($errors)): ?>
            <ul class="errors">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="index.php?action=update" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="id" value="<?php echo $recette['id']; ?>">
            <div>
                <label for="nom">Nom de la recette:</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($recette['nom']); ?>" required>
            </div>
            <div id="ingredients-container">
                <label for="ingredients">Ingrédients:</label>
                <?php
                $ingredients = json_decode($recette['ingredients'], true);
                if (!empty($ingredients)) {
                    foreach ($ingredients as $ingredient) {
                        echo '<input type="text" name="ingredients[]" value="' . htmlspecialchars($ingredient) . '"><br>';
                    }
                } else {
                    echo '<input type="text" name="ingredients[]"><br>';
                }
                ?>
                <button type="button" onclick="ajouterChampIngredient()">Ajouter un ingrédient</button>
            </div>
            <div>
                <label for="instructions">Instructions:</label>
                <textarea id="instructions" name="instructions" required><?php echo htmlspecialchars($recette['instructions']); ?></textarea>
            </div>
            <div>
                <label for="prep_time">Temps de préparation (en minutes):</label>
                <input type="number" id="prep_time" name="prep_time" value="<?php echo htmlspecialchars($recette['prep_time']); ?>">
            </div>
            <div>
                <label for="serving_size">Taille de portion:</label>
                <input type="number" id="serving_size" name="serving_size" value="<?php echo htmlspecialchars($recette['serving_size']); ?>">
            </div>
            <div>
                <label for="category">Catégorie:</label>
                <select name="category" id="category" required>
                    <option value="soupe">Soupe</option>
                    <option value="entrée">Entrée</option>
                    <option value="plat">Plat</option>
                    <option value="dessert">Dessert</option>
                </select>
            </div>
            <div>
                <label for="image">Image:</label>
                <input type="file" id="image" name="image">
                <img src="<?php echo $recette['image_path']; ?>" alt="Image de recette" height="100">
            </div>
            <input type="submit" value="Mettre à jour la recette">
        </form>
    </section>
</main>

<script src="../public/js/addRecette.js"></script>

<?php include 'footer.php'; ?>
