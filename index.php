<?php
// Proteção contra download via cmd
if (php_sapi_name() == 'cli') {
    die("Acesso negado");
}

// Configurações de segurança
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0f0f0f">
    <title>BOBBY | Verification System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
            color: #fff;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 50% 50%, rgba(0, 255, 132, 0.03) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            display: inline-block;
            font-size: 48px;
            font-weight: 800;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -1px;
            margin-bottom: 10px;
        }

        .logo span {
            background: linear-gradient(135deg, #f1c40f, #e67e22);
            -webkit-background-clip: text;
            background-clip: text;
        }

        .subtitle {
            color: #666;
            font-size: 14px;
            letter-spacing: 2px;
        }

        .card-input {
            background: rgba(15, 15, 25, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            border: 1px solid rgba(46, 204, 113, 0.2);
            padding: 30px;
            margin-bottom: 30px;
            transition: all 0.3s;
        }

        .card-input:hover {
            border-color: rgba(46, 204, 113, 0.5);
            box-shadow: 0 0 30px rgba(46, 204, 113, 0.1);
        }

        .section-title {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2ecc71;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        textarea {
            width: 100%;
            height: 280px;
            background: #0a0a0f;
            border: 1px solid rgba(46, 204, 113, 0.3);
            border-radius: 16px;
            padding: 20px;
            color: #2ecc71;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 13px;
            resize: vertical;
            outline: none;
            transition: all 0.3s;
        }

        textarea:focus {
            border-color: #2ecc71;
            box-shadow: 0 0 20px rgba(46, 204, 113, 0.15);
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 40px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: inherit;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: #fff;
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: #fff;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: #fff;
        }

        .btn-secondary {
            background: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
            border: 1px solid rgba(46, 204, 113, 0.3);
        }

        .btn-secondary:hover:not(:disabled) {
            background: rgba(46, 204, 113, 0.2);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(15, 15, 25, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            border: 1px solid rgba(46, 204, 113, 0.2);
            transition: all 0.3s;
        }

        .stat-card:hover {
            border-color: rgba(46, 204, 113, 0.5);
            transform: translateY(-2px);
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            background: linear-gradient(135deg, #2ecc71, #f1c40f);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .stat-label {
            font-size: 12px;
            color: #888;
            margin-top: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .results-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .result-panel {
            background: rgba(15, 15, 25, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            border: 1px solid rgba(46, 204, 113, 0.2);
            overflow: hidden;
        }

        .panel-header {
            padding: 18px 22px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(46, 204, 113, 0.2);
        }

        .panel-header.live {
            background: linear-gradient(90deg, rgba(46, 204, 113, 0.1), transparent);
            color: #2ecc71;
        }

        .panel-header.die {
            background: linear-gradient(90deg, rgba(231, 76, 60, 0.1), transparent);
            color: #e74c3c;
        }

        .badge {
            background: rgba(0,0,0,0.5);
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 12px;
        }

        .panel-content {
            max-height: 450px;
            overflow-y: auto;
            padding: 15px;
        }

        .result-line {
            padding: 12px 15px;
            margin-bottom: 8px;
            background: rgba(0,0,0,0.3);
            border-radius: 12px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            word-break: break-all;
            border-left: 3px solid;
            transition: all 0.2s;
        }

        .result-line.live {
            border-left-color: #2ecc71;
        }

        .result-line.die {
            border-left-color: #e74c3c;
        }

        .result-line:hover {
            transform: translateX(5px);
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #555;
            font-size: 13px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            padding: 20px;
            font-size: 11px;
            color: #555;
            border-top: 1px solid rgba(46, 204, 113, 0.1);
        }

        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(8px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .loading-overlay.active {
            display: flex;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(46, 204, 113, 0.2);
            border-top-color: #2ecc71;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-text {
            margin-top: 15px;
            color: #2ecc71;
            font-size: 13px;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        ::-webkit-scrollbar-thumb {
            background: #2ecc71;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .results-grid {
                grid-template-columns: 1fr;
            }
            .logo {
                font-size: 32px;
            }
        }

        .info-bar {
            background: rgba(46, 204, 113, 0.1);
            padding: 10px 15px;
            border-radius: 12px;
            margin-top: 20px;
            font-size: 12px;
            color: #2ecc71;
        }
    </style>
</head>
<body>

<div class="loading-overlay" id="loading">
    <div class="spinner"></div>
    <div class="loading-text">PROCESSANDO...</div>
</div>

<div class="container">
    <div class="header">
        <h1 class="logo">BOBBY<span>.</span></h1>
        <p class="subtitle">VERIFICATION SYSTEM</p>
    </div>

    <div class="card-input">
        <div class="section-title">
            <span>📋 INPUT DATA</span>
        </div>
        <textarea id="cardsInput" placeholder="FORMAT: number|month|year|cvv&#10;&#10;Example:&#10;5218071187326333|10|2032|000&#10;4220610675709700|12|2032|830"></textarea>
        
        <div class="button-group">
            <button class="btn btn-primary" id="startBtn">▶ START</button>
            <button class="btn btn-danger" id="stopBtn" disabled>⏹ STOP</button>
            <button class="btn btn-warning" id="clearBtn">🗑 CLEAR</button>
            <button class="btn btn-secondary" id="copyLiveBtn">📋 COPY LIVE</button>
            <button class="btn btn-secondary" id="copyDieBtn">📋 COPY DIE</button>
        </div>
        
        <div class="info-bar">
            <span>📊 TOTAL: <strong id="totalCount">0</strong> | ✅ PROCESSED: <strong id="processedCount">0</strong></span>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value" id="liveCount">0</div>
            <div class="stat-label">LIVE</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="dieCount">0</div>
            <div class="stat-label">DIE</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="totalProcessed">0</div>
            <div class="stat-label">TOTAL</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="successRate">0%</div>
            <div class="stat-label">SUCCESS RATE</div>
        </div>
    </div>

    <div class="results-grid">
        <div class="result-panel">
            <div class="panel-header live">
                <span>✅ LIVE / APPROVED</span>
                <span class="badge" id="liveBadge">0</span>
            </div>
            <div class="panel-content" id="liveResults">
                <div class="empty-state">Waiting for verification...</div>
            </div>
        </div>
        <div class="result-panel">
            <div class="panel-header die">
                <span>❌ DIE / DECLINED</span>
                <span class="badge" id="dieBadge">0</span>
            </div>
            <div class="panel-content" id="dieResults">
                <div class="empty-state">Waiting for verification...</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>BOBBY SYSTEM v3.0 | SECURE VERIFICATION</p>
    </div>
</div>

<script>
let isRunning = false;
let currentCards = [];
let liveResults = [];
let dieResults = [];
let currentIndex = 0;

const cardsInput = document.getElementById('cardsInput');
const startBtn = document.getElementById('startBtn');
const stopBtn = document.getElementById('stopBtn');
const clearBtn = document.getElementById('clearBtn');
const copyLiveBtn = document.getElementById('copyLiveBtn');
const copyDieBtn = document.getElementById('copyDieBtn');
const liveResultsDiv = document.getElementById('liveResults');
const dieResultsDiv = document.getElementById('dieResults');
const liveCountSpan = document.getElementById('liveCount');
const dieCountSpan = document.getElementById('dieCount');
const totalProcessedSpan = document.getElementById('totalProcessed');
const successRateSpan = document.getElementById('successRate');
const totalCountSpan = document.getElementById('totalCount');
const processedCountSpan = document.getElementById('processedCount');
const liveBadge = document.getElementById('liveBadge');
const dieBadge = document.getElementById('dieBadge');
const loadingDiv = document.getElementById('loading');

function updateStats() {
    liveCountSpan.textContent = liveResults.length;
    dieCountSpan.textContent = dieResults.length;
    const total = liveResults.length + dieResults.length;
    totalProcessedSpan.textContent = total;
    processedCountSpan.textContent = currentIndex;
    const rate = total > 0 ? ((liveResults.length / total) * 100).toFixed(1) : 0;
    successRateSpan.textContent = rate + '%';
    liveBadge.textContent = liveResults.length;
    dieBadge.textContent = dieResults.length;
}

function updateResultsDisplay() {
    if (liveResults.length === 0) {
        liveResultsDiv.innerHTML = '<div class="empty-state">No LIVE results</div>';
    } else {
        liveResultsDiv.innerHTML = liveResults.map(item => `<div class="result-line live">${escapeHtml(item)}</div>`).join('');
    }
    
    if (dieResults.length === 0) {
        dieResultsDiv.innerHTML = '<div class="empty-state">No DIE results</div>';
    } else {
        dieResultsDiv.innerHTML = dieResults.map(item => `<div class="result-line die">${escapeHtml(item)}</div>`).join('');
    }
    updateStats();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

async function processCard(card) {
    try {
        const response = await fetch('api.php?lista=' + encodeURIComponent(card));
        const result = await response.text();
        
        let displayMessage = result.trim();
        
        if (result.includes('✅ APROVADA')) {
            liveResults.push(displayMessage);
        } else {
            dieResults.push(displayMessage);
        }
        updateResultsDisplay();
        
        return result;
    } catch (error) {
        dieResults.push(card + ' ~> ERROR ~> API connection failed');
        updateResultsDisplay();
        return 'ERROR';
    }
}

async function processQueue() {
    if (!isRunning) return;
    if (currentIndex >= currentCards.length) {
        finishProcessing();
        return;
    }
    
    const card = currentCards[currentIndex];
    currentIndex++;
    processedCountSpan.textContent = currentIndex;
    
    await processCard(card);
    
    if (isRunning && currentIndex < currentCards.length) {
        setTimeout(processQueue, 1500);
    } else if (currentIndex >= currentCards.length) {
        finishProcessing();
    }
}

function startProcessing() {
    const rawCards = cardsInput.value.trim();
    if (!rawCards) {
        alert('Insert cards to verify');
        return;
    }
    
    currentCards = rawCards.split('\n').filter(line => line.trim() && line.includes('|'));
    
    if (currentCards.length === 0) {
        alert('Invalid format. Use: number|month|year|cvv');
        return;
    }
    
    if (currentCards.length > 100) {
        alert('Maximum 100 cards per batch');
        return;
    }
    
    totalCountSpan.textContent = currentCards.length;
    
    isRunning = true;
    currentIndex = 0;
    liveResults = [];
    dieResults = [];
    updateResultsDisplay();
    
    startBtn.disabled = true;
    stopBtn.disabled = false;
    clearBtn.disabled = true;
    
    loadingDiv.classList.add('active');
    processQueue();
}

function finishProcessing() {
    isRunning = false;
    startBtn.disabled = false;
    stopBtn.disabled = true;
    clearBtn.disabled = false;
    loadingDiv.classList.remove('active');
}

function stopProcessing() {
    isRunning = false;
    startBtn.disabled = false;
    stopBtn.disabled = true;
    clearBtn.disabled = false;
    loadingDiv.classList.remove('active');
}

function clearAll() {
    if (isRunning) {
        alert('Wait for processing to finish');
        return;
    }
    cardsInput.value = '';
    liveResults = [];
    dieResults = [];
    currentCards = [];
    currentIndex = 0;
    totalCountSpan.textContent = '0';
    processedCountSpan.textContent = '0';
    updateResultsDisplay();
}

function copyLive() {
    if (liveResults.length === 0) {
        alert('No LIVE results to copy');
        return;
    }
    navigator.clipboard.writeText(liveResults.join('\n'));
    alert(`✅ ${liveResults.length} LIVE results copied`);
}

function copyDie() {
    if (dieResults.length === 0) {
        alert('No DIE results to copy');
        return;
    }
    navigator.clipboard.writeText(dieResults.join('\n'));
    alert(`❌ ${dieResults.length} DIE results copied`);
}

startBtn.addEventListener('click', startProcessing);
stopBtn.addEventListener('click', stopProcessing);
clearBtn.addEventListener('click', clearAll);
copyLiveBtn.addEventListener('click', copyLive);
copyDieBtn.addEventListener('click', copyDie);
</script>
</body>
</html>
