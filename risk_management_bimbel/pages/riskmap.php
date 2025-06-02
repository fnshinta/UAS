<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .grid {
            gap: 2px;
            width: 50%;
            margin: 0;
            padding: 0;
        }

        .grid-cols-5 {
            grid-template-columns: repeat(5, 1fr);
        }

        .cell {
            border: 1px solid #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
            position: relative;
            height: 100px;
        }

        /* WARNA UNGU & SENADANYA */

        /* Dari ungu tua ke ungu muda */
        .low {
            background-color: rgb(76, 29, 149); /* ungu tua */
        }

        .minor { 
            background-color: rgb(116, 54, 180); /* ungu medium */
        } 

        .medium {
            background-color: rgb(179, 136, 224); /* ungu muda */
        }

        .high {
            background-color: rgb(102, 51, 153); /* ungu sedang agak gelap */
        }

        .bubble-container {
            display: flex;
            flex-wrap: wrap; /* Agar bubble tidak tumpang tindih */
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            position: relative;
        }

        .risk-bubble {
            border-radius: 50%;
            font-size: 12px;
            padding: 5px 10px;
            margin: 5px;
            text-align: center;
            background: white;
            border: 2px solid black;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 30px;
            width: 30px;
        }

        /* Warna bubble inherent, residual, after diganti ke ungu sesuai tema */
        .inherent {
            background-color: #6b46c1; /* ungu gelap */
            color: white;
        }

        .residual {
            background-color: #b794f4; /* ungu muda */
            color: black;
        }

        .after {
            background-color: #805ad5; /* ungu medium */
            color: white;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
        
    <main class="container mx-auto px-4 py-6 flex-grow ">
        <section class="bg-white p-6 rounded shadow mb-6">
            <h2 class="text-xl font-bold mb-4 text-purple-700">Risk Map Visualization</h2>
            <div class="w-full">
                <!-- Container Grid -->
                <div class="flex items-center justify-center w-full">
                    <!-- Label Impact (Sisi Kiri) -->
                    <div class="hidden md:flex flex-col justify-between text-sm font-semibold pr-4 text-purple-700">
                        <div class="h-24 flex items-center justify-end">Very High</div>
                        <div class="h-24 flex items-center justify-end">High</div>
                        <div class="h-24 flex items-center justify-end">Medium</div>
                        <div class="h-24 flex items-center justify-end">Low</div>
                        <div class="h-24 flex items-center justify-end">Very Low</div>
                    </div>

                    <!-- Grid Risiko -->
                    <div class="grid grid-cols-5 gap-1 flex-1 w-full rounded-lg max-w-xs md:max-w-full mx-auto">
                        <?php
                        for ($impact = 5; $impact >= 1; $impact--) {
                            for ($likelihood = 1; $likelihood <= 5; $likelihood++) {
                                // Filter risiko dengan Likelihood dan Impact saat ini
                                $cellRisks = array_filter($risks, function ($risk) use ($impact, $likelihood) {
                                    return $risk['impact'] == $impact && $risk['likelihood'] == $likelihood;
                                });

                                // Modifikasi logika pewarnaan jadi ungu senada
                                if (($impact == 5 && $likelihood == 1) || ($impact == 1 && $likelihood == 5)) {
                                    $class = 'medium'; // Ungu muda untuk kotak kiri atas dan kanan bawah
                                } else {
                                    $riskLevel = $impact * $likelihood; // Hitung risk level
                                    if ($riskLevel >= 16) {
                                        $class = 'high'; // Ungu agak gelap
                                    } elseif ($riskLevel >= 10) {
                                        $class = 'medium'; // Ungu muda
                                    } elseif ($riskLevel >= 5) {
                                        $class = 'minor'; // Ungu medium
                                    } else {
                                        $class = 'low'; // Ungu tua
                                    }
                                }

                                // Mulai Sel Grid
                                echo "<div class='flex justify-center items-center border border-gray-300 $class text-white h-24 relative'>";

                                // Tambahkan Bubble Risiko
                                echo "<div class='flex flex-wrap justify-center items-center'>";
                                foreach ($cellRisks as $risk) {
                                    $bubbleClass = strtolower($risk['type']); // inherent, residual, or after mitigation
                                    echo "<div class='risk-bubble $bubbleClass'>{$risk['risk_code']}</div>";
                                }
                                echo "</div>";

                                echo "</div>"; // Tutup Sel Grid
                            }
                        }
                        ?>
                        <!-- Label Likelihood (Bawah) -->
                        <div class="text-center text-sm font-semibold text-purple-700">Very Low</div>
                        <div class="text-center text-sm font-semibold text-purple-700">Low</div>
                        <div class="text-center text-sm font-semibold text-purple-700">Medium</div>
                        <div class="text-center text-sm font-semibold text-purple-700">High</div>
                        <div class="text-center text-sm font-semibold text-purple-700">Very High</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-white p-6 rounded shadow mb-6">
            <div class="bg-white mt-6 p-4 border rounded shadow">
                <h3 class="text-lg font-bold mb-2 text-purple-700">Information</h3>
                <ul class="list-disc ml-4 text-purple-800">
                    <li><span class="font-bold">A</span>: Inherent Risk (Before Mitigation)</li>
                    <li><span class="font-bold">B</span>: Residual Risk (After Initial Mitigation)</li>
                    <li><span class="font-bold">C</span>: After Mitigation Risk</li>
                </ul>
                <h4 class="font-semibold mt-4 text-purple-700">Color Code:</h4>
                <div class="flex flex-wrap gap-2 mt-2">
                    <span class="inline-block w-5 h-5 bg-purple-900"></span> <span>Low</span>
                    <span class="inline-block w-5 h-5 bg-purple-700"></span> <span>Minor</span>
                    <span class="inline-block w-5 h-5 bg-purple-400"></span> <span>Medium</span>
                    <span class="inline-block w-5 h-5 bg-purple-600"></span> <span>High</span>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-purple-900 text-white text-center">
        <div class="bg-purple-700 py-2"></div> <!-- Warna ungu muda di atas -->
        <div class="py-4"> <!-- Padding top dan bottom pada bagian utama -->
            © 2025 Risk Management Dashboard. All rights reserved.
        </div>
    </footer>

</body>
</html>
