<?php include_once 'functions/functions.php'; ?>
<article class="recette-card" data-category="<?php echo htmlspecialchars($recette['category']); ?>">
    <div class="card-header">
        <h3 class="card-title"><?php echo htmlspecialchars($recette['nom']); ?></h3>
        <div>
            <i class="uil uil-heart favorite-icon <?= (isset($favoris) && in_array($recette['id'], $favoris)) ? 'favoris' : '' ?>" data-recette-id="<?= $recette['id']; ?>"></i>
        </div>
    </div>

    <div class="card-body">
        <img src="/public/uploads/<?php echo basename(htmlspecialchars($recette['image_path'])); ?>" alt="Image de <?php echo htmlspecialchars($recette['nom']); ?>" class="card-avatar">
        <div class="card-time">
            <span><i class="uil uil-clock icone_card"></i> <?php echo htmlspecialchars($recette['prep_time']); ?> minutes</span>
            <span><i class="uil uil-users-alt icone_card"></i> <?php echo htmlspecialchars($recette['serving_size']); ?></span>
        </div>
        <p><strong>Ingrédients:</strong></p>
        <ul><?php renderIngredients($recette['ingredients']); ?></ul>
        <p><strong>Instructions:</strong></p>
        <p><?php echo nl2br(htmlspecialchars(truncateText($recette['instructions'], 30))); ?></p>
    </div>
    <div class="card-footer">
        <a href="index.php?action=detail&id=<?php echo $recette['id']; ?>">Voir plus</a>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $recette['user_id']): ?>
            <a class="button-a" href="index.php?action=edit&id=<?php echo $recette['id']; ?>">Modifier</a>
            <a class="button-a" href="index.php?action=delete&id=<?php echo $recette['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette recette ?');">Supprimer</a>
        <?php endif; ?>
    </div>
</article>
