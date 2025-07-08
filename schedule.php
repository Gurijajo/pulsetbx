<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$auth = new Auth();
$events = getEvents();
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule - PULSE Festival</title>
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
        .vertical-text {
            writing-mode: vertical-rl;
            text-orientation: mixed;
        }
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
        <h1 class="text-5xl font-bold text-center mb-16 glow text-festival-blue">EVENT SCHEDULE</h1>
        
        <div class="max-w-6xl mx-auto">
            <!-- Date Header -->
            <div class="text-center mb-12">
                <h2 class="text-6xl font-bold text-white mb-4">28 JULY</h2>
                <p class="text-festival-blue text-xl">Main Stage - Electronic Music Festival</p>
            </div>
            
            <!-- Schedule Grid -->
            <div class="bg-black rounded-lg p-8 border border-gray-700 relative">
                <!-- Vertical Label -->
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 -rotate-90">
                    <span class="text-festival-blue text-xl font-bold tracking-widest">EVENT SCHEDULE</span>
                </div>
                
                <!-- Events List -->
                <div class="ml-16 space-y-8">
                    <?php 
                    $colors = ['festival-purple', 'festival-green', 'festival-blue', 'festival-yellow', 'festival-pink', 'festival-green'];
                    $colorIndex = 0;
                    foreach ($events as $event): 
                        $color = $colors[$colorIndex % count($colors)];
                        $colorIndex++;
                    ?>
                        <div class="flex items-center space-x-8 group hover:bg-gray-900/50 p-4 rounded-lg transition-all duration-300">
                            <!-- Time -->
                            <div class="text-3xl font-bold min-w-[120px] text-white">
                                <?php echo date('H:i', strtotime($event['start_time'])); ?>
                            </div>
                            
                            <!-- Number Badge -->
                            <div class="w-16 h-16 rounded-full bg-<?php echo $color; ?> flex items-center justify-center text-black font-bold text-xl">
                                <?php echo sprintf('%02d', $colorIndex); ?>
                            </div>
                            
                            <!-- Artist Info -->
                            <div class="flex-1">
                                <h3 class="text-3xl font-bold text-white mb-2"><?php echo htmlspecialchars($event['artist_name']); ?></h3>
                                <div class="flex items-center space-x-4 text-gray-400">
                                    <span><i class="fas fa-map-marker-alt mr-2"></i><?php echo htmlspecialchars($event['stage'] ?? 'Main Stage'); ?></span>
                                    <span><i class="fas fa-clock mr-2"></i><?php echo date('H:i', strtotime($event['start_time'])); ?> - <?php echo date('H:i', strtotime($event['end_time'] ?? $event['start_time'] . ' +1 hour')); ?></span>
                                </div>
                            </div>
                            
                            <!-- Action Button -->
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="bg-<?php echo $color; ?> text-black px-4 py-2 rounded font-bold hover:scale-105 transition-transform">
                                    ADD TO CALENDAR
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Additional Info -->
            <div class="mt-12 grid md:grid-cols-3 gap-6">
                <div class="bg-gray-900 rounded-lg p-6 border border-gray-700">
                    <h3 class="text-xl font-bold mb-4 text-festival-green">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        VENUE INFO
                    </h3>
                    <p class="text-gray-300 mb-2">Festival Grounds</p>
                    <p class="text-gray-400 text-sm">123 Music Avenue, Festival City</p>
                </div>
                
                <div class="bg-gray-900 rounded-lg p-6 border border-gray-700">
                    <h3 class="text-xl font-bold mb-4 text-festival-purple">
                        <i class="fas fa-info-circle mr-2"></i>
                        IMPORTANT INFO
                    </h3>
                    <ul class="text-gray-400 text-sm space-y-1">
                        <li>• Doors open at 23:30</li>
                        <li>• Must be 18+ to enter</li>
                        <li>• Bring valid ID</li>
                    </ul>
                </div>
                
                <div class="bg-gray-900 rounded-lg p-6 border border-gray-700">
                    <h3 class="text-xl font-bold mb-4 text-festival-yellow">
                        <i class="fas fa-mobile-alt mr-2"></i>
                        DOWNLOAD APP
                    </h3>
                    <p class="text-gray-400 text-sm mb-3">Get real-time updates and festival map</p>
                    <div class="flex space-x-2">
                        <button class="bg-gray-800 px-3 py-1 rounded text-xs">iOS</button>
                        <button class="bg-gray-800 px-3 py-1 rounded text-xs">Android</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>