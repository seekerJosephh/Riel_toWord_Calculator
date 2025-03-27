<!DOCTYPE html>
<html>

<head>
    <title>Riel to Words Calculator</title>
</head>
<body>

<h1>Riel to Words Calculator</h1>

<form method="post" action="">
    <label for="riel_amount">Please input your data (Riel):</label>
    <input type="text" id="riel_amount" name="riel_amount">
    <input type="submit" value="Convert">
</form>

<?php
header('Content-Type: text/html; charset=UTF-8');
$english_words = $khmer_words = $usd_amount = $error_message = '';
$riel_amount = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $riel_amount_input = $_POST["riel_amount"];
         // a. Input Validation
          if (!is_numeric($riel_amount_input) || $riel_amount_input < 0) {
              echo "<p style='color:red;'>Error: Please enter a positive number for the Riel amount.</p>";
          } else {
              $riel_amount = intval($riel_amount_input);
              // Proceed with logic
          }

        // b. Store data in Text File
        // $file = 'Projects.txt';
        // if (is_writable($file) || (!file_exists($file) && is_writable(dirname($file)))) {
        //     $current = file_exists($file) ? file_get_contents($file) : '';
        //     $current .= $riel_amount . "\n";
        //     file_put_contents($file, $current);
        // } else {
        //     echo "<p style='color:red;'>Error: Cannot write to file.</p>";
        // }

        function numberToEnglishWords($number) {
          $ones = array(
              0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
              6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
              11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen',
              15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
              19 => 'Nineteen'
          );
          $tens = array(
              0 => '', 2 => 'Twenty', 3 => 'Thirty', 4 => 'Forty', 5 => 'Fifty',
              6 => 'Sixty', 7 => 'Seventy', 8 => 'Eighty', 9 => 'Ninety'
          );
          $thousands = array(
              0 => '', 1 => 'Thousand', 2 => 'Million', 3 => 'Billion', 4 => 'Trillion'
          );
      
          if ($number == 0) return 'Zero';

          $num_str = (string)abs($number);
          $chunks = array_reverse(str_split(str_pad($num_str, ceil(strlen($num_str) / 3) * 3, '0', STR_PAD_LEFT), 3));
          $words = [];
      
          foreach ($chunks as $i => $chunk) {
              $chunk_int = (int)$chunk;
              if ($chunk_int != 0) {
                  $chunk_words = '';
                  $hundreds = floor($chunk_int / 100);
                  $remainder = $chunk_int % 100;
      
                  if ($hundreds > 0) {
                      $chunk_words .= $ones[$hundreds] . ' Hundred';
                  }
                  if ($remainder > 0) {
                      if (!empty($chunk_words)) $chunk_words .= ' ';
                      if ($remainder < 20) {
                          $chunk_words .= $ones[$remainder];
                      } else {
                          $chunk_words .= $tens[floor($remainder / 10)];
                          if ($remainder % 10 > 0) {
                              $chunk_words .= ' ' . $ones[$remainder % 10];
                          }
                      }
                  }
                  $words[] = $chunk_words . ' ' . $thousands[$i];
              }
          }
          return trim(implode(' ', array_reverse($words)));
      }

        function numberToKhmerWords($number) {
          $khmer_ones = array(0 => 'សូន្យ', 1 => 'មួយ', 2 => 'ពីរ', 3 => 'បី', 4 => 'បួន', 5 => 'ប្រាំ', 6 => 'ប្រាំមួយ', 7 => 'ប្រាំពីរ', 8 => 'ប្រាំបី', 9 => 'ប្រាំបួន');
          $khmer_tens = array(1 => 'ដប់', 2 => 'ម្ភៃ', 3 => 'សាមសិប', 4 => 'សែសិប', 5 => 'ហាសិប', 6 => 'ហុកសិប', 7 => 'ចិតសិប', 8 => 'ប៉ែតសិប', 9 => 'កៅសិប');
          $khmer_thousands = array(1 => 'ពាន់', 2 => 'ម៉ឺន', 3 => 'សែន', 4 => 'លាន', 5 => 'កោដិ');
      
          if ($number == 0) return $khmer_ones[0];
      
          $words = [];
          $num_str = (string)$number;
          $chunks = array_reverse(str_split(str_pad($num_str, ceil(strlen($num_str) / 3) * 3, '0', STR_PAD_LEFT), 3));
      
          foreach ($chunks as $i => $chunk) {
              $chunk_int = (int)$chunk;
              if ($chunk_int != 0) {
                  $chunk_words = '';
                  $hundreds = floor($chunk_int / 100);
                  $remainder = $chunk_int % 100;
      
                  if ($hundreds > 0) {
                      $chunk_words .= $khmer_ones[$hundreds] . ' រយ';
                  }
                  if ($remainder > 0) {
                      if (!empty($chunk_words)) $chunk_words .= ' ';
                      if ($remainder < 10) {
                          $chunk_words .= $khmer_ones[$remainder];
                      } else {
                          $tens_digit = floor($remainder / 10);
                          $ones_digit = $remainder % 10;
                          $chunk_words .= $khmer_tens[$tens_digit];
                          if ($ones_digit > 0) $chunk_words .= ' ' . $khmer_ones[$ones_digit];
                      }
                  }
                  if ($i > 0) $chunk_words .= ' ' . $khmer_thousands[$i];
                  $words[] = $chunk_words;
              }
          }
          return trim(implode(' ', array_reverse($words)));
      }
        $english_words = numberToEnglishWords($riel_amount);
        $khmer_words = numberToKhmerWords($riel_amount);
        $usd_amount = number_format($riel_amount / 4000, 2);
        // a. Display entered Riel currency data as text (English)
        $english_words = numberToEnglishWords($riel_amount);
        echo "<h3>Entered Data: " . number_format($riel_amount) . " Riel</h3>";
        echo "<p><strong>a. English:</strong> " . $english_words . " Riel</p>";
        echo "<p><strong>b. Khmer:</strong> " . $khmer_words . " រៀល </p>";
        echo "<p><strong>c. US Dollars:</strong> $" . $usd_amount . "</p>";
    }

?>

</body>
</html>