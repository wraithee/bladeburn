<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BladeBurn - просмотр сообщения</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-200 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-slate-800 p-8 rounded-2xl shadow-2xl border border-slate-700">
        <h1 class="text-3xl font-bold mb-6 text-center text-indigo-400">BladeBurn</h1>
        
        <div id="loading" class="text-center py-10">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-500 mx-auto mb-4"></div>
            <p class="text-slate-400">Расшифровываем секрет...</p>
        </div>

        <div id="content" class="hidden">
            <div class="bg-slate-900 p-6 rounded-lg border border-slate-700 mb-6 italic text-indigo-200 break-words">
                <p id="secret-message"></p>
            </div>
            <div class="bg-amber-900/20 border border-amber-900/50 p-4 rounded-lg mb-6">
                <p class="text-amber-400 text-xs text-center font-bold uppercase tracking-widest">Внимание: сообщение удалено навсегда</p>
            </div>
            <a href="/" class="block text-center w-full bg-slate-700 hover:bg-slate-600 text-white font-bold py-3 rounded-lg transition duration-200">
                Создать свой секрет
            </a>
        </div>

        <div id="error" class="hidden text-center">
            <p class="text-red-400 mb-6 font-medium" id="error-text"></p>
            <a href="/" class="text-indigo-400 hover:underline">Вернуться на главную</a>
        </div>
    </div>

    <script>
        const hash = window.location.pathname.split('/').pop();

        async function fetchSecret() {
            try {
                const response = await fetch(`/api/secrets/${hash}`);
                const data = await response.json();

                document.getElementById('loading').classList.add('hidden');

                if (response.ok) {
                    document.getElementById('content').classList.remove('hidden');
                    document.getElementById('secret-message').innerText = data.message;
                } else {
                    document.getElementById('error').classList.remove('hidden');
                    document.getElementById('error-text').innerText = 'Секрет не найден';
                }
            } catch (e) {
                document.getElementById('loading').classList.add('hidden');
                document.getElementById('error').classList.remove('hidden');
                document.getElementById('error-text').innerText = 'Ошибка при получении данных';
            }
        }

        fetchSecret();
    </script>
</body>
</html>