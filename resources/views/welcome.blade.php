<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca de PDFs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .search-container {
            display: flex;
            width: 80%;
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px 0 0 4px;
        }
        button {
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            background-color: #4285f4;
            color: white;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
        button:hover {
            background-color: #357ae8;
        }
        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .result {
            width: 80%;
            background: white;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .result h3 {
            margin: 0;
        }
        .result p {
            margin: 5px 0;
            color: #555;
        }
        #loading {
            display: none;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h1>Busca de PDFs</h1>

    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Digite sua busca..." oninput="toggleButton()">
        <button id="searchButton" onclick="searchPDFs()" disabled>Buscar</button>
    </div>

    <div id="loading">Carregando resultados...</div>
    <div id="results"></div>

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
            
            loading.style.display = 'block';
            resultsDiv.innerHTML = ''; 
            
            try {
                const response = await fetch(`/api/consult?s=${encodeURIComponent(query)}`);
                const data = await response.json();

                loading.style.display = 'none';

                if (data.pdfs.length === 0) {
                    resultsDiv.innerHTML = '<p>Nenhum resultado encontrado.</p>';
                    return;
                }

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
