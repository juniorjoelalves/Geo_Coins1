# 📊 GeoCoins

Sistema web de acompanhamento de cotações de moedas em tempo real, conversor de valores e configuração de alertas de preço, desenvolvido como projeto académico.

## 🚀 Funcionalidades

* **Cotações em Tempo Real:** Exibe os valores atualizados de Dólar (USD), Euro (EUR) e Bitcoin (BTC) em relação ao Real (BRL) utilizando a AwesomeAPI.
* **Conversor de Moedas:** Permite calcular rapidamente a conversão entre Real, Dólar, Euro e Bitcoin com base na taxa atual do mercado.
* **Alerta de Preço:** Formulário para definir valores-limite de monitoramento para os pares de moedas.
* **Gráfico de Tendências:** Visualização interativa da variação do Dólar e do Euro nos últimos 7 dias com Chart.js.

## 🛠️ Tecnologias Utilizadas

* **Frontend:** HTML5, CSS3 e JavaScript (Fetch API e Chart.js)
* **Backend:** PHP
* **Banco de Dados:** MySQL (estrutura relacional e consultas analíticas)
* **API Externa:** [AwesomeAPI](https://docs.awesomeapi.com.br/api-de-moedas)

## 📁 Estrutura de Arquivos

* `Geo-Coins.php` — Arquivo principal contendo a interface, o processamento de formulários em PHP e a integração em JavaScript com a API e o gráfico.
* `style.css` — Folha de estilos responsável pelo layout em cartões e responsividade.
* `teste.sql` — Script SQL com a criação das tabelas (`cotacoes`, `alertas`, `log_conversoes`) e consultas analíticas utilizando `GROUP BY` e `JOIN`.

## ⚙️ Como Executar

1. Faça o upload dos arquivos `Geo-Coins.php` e `style.css` para a mesma pasta no servidor web (Apache/PHP) ou ambiente local (XAMPP/Laragon).
2. Caso deseje testar a estrutura de banco de dados, importe o arquivo `teste.sql` no MySQL/phpMyAdmin.
3. Acesse o arquivo `Geo-Coins.php` pelo navegador.
