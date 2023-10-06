<div class="recette">
    <h3><?php echo htmlspecialchars($recette['nom']); ?></h3>

    <?php if ($recette['image_path'] !== null && $recette['image_path'] !== ''): ?>
        <img src="/public/uploads/<?php echo basename(htmlspecialchars($recette['image_path'])); ?>" alt="Image de <?php echo htmlspecialchars($recette['nom']); ?>" width="300">
    <?php else:?>
        <p>Aucune image disponible pour <?php echo htmlspecialchars($recette['nom']); ?>.</p>
    <?php endif;?>

    <p><strong>Temps de préparation :</strong> <?php echo htmlspecialchars((string) $recette['prep_time']); ?> minutes</p>
    <p><strong>Nombre de personnes :</strong> <?php echo htmlspecialchars((string) $recette['serving_size']); ?></p>

    <p><strong>Ingrédients:</strong></p>
    <ul>
        <?php 
        $ingredients = json_decode($recette['ingredients'], true);
        if (is_array($ingredients) || is_object($ingredients)):
            foreach ($ingredients as $ingredient): 
        ?>
                <li><?php echo htmlspecialchars($ingredient); ?></li>
        <?php 
            endforeach; 
        else: 
        ?>
            <li>Aucun ingrédient disponible</li>
        <?php endif; ?>
    </ul>

    <p><strong>Instructions:</strong></p>
    <p><?php echo nl2br(htmlspecialchars($recette['instructions'])); ?></p>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $recette['user_id']): ?>
        <a href="index.php?action=delete&id=<?php echo $recette['id']; ?>">Supprimer</a>
    <?php endif; ?>
</div>
