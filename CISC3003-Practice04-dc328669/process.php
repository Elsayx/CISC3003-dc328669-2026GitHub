<?php
// Practice 04 reference only (no need to run PHP yet).
// This file simply prints posted form values.

header('Content-Type: text/plain; charset=utf-8');

echo "CISC3003 Practice 04 - process.php\n";
echo "Method: " . ($_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN') . "\n\n";

echo "POST data:\n";
foreach ($_POST as $key => $value) {
  if (is_array($value)) {
    echo $key . " = [" . implode(", ", $value) . "]\n";
  } else {
    echo $key . " = " . $value . "\n";
  }
}

