<?php
// === Step 1: Convert input.dat to output.bedGraph ===
$inputDatFile = 'input.dat';
$bedGraphFile = 'output.bedGraph';

$lines = file($inputDatFile);
$out = fopen($bedGraphFile, 'w');

foreach ($lines as $line) {
    $parts = preg_split('/\s+/', trim($line));
    if (count($parts) === 4) {
        fwrite($out, implode("\t", $parts) . "\n");
    }
}
fclose($out);
echo "✅ Converted to bedGraph: $bedGraphFile\n";

// === Step 2: Convert output.bedGraph to output.bw ===
$chromSizesFile = 'chrom.sizes';  // ⚠️ Replace this with actual path
$outputBWFile = 'output.bw';

$cmd = "/usr/local/bin/bedGraphToBigWig $bedGraphFile $chromSizesFile $outputBWFile";

// Execute conversion
$output = shell_exec($cmd);

// Show debug message
echo "✅ Converted to BigWig: $outputBWFile\n";
echo "Command output: $output\n";
?>
