<?php include 'view/header.php'; ?>
<main>
  <section>
    <h1>Tous les Messages</h1>
    <div class="messages">
        <?php foreach ($messages as $message): ?>
            <article class="message">
                <p><strong>Nom :</strong> <?= htmlspecialchars($message['name']); ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($message['email']); ?></p>
                <p><strong>Sujet :</strong> <?= htmlspecialchars($message['subject']); ?></p>
                <p><strong>Message :</strong> <?= nl2br($message['message']); ?></p> <!-- Supprimez htmlspecialchars pour conserver les caractères spéciaux -->
                <div id="replies-<?= $message['id']; ?>">
                    <?php if (!empty($message['replies'])): ?>
                        <?php foreach ($message['replies'] as $reply): ?>
                            <div class="reply">
                                <p><strong>Admin :</strong> <?= nl2br($reply['reply']); ?></p> <!-- Supprimez htmlspecialchars pour conserver les caractères spéciaux -->
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucune réponse pour ce message.</p>
                    <?php endif; ?>
                </div>
                <form>
                    <textarea id="reply-text-<?= $message['id']; ?>" placeholder="Réponse..."></textarea>
                    <button id="reply-btn-<?= $message['id']; ?>">Envoyer la réponse</button>
                </form>
            </article>
        <?php endforeach; ?>
    </div>
  </section>
</main>
<script src="./public/js/adminReplies.js"></script>
<?php include "../view/footer.php";?>
