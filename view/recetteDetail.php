<?php include './admin/view/header.php'; ?>

<main>
    <article>
        <div>
            <h3><?php echo htmlspecialchars($recette['nom']); ?></h3>
        </div>
            
        <div>
            <img src="/public/uploads/<?php echo basename(htmlspecialchars($recette['image_path'])); ?>" alt="Image de <?php echo htmlspecialchars($recette['nom']); ?>" class="card-avatar">
            <div>
                <span><i class="uil uil-clock icone_card"></i> <?php echo htmlspecialchars((string) $recette['prep_time']); ?> minutes</span>
                <span><i class="uil uil-users-alt icone_card"></i> <?php echo htmlspecialchars((string) $recette['serving_size']); ?></span>
            </div>
            <p><strong>Ingrédients :</strong></p>
            <ul>
                <?php 
                $ingredients = json_decode($recette['ingredients'], true);
                if ($ingredients && is_array($ingredients)) {
                    foreach ($ingredients as $ingredient) {
                        echo '<li>' . htmlspecialchars($ingredient) . '</li>';
                    }
                } else {
                    echo '<li>Aucun ingrédient disponible</li>';
                }
                ?>
            </ul>
            <p><strong>Instructions :</strong></p>
            <p><?php echo nl2br(htmlspecialchars($recette['instructions'])); ?></p>
        </div>
        <div class="card-footer">
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $recette['user_id']): ?>
                <a class="button-a" href="index.php?action=delete&id=<?php echo $recette['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette recette ?');">Supprimer</a>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['user_id'])):?>
            <form action="index.php?action=ajouterCommentaire" method="post">
                <textarea name="commentaire" required></textarea>
                <input type="hidden" name="recette_id" value="<?= $recette['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                <input type="submit" value="Ajouter un commentaire">
            </form>
        <?php endif; ?>
        <!-- Affichage des commentaires existants -->
        <?php foreach ($commentaires as $commentaire): ?>
            <div class="commentaire">
                <!-- Affichage du commentaire et des informations de l'utilisateur -->
                <p><strong><?= htmlspecialchars($commentaire['firstname']) . " " . htmlspecialchars($commentaire['lastname']) ?></strong>: <?= htmlspecialchars($commentaire['commentaire']) ?></p>
                
                <!-- Bouton de suppression pour l'auteur du commentaire -->
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $commentaire['user_id']): ?>
                    <a class="button-a" href="index.php?action=supprimerCommentaire&commentaire_id=<?= $commentaire['id'] ?>&recette_id=<?= $recette['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');">Supprimer</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </article>
</main>
<script src="/public/js/commentaires.js"></script>

<?php include 'footer.php'; ?>