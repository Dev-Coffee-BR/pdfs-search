<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-LDGB2F1W3B"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-LDGB2F1W3B');
    </script>
    <title>Busca de PDFs</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f7;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
            animation: fadeInDown 0.6s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            width: 80px; /* Ajuste o tamanho da logo aqui */
            height: 80px; /* Ajuste o tamanho da logo aqui */
            border-radius: 50%;
            margin-bottom: 10px;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .search-container {
            display: flex;
            width: 70%;
            max-width: 800px;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            background-color: white;
        }

        input[type="text"] {
            width: 100%;
            padding: 15px;
            font-size: 18px;
            border: none;
            outline: none;
            border-radius: 10px 0 0 10px;
        }

        button {
            padding: 15px 20px;
            font-size: 18px;
            border: none;
            background-color: #3498db;
            color: white;
            border-radius: 0 10px 10px 0;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        button:disabled {
            background-color: #bdc3c7;
            cursor: not-allowed;
        }

        .results-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 800px;
        }

        .result {
            width: 100%;
            background: white;
            padding: 20px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .result h3 {
            margin: 0;
            color: #34495e;
        }

        .result p {
            margin: 5px 0;
            color: #7f8c8d;
        }

        #loading {
            display: none;
            font-size: 18px;
            margin-bottom: 20px;
            color: #3498db;
            font-weight: bold;
            animation: fadeIn 0.6s ease;
        }

        #results-count {
            margin-bottom: 10px;
            font-size: 16px;
            color: #2c3e50;
        }
    </style>
</head>

<body>
    <img class="logo" src="https://ugc.production.linktr.ee/6f0ef1f8-3a73-4b37-bee8-5978e170580a_DV.png?io=true&size=avatar-v3_0" alt="Logo Dev&Coffee">
    <h1>Dev&Coffee - PDF SEARCH</h1>

    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Digite sua busca..." oninput="toggleButton()">
        <button id="searchButton" onclick="searchPDFs()" disabled>Buscar</button>
    </div>

    <div id="loading">Carregando resultados...</div>
    <div class="results-container">
        <div id="results-count"></div>
        <div id="results"></div>
    </div>

    <script>
        function toggleButton() {
            const query = document.getElementById('searchInput').value;
            const searchButton = document.getElementById('searchButton');
            searchButton.disabled = query.trim() === '';
        }

        async function searchPDFs() {
            const query = document.getElementById('searchInput').value;
            const resultsDiv = document.getElementById('results');
            const loading = document.getElementById('loading');
            const resultsCount = document.getElementById('results-count');

            loading.style.display = 'block';
            resultsDiv.innerHTML = '';
            resultsCount.innerHTML = '';

            try {
                const response = await fetch(`/api/consult?s=${encodeURIComponent(query)}`);
                const data = await response.json();

                loading.style.display = 'none';

                if (data.pdfs.length === 0) {
                    resultsDiv.innerHTML = '<p>Nenhum resultado encontrado.</p>';
                    return;
                }

                resultsCount.innerHTML = `Total de resultados: ${data.total}`;

                data.pdfs.forEach(pdf => {
                    const pdfDiv = document.createElement('div');
                    pdfDiv.className = 'result';
                    pdfDiv.innerHTML = `
                        <h3><a href="${pdf.link}" target="_blank">${pdf.title}</a></h3>
                        <p>${pdf.description}</p>
                        <p><strong>Tamanho do Arquivo:</strong> ${pdf.size ? pdf.size : 'N/A'}</p>
                    `;
                    resultsDiv.appendChild(pdfDiv);
                });

            } catch (error) {
                loading.style.display = 'none';
                resultsDiv.innerHTML = '<p>Erro ao buscar PDFs. Tente novamente mais tarde.</p>';
            }
        }
    </script>
</body>

</html>
