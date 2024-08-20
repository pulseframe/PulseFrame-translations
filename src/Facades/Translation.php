<?php

namespace PulseFrame\Facades;

use Symfony\Component\Yaml\Yaml;

class Translation
{
  public static $language = "en";
  protected static $configs = [];

  public static function languageCodes()
  {
    return array_keys(self::$configs);
  }

  protected static function loadConfig($languageCode)
  {
    $filepath = ROOT_DIR . "/translations/$languageCode.yml";

    if (!file_exists($filepath)) {
      throw new \Exception("Translation \"" . self::$language . "\" not found!");
    }

    if (!isset(self::$configs[$languageCode ?? self::$language])) {
      self::$configs[$languageCode ?? self::$language] = Yaml::parseFile($filepath);
    }
  }

  public static function key($key, $languageCode = null)
  {
    self::loadConfig($languageCode ?? self::$language);

    if ($key === null) {
      return self::$configs[$languageCode ?? self::$language];
    }

    $keys = explode('.', $key);
    $value = self::$configs[$languageCode ?? self::$language];

    foreach ($keys as $k) {
      if (isset($value[$k])) {
        $value = $value[$k];
      } else {
        return '';
      }
    }

    return $value;
  }
}
