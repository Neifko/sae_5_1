<?php

namespace Procrastinateur\Sae51\Utils;

class View
{
    public static function render(string $view, array $data = [])
    {
        // Convertir les données en variables individuelles
        extract($data);

        // Capturer le contenu de la vue
        ob_start();
        include __DIR__ . '/../Views/pages/' . $view . '.php';
        $content = ob_get_clean();

        // Charger le layout principal avec le contenu
        include __DIR__ . '/../Views/layouts/main.php';
    }
}