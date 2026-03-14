<?php

namespace KirbyPushNotifications\Helpers;

/**
 * TranslationHelper class for managing translations
 * 
 * This class provides methods to load translations from a directory.
 * 
 * @author Philipp Oehrlein
 * @version 1.0.0
 */
class TranslationHelper
{
    /**
     * Loads translations from a directory.
     * 
     * @param string $translationsDir The directory containing the translation files.
     * @return array The translations.
     */
    public static function loadTranslations(string $translationsDir): array
    {
        $translationFiles = glob($translationsDir . '/*.php');
        $translations = [];

        foreach ($translationFiles as $file) {
            $languageCode = basename($file, '.php'); 
            $translations[$languageCode] = require $file;
        }

        return $translations;
    }
}