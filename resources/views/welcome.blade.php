<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BladeBurn - самоудаляющиеся сообщения</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-200 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-slate-800 p-8 rounded-2xl shadow-2xl border border-slate-700">
        <h1 class="text-3xl font-bold mb-6 text-center text-indigo-400">BladeBurn</h1>
        
        <div id="create-form">
            <textarea id="message" placeholder="Введите ваш секрет..." class="w-full h-32 p-4 bg-slate-900 border border-slate-700 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none resize-none mb-4"></textarea>
            <input type="email" id="email" placeholder="Email для уведомления (опционально)" class="w-full p-3 bg-slate-900 border border-slate-700 rounded-lg mb-6 outline-none focus:ring-2 focus:ring-indigo-500">
            <button onclick="createSecret()" id="btn-submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-lg transition duration-200">
                Зашифровать
            </button>
        </div>

        <div id="result" class="hidden mt-6">
            <p class="text-sm text-slate-400 mb-2 font-medium">Ваша ссылка (доступна 1 раз):</p>
            <div class="flex items-center gap-2">
                <input type="text" id="secret-url" readonly class="w-full p-2 bg-slate-900 border border-slate-700 rounded text-indigo-300 text-sm">
                <button onclick="copyLink()" class="bg-slate-700 hover:bg-slate-600 px-3 py-2 rounded text-sm">Copy</button>
            </div>
            <button onclick="location.reload()" class="w-full mt-6 text-slate-500 text-sm hover:text-slate-300 underline">Создать еще один</button>
        </div>
    </div>

    <script>
        async function createSecret() {
            const btn = document.getElementById('btn-submit');
            const message = document.getElementById('message').value;
            const email = document.getElementById('email').value;

            if(!message) return alert('Введите текст!');

            btn.disabled = true;
            btn.innerText = 'Шифруем...';

            try {
                const response = await fetch('/api/secrets', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ message, email })
                });

                const data = await response.json();

                if (response.ok) {
                    document.getElementById('create-form').classList.add('hidden');
                    document.getElementById('result').classList.remove('hidden');
                    document.getElementById('secret-url').value = data.link;
                } else {
                    alert(data.message || 'Ошибка сервера');
                }
            } catch (e) {
                alert('Произошла ошибка при запросе');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Зашифровать';
            }
        }

        function copyLink() {
            const copyText = document.getElementById("secret-url");
            copyText.select();
            navigator.clipboard.writeText(copyText.value);
            alert("Ссылка скопирована!");
        }
    </script>
</body>
</html>