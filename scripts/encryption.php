<?php
class Encryption
{
      public static function generateRandomString($length = 16)
      {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^*?';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++)
                  $randomString .= $characters[rand(0, $charactersLength - 1)];

            return $randomString;
      }

      public static function generateRandomStringChars($length = 16)
      {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++)
                  $randomString .= $characters[rand(0, $charactersLength - 1)];

            return $randomString;
      }

      public static function encryptData($data, $password)
      {
            return openssl_encrypt($data, "AES-128-ECB", $password);
      }

      public static function decryptData($data, $password)
      {
            return openssl_decrypt($data, "AES-128-ECB", $password);
      }
}
?>
