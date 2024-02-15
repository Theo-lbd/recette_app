<?php include 'view/header.php'; ?>
<main>
    <section>
        <h1>Tous les Messages</h1>
        <div class="messages">
                    <!-- Boucle pour afficher chaque message -->
            <?php foreach ($messages as $message): ?>
                <article class="message">
                    <p><strong>Nom :</strong> <?php echo htmlspecialchars($message['name']); ?></p>
                    <p><strong>Email :</strong> <?php echo htmlspecialchars($message['email']); ?></p>
                    <p><strong>Sujet :</strong> <?= htmlspecialchars($message['subject']) ?></p>
                    <p><strong>Message :</strong> <?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                    <div id="replies-<?= $message['id']; ?>">
                        <?php if (!empty($message['replies'])): ?>
                            <?php foreach ($message['replies'] as $reply): ?>
                                <div class="reply">
                                    <p><strong>admin :</strong><?= htmlspecialchars($reply['reply'] ?? "Contenu non disponible"); ?></p>
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
<?php include 'view/footer.php'; ?>

<?php include 'view/header.php'; ?>
<main>
  <section>
    <h1>Tous les Messages</h1>
    <div class="messages">
        <?php foreach ($messages as $message): ?>
            <article class="message">
            <p><strong>Nom :</strong> <?php echo htmlspecialchars($message['name']); ?></p>
            <p><strong>Email :</strong> <?php echo htmlspecialchars($message['email']); ?></p>
            <p><strong>Sujet :</strong> <?= htmlspecialchars($message['subject']) ?></p>
            <p><strong>Message :</strong> <?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
            <div id="replies-<?= $message['id']; ?>">
                <?php if (!empty($message['replies'])): ?>
                    <?php foreach ($message['replies'] as $reply): ?>
                        <div class="reply">
                            <p><strong>admin :</strong><?= htmlspecialchars($reply['reply'] ?? "Contenu non disponible"); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucune réponse pour ce message.</p>
                <?php endif; ?>
            </div>

            <textarea id="reply-text-<?= $message['id']; ?>" placeholder="Réponse..."></textarea>
            <button id="reply-btn-<?= $message['id']; ?>">Envoyer la réponse</button>
        </div>
    <?php endforeach; ?>
</div>
  </section>
</main>
<script src="./public/js/adminReplies.js"></script>
<?php include 'view/footer.php'; ?>
