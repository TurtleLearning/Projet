<?php
namespace App\Config;

class CSRFProtection {
    const TOKEN_LENGTH = 32; // nb caractères
    const TOKEN_EXPIRY = 3600; // 1 heure
    
    public static function initialize() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (self::needsNewToken()) {
            self::generateNewToken();
        }
    }
    
    public static function getToken() {
        try {
            if (!isset($_SESSION['csrf_token'])) {
                echo "Erreur: Token non trouvé dans la session"; // Debug visible
                return '';
            }
            return $_SESSION['csrf_token'];
        } catch (\Exception $e) {
            echo "Erreur lors de la récupération du token: " . $e->getMessage(); // Debug visible
            return '';
        }
    }
    
    public static function verifyToken($token) {
        // Vérifions l'état de la session et des tokens
        error_log("Token reçu: " . $token);
        error_log("Token session: " . ($_SESSION['csrf_token'] ?? 'non défini'));
        error_log("Session active: " . (session_status() === PHP_SESSION_ACTIVE ? 'oui' : 'non'));
    
        if (!isset($_SESSION['csrf_token'], $_SESSION['token_time'])) {
            error_log("Session CSRF invalide - tokens manquants");
            throw new \Exception("Session CSRF invalide");
        }
        
        if (time() - $_SESSION['token_time'] > self::TOKEN_EXPIRY) {
            error_log("Token CSRF expiré");
            throw new \Exception("Token CSRF expiré");
        }
        
        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            error_log("Token CSRF ne correspond pas");
            throw new \Exception("Token CSRF invalide");
        }
        
        return true;
    }
    
    private static function needsNewToken() {
        return empty($_SESSION['csrf_token']) || 
               !isset($_SESSION['token_time']) || 
               time() - $_SESSION['token_time'] > self::TOKEN_EXPIRY;
    }
    
    private static function generateNewToken() {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(self::TOKEN_LENGTH));
        $_SESSION['token_time'] = time();
    }
}