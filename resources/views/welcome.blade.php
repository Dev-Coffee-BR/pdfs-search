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
        input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        button {
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            background-color: #4285f4;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #357ae8;
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
    </style>
</head>

<body>
    <h1>Busca de PDFs</h1>
    <input type="text" id="searchInput" placeholder="Digite sua busca...">
    <button onclick="searchPDFs()">Buscar</button>

    <div id="results"></div>

    <script>
        async function searchPDFs() {
            const query = document.getElementById('searchInput').value;
            const response = await fetch(`/api/consult?s=${encodeURIComponent(query)}`);
            const data = await response.json();

            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = ''; // Limpa os resultados anteriores

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
        } 
    </script>
</body>
</html>
