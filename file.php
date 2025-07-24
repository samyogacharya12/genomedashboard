<?php
// === Step 1: Convert input.dat to output.bedGraph ===
$inputDatFile = 'wri.dat';
$bedGraphFile = 'output.bedGraph';

$lines = file($inputDatFile);
$out = fopen($bedGraphFile, 'w');

$chrom = "chr1";

foreach ($lines as $i => $line) {
    $parts = preg_split('/\s+/', trim($line));
    
    // If line has two columns, use value from column 2
    // Otherwise, treat whole line as a value (e.g., single-column input)
    if (count($parts) >= 2 && is_numeric($parts[1])) {
        $value = $parts[1];
    } elseif (count($parts) === 1 && is_numeric($parts[0])) {
        $value = $parts[0];
    } else {
        continue; // skip invalid lines
    }

    $start = $i;       // dynamic index based on line number
    $end = $i + 1;

    fwrite($out, "$chrom\t$start\t$end\t$value\n");
}


// === Step 2: Convert output.bedGraph to output.bw ===
$chromSizesFile = 'chrom.sizes';  // ⚠️ Replace with actual path
$outputBWFile = 'output.bw';

$cmd = "/usr/local/bin/bedGraphToBigWig $bedGraphFile $chromSizesFile $outputBWFile";
$output = shell_exec($cmd);

echo "✅ Converted to BigWig: $outputBWFile\n";
echo "Command output: $output\n";

// === Step 3: Convert input.txt to output.fasta ===
$inputTxtFile = 'seqin.txt';
$fastaOutputFile = 'output.fasta';

$lines = file($inputTxtFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$sequence = implode('', $lines);  // Join all lines into one sequence
$id = 'seq1';

$outFasta = fopen($fastaOutputFile, 'w');
fwrite($outFasta, ">$id\n$sequence\n");
fclose($outFasta);

echo "✅ Converted to FASTA: $fastaOutputFile\n";


// === Step 1: Define input/output ===
$fasta = "output.fasta";
$twobit = "output.2bit";
$binary = "faToTwoBit"; // full path is important

echo "✅ Converted to FASTA: $fasta\n";



if (!file_exists($fasta)) {
    die("❌ FASTA file not found: $fasta");
}

// Add 2>&1 to capture stderr too
$cmd = "$binary $fasta $twobit 2>&1";
$output = shell_exec($cmd);

echo "✅ faToTwoBit executed.<br>";
echo "<pre>$output</pre>";
?>



