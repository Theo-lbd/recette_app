<?php include "view/header.php"; ?>
<main>
  <section>
    <h1>Modifier l'utilisateur</h1>
    <!-- Formulaire pour modifier les informations d'un utilisateur -->
    <form action="index.php?action=updateUser" method="post">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <div>
            <label for="firstname">Prénom :</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>">
        </div>
        <div>
            <label for="lastname">Nom :</label>
            <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>">
        </div>
        <div>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
        </div>
        <button type="submit">Mettre à jour</button>
    </form>
  </section>
</main>
<?php include "view/footer.php"; ?>
