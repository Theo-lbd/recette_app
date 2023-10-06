<?php include 'header.php'; ?>

<h2>Ajouter une nouvelle recette</h2>

<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="index.php?action=add" method="post" enctype="multipart/form-data">
    <label for="nom">Nom de la recette:</label>
    <input type="text" id="nom" name="nom" required value="<?php echo htmlspecialchars($nom ?? ''); ?>"><br><br>
    <label for="image">Image:</label>
    <input type="file" id="image" name="image"><br><br>

    <label for="prep_time">Temps de préparation (minutes):</label>
    <input type="number" id="prep_time" name="prep_time" required value="<?php echo htmlspecialchars($prep_time ?? ''); ?>"><br><br>

    <label for="serving_size">Nombre de personnes:</label>
    <input type="number" id="serving_size" name="serving_size" required value="<?php echo htmlspecialchars($serving_size ?? ''); ?>"><br><br>

    <label for="ingredients">Ingrédients:</label>
    <div id="ingredients-list">
        <input type="text" name="ingredients[]" required>
    </div>
    <button type="button" id="add-ingredient">Ajouter un ingrédient</button>

    <label for="instructions">Instructions:</label>
    <textarea id="instructions" name="instructions" required><?php echo htmlspecialchars($instructions ?? ''); ?></textarea><br><br>

    <label for="category">Catégorie:</label>
        <select name="category" id="category" required>
        <option value="soupe">Soupe</option>
        <option value="entrée">Entrée</option>
        <option value="plat">Plat</option>
        <option value="dessert">Dessert</option>
    </select>


    <input type="submit" value="Ajouter la recette">
</form>

<script src="/public/js/addRecette.js"></script>

<?php include 'footer.php'; ?>
