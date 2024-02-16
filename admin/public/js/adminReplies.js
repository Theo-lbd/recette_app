function submitReply(messageId) {
    const replyText = document.getElementById(`reply-text-${messageId}`).value;

    fetch('index.php?action=submitReply', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json',
        },
        body: `message_id=${messageId}&reply=${encodeURIComponent(replyText)}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('La réponse du réseau n\'était pas ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Ajoutez la réponse au DOM
            const repliesContainer = document.getElementById(`replies-${messageId}`);
        const noReplyText = repliesContainer.querySelector('.no-reply');
            if (noReplyText) {
                noReplyText.textContent = 'Réponses :';
            }
            const newReply = document.createElement('div');

            newReply.classList.add('reply');
    
            // Créez un élément <strong> pour "admin :"
            const adminPrefix = document.createElement('strong');
            adminPrefix.textContent = 'admin :';
    
            const replyTextNode = document.createTextNode(` ${replyText}`);
    
            // Ajoutez le préfixe et le texte de la réponse au nouvel élément de réponse
            newReply.appendChild(adminPrefix); // Ajoute le préfixe en gras
            newReply.appendChild(replyTextNode); // Ajoute le texte de la réponse
    
            repliesContainer.appendChild(newReply);
    
            document.getElementById(`reply-text-${messageId}`).value = '';
        } else {
            alert(data.message || 'Erreur lors de l\'envoi de la réponse.');
        }
    })
    .catch(error => {
        console.error('Erreur lors de la récupération des données:', error);
    });
    
}

document.querySelectorAll('button[id^="reply-btn-"]').forEach(btn => {
    btn.addEventListener('click', function(event) {
        event.preventDefault();
        const messageId = this.id.replace('reply-btn-', '');
        submitReply(messageId);
    });
});


