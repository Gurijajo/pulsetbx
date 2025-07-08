<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$auth = new Auth();
$artists = getArtists();
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artists - PULSE Festival</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'festival-green': '#00ff41',
                        'festival-purple': '#ff006b',
                        'festival-blue': '#0099ff',
                        'festival-yellow': '#ffff00',
                        'festival-pink': '#ff00ff'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap');
        .festival-font { font-family: 'Orbitron', monospace; }
        .glow { text-shadow: 0 0 10px currentColor; }
    </style>
</head>
<body class="bg-black text-white festival-font min-h-screen">
    <nav class="bg-black/90 backdrop-blur-sm border-b border-gray-800 p-4">
        <div class="container mx-auto flex items-center justify-between">
            <a href="index.php" class="text-2xl font-bold festival-font glow text-festival-green">PULSE</a>
            <a href="index.php" class="text-gray-400 hover:text-white">← Back to festival</a>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-5xl font-bold text-center mb-16 glow text-festival-purple">ARTISTS</h1>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 max-w-7xl mx-auto">
            <?php foreach ($artists as $index => $artist): 
                $colors = ['festival-purple', 'festival-green', 'festival-blue', 'festival-yellow', 'festival-pink'];
                $color = $colors[$index % count($colors)];
            ?>
                <div class="group cursor-pointer transform transition-all duration-300 hover:scale-105" x-data="{ expanded: false }">
                    <div class="bg-gray-900 rounded-lg overflow-hidden border border-gray-700 hover:border-<?php echo $color; ?>">
                        <div class="h-64 bg-gradient-to-br from-<?php echo $color; ?> to-gray-800 flex items-center justify-center relative">
                            <span class="text-4xl font-bold"><?php echo substr($artist['name'], 0, 2); ?></span>
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300"></div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 text-<?php echo $color; ?>"><?php echo htmlspecialchars($artist['name']); ?></h3>
                            <p class="text-gray-400 text-sm mb-4"><?php echo htmlspecialchars($artist['bio'] ?? 'Electronic Music Artist'); ?></p>
                            
                            <button @click="expanded = !expanded" class="text-<?php echo $color; ?> text-sm hover:underline">
                                <span x-text="expanded ? 'Show Less' : 'Show More'">Show More</span>
                                <i class="fas fa-chevron-down ml-1 transform transition-transform" :class="{ 'rotate-180': expanded }"></i>
                            </button>
                            
                            <div x-show="expanded" x-transition class="mt-4">
                                <div class="space-y-2">
                                    <h4 class="font-bold text-white">Performance Details:</h4>
                                    <p class="text-gray-400 text-sm">This artist will deliver an unforgettable electronic music experience with cutting-edge visuals and sound design.</p>
                                    <div class="flex space-x-4 mt-3">
                                        <i class="fab fa-spotify text-green-500 text-xl cursor-pointer hover:scale-110 transition-transform"></i>
                                        <i class="fab fa-instagram text-pink-500 text-xl cursor-pointer hover:scale-110 transition-transform"></i>
                                        <i class="fab fa-youtube text-red-500 text-xl cursor-pointer hover:scale-110 transition-transform"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>