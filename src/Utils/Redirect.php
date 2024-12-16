<?php

namespace Victor\Sae51\Utils;

class Redirect {
    public static function to(string $route) {
        header("Location: $route");
        exit();
    }

    public static function withMessage(string $route, string $message, string $type = 'success') {
        $_SESSION['flash_message'] = [
            'type' => $type,
            'content' => $message
        ];
        header("Location: $route");
        exit();
    }
}
