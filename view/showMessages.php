<?php include './admin/view/header.php'; ?>

<main>
    <section>
        <h2>Mes Messages</h2>

        <?php if (!empty($messages)): ?>
            <div class="messages">
                <?php foreach ($messages as $message): ?>
                    <div class="message">
                        <p><strong>Nom :</strong> <?= htmlspecialchars($message['name']); ?></p>
                        <p><strong>Email :</strong> <?= htmlspecialchars($message['email']); ?></p>
                        <p><strong>Sujet :</strong> <?= htmlspecialchars($message['subject']); ?></p>
                        <p><strong>Message :</strong> <?= nl2br($message['message']); ?></p>

                        <?php if (!empty($message['replies'])): ?>
                            <div class="replies">
                                <h4>Réponses :</h4>
                                <?php foreach ($message['replies'] as $reply): ?>
                                    <div class="reply">
                                        <p><?= nl2br($reply['reply']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p>Aucune réponse pour ce message.</p>
                        <?php endif; ?>
                        <hr>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Vous n'avez envoyé aucun message.</p>
        <?php endif; ?>
    </section>
</main>

<?php include 'footer.php'; ?>
