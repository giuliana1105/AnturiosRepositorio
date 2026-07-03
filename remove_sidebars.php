<?php
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('resources/views'));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        
        if (strpos($path, 'app.blade.php') !== false || strpos($path, 'change-password.blade.php') !== false) {
            continue; // Skip layout and already fixed files
        }

        $content = file_get_contents($path);
        $original = $content;

        // Pattern to remove the sidebar navigation entirely
        $pattern = '/<!-- Sidebar Navigation -->.*?<!-- Main Content -->/s';
        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, '<!-- Main Content -->', $content);
        }

        // Change the wrapper to full width
        $content = str_replace('<div class="col-md-10 py-3 px-4 bg-white">', '<div class="col-12 py-3 px-4">', $content);
        $content = str_replace('<div class="col-md-10 py-4 px-4 bg-white">', '<div class="col-12 py-4 px-4">', $content);
        
        $content = str_replace('<div class="container-fluid p-0 m-0">', '<div class="container py-4">', $content);
        $content = str_replace('<div class="row g-0 min-vh-100">', '<div class="row">', $content);

        // Also fix the bg-info issue where it didn't have a leading space
        $content = str_replace('"bg-info ', '"bg-brand ', $content);
        $content = str_replace("'bg-info ", "'bg-brand ", $content);
        $content = str_replace('bg-info"', 'bg-brand"', $content);
        $content = str_replace("bg-info'", "bg-brand'", $content);
        
        // Fix text-info in quotes
        $content = str_replace('"text-info ', '"text-brand ', $content);
        $content = str_replace("'text-info ", "'text-brand ", $content);
        $content = str_replace('text-info"', 'text-brand"', $content);
        $content = str_replace("text-info'", "text-brand'", $content);

        // Clean up CSS at the bottom of the files that might affect col-md-2
        $css_pattern = '/\.col-md-2,\s*\.col-md-10\s*\{.*?\}\s*\.col-md-2\.bg-light\s*\{.*?\}/s';
        $content = preg_replace($css_pattern, '', $content);

        if ($content !== $original) {
            file_put_contents($path, $content);
            echo "Cleaned sidebar from: $path\n";
        }
    }
}
echo "Done\n";
