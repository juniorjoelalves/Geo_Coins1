<?php
error_reporting(0);

$msgAlerta = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['par_moedas'])) {
    $par = $_POST['par_moedas'];
    $limite = (float) $_POST['valor_limite'];

    if ($limite > 0) {
        $limiteFormatado = number_format($limite, 2, ',', '.');
        $msgAlerta = "<p style='color: #166534; font-weight: bold; margin-top: 10px; padding: 10px; background: #dcfce7; border-radius: 6px;'>Alerta salvo para $par no valor de R$ $limiteFormatado</p>";
    } else {
        $msgAlerta = "<p style='color: #dc2626; margin-top: 10px;'>Digite um valor válido.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoCoins</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>📊 GeoCoins</h1>
    </header>

    <main class="container">
        <section class="cards-grid">
            <div class="card">
                <h3>Dólar (USD)</h3>
                <p class="valor" id="usd">Carregando...</p>
            </div>
            <div class="card">
                <h3>Euro (EUR)</h3>
                <p class="valor" id="eur">Carregando...</p>
            </div>
            <div class="card">
                <h3>Bitcoin (BTC)</h3>
                <p class="valor" id="btc">Carregando...</p>
            </div>
        </section>

        <div class="forms-grid">
            <section class="card-form">
                <h2>Conversor de Moedas</h2>
                <form id="form-conversor">
                    <label for="valor">Valor:</label>
                    <input type="number" step="0.01" id="valor" required placeholder="Ex: 100.00">

                    <label for="origem">De:</label>
                    <select id="origem">
                        <option value="USD">Dólar (USD)</option>
                        <option value="EUR">Euro (EUR)</option>
                        <option value="BTC">Bitcoin (BTC)</option>
                        <option value="BRL">Real (BRL)</option>
                    </select>

                    <label for="destino">Para:</label>
                    <select id="destino">
                        <option value="BRL" selected>Real (BRL)</option>
                        <option value="USD">Dólar (USD)</option>
                        <option value="EUR">Euro (EUR)</option>
                        <option value="BTC">Bitcoin (BTC)</option>
                    </select>

                    <button type="submit">Converter</button>
                </form>
                <div id="resultado"></div>
            </section>

            <section class="card-form">
                <h2>Alerta de Preço</h2>
                <form method="POST" action="Geo-Coins.php">
                    <label for="par_moedas">Moeda:</label>
                    <select name="par_moedas" id="par_moedas">
                        <option value="USD-BRL">Dólar / Real</option>
                        <option value="EUR-BRL">Euro / Real</option>
                        <option value="BTC-BRL">Bitcoin / Real</option>
                    </select>

                    <label for="valor_limite">Avisar quando chegar em (R$):</label>
                    <input type="number" step="0.01" id="valor_limite" name="valor_limite" required placeholder="Ex: 5.50">

                    <button type="submit">Criar Alerta</button>
                </form>
                <div><?= $msgAlerta ?></div>
            </section>
        </div>

        <section class="grafico-container">
            <h2>Variação nos últimos 7 dias</h2>
            <div style="height: 300px;">
                <canvas id="grafico"></canvas>
            </div>
        </section>
    </main>

    <script>
        let taxas = {
            BRL: 1.0,
            USD: 5.05,
            EUR: 5.42,
            BTC: 429974.00
        };

        function formatarReais(valor) {
            return 'R$ ' + valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function buscarCotacoes() {
            fetch('https://economia.awesomeapi.com.br/last/USD-BRL,EUR-BRL,BTC-BRL')
                .then(res => res.json())
                .then(dados => {
                    taxas.USD = parseFloat(dados.USDBRL.bid);
                    taxas.EUR = parseFloat(dados.EURBRL.bid);
                    taxas.BTC = parseFloat(dados.BTCBRL.bid);

                    document.getElementById('usd').innerText = formatarReais(taxas.USD);
                    document.getElementById('eur').innerText = formatarReais(taxas.EUR);
                    document.getElementById('btc').innerText = formatarReais(taxas.BTC);
                })
                .catch(() => {
                    document.getElementById('usd').innerText = formatarReais(taxas.USD);
                    document.getElementById('eur').innerText = formatarReais(taxas.EUR);
                    document.getElementById('btc').innerText = formatarReais(taxas.BTC);
                });
        }

        document.getElementById('form-conversor').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let valor = parseFloat(document.getElementById('valor').value);
            let de = document.getElementById('origem').value;
            let para = document.getElementById('destino').value;
            let boxResultado = document.getElementById('resultado');

            if (valor > 0) {
                let emReais = valor * taxas[de];
                let convertido = emReais / taxas[para];

                let v1 = valor.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                let v2 = convertido.toLocaleString('pt-BR', { minimumFractionDigits: 4 });

                boxResultado.innerHTML = `<p style='color: #166534; font-weight: bold; margin-top: 10px; padding: 10px; background: #dcfce7; border-radius: 6px;'>${v1} ${de} = ${v2} ${para}</p>`;
            }
        });

        function montarGrafico() {
            let datas = ['6d', '5d', '4d', '3d', '2d', 'Ontem', 'Hoje'];
            let histUsd = [5.01, 5.03, 4.98, 5.02, 5.05, 5.00, 5.05];
            let histEur = [5.55, 5.58, 5.54, 5.59, 5.62, 5.61, 5.42];

            Promise.all([
                fetch('https://economia.awesomeapi.com.br/json/daily/USD-BRL/7').then(r => r.json()),
                fetch('https://economia.awesomeapi.com.br/json/daily/EUR-BRL/7').then(r => r.json())
            ])
            .then(([dadosUsd, dadosEur]) => {
                datas = dadosUsd.map(d => {
                    let dia = new Date(d.timestamp * 1000);
                    return dia.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' });
                }).reverse();

                histUsd = dadosUsd.map(d => parseFloat(d.bid)).reverse();
                histEur = dadosEur.map(d => parseFloat(d.bid)).reverse();
                desenhar(datas, histUsd, histEur);
            })
            .catch(() => {
                desenhar(datas, histUsd, histEur);
            });
        }

        function desenhar(labels, dadosUsd, dadosEur) {
            let ctx = document.getElementById('grafico').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Dólar (USD)',
                            data: dadosUsd,
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Euro (EUR)',
                            data: dadosEur,
                            borderColor: '#0f172a',
                            backgroundColor: 'rgba(15, 23, 42, 0.05)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        buscarCotacoes();
        montarGrafico();
    </script>
</body>
</html>