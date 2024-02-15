<?php include './admin/view/header.php'; ?>

<main>
    <section>
        <h1>Mes recettes</h1>

        <?php include 'filterButtons.php'; ?>

        <div class="admin-dashboard">
            <?php 
            include_once 'functions/functions.php';
            if (!empty($recipes)): ?>
                <?php foreach ($recipes as $recette): ?>
                    <?php include 'recipeCard.php'; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Vous n'avez pas encore ajouté de recettes. <a class="button-a" href="index.php?action=add">Ajoutez votre première recette maintenant</a>!</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
