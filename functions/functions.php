<?php
/**
 * Affiche les ingrédients d'une recette sous forme de liste HTML.
 * @param string $ingredientsJson La chaîne JSON contenant les ingrédients.
 */
function renderIngredients($ingredientsJson) {
    $ingredients = json_decode($ingredientsJson, true);
    if (is_array($ingredients) || is_object($ingredients)):
        foreach ($ingredients as $ingredient): 
            echo "<li><i class='uil uil-angle-right icone_list'></i>" . htmlspecialchars($ingredient) . "</li>";
        endforeach; 
    else: 
        echo "<li>Aucun ingrédient disponible</li>";
    endif; 
}

/**
 * Tronque un texte à une longueur spécifiée et ajoute "..." à la fin si le texte est tronqué.
 * @param string $text Le texte à tronquer.
 * @param int $maxLength La longueur maximale du texte tronqué.
 * @param string $append Le texte à ajouter à la fin du texte tronqué.
 * @return string Le texte tronqué.
 */

function truncateText($text, $maxLength = 30, $append = '...') {
    if (strlen($text) > $maxLength) {
        $truncated = substr($text, 0, $maxLength) . $append;
    } else {
        $truncated = $text;
    }
    return $truncated;
}
