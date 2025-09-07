<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Помощник вайб-кодера / Vibe coder's assistant VCA /Экспорт проекта</title>
    <style>
        /* Mobile-first стили */
        * { box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            margin: 0;
            padding: 10px;
            background: #f5f5f5; 
            font-size: 14px;
        }
        .container { 
            width: 100%;
            /*max-width: 800px;*/
            margin: 0 auto;
            background: white; 
            padding: 15px; 
            border-radius: 5px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
            overflow: hidden;
            overflow-wrap: break-word;
        }
        .file-item, .folder-item { 
            margin: 8px 0;
            font-size: 16px;
        }
        .children { 
            margin-left: 15px; 
        }
        .progress { 
            margin: 15px 0; 
            color: #555; 
            font-size: 16px;
        }
        button { 
            padding: 12px 18px; 
            background: #4CAF50; 
            color: white; 
            border: none; 
            cursor: pointer; 
            font-size: 16px;
            min-height: 44px;
        }
        button:disabled { 
            background: #cccccc; 
        }
        .toggle-icon {
            display: inline-block;
            width: 20px;
            text-align: center;
            cursor: pointer;
            margin-right: 5px;
            font-size: 16px;
        }
        .collapsed .children { 
            display: none; 
        }
        .collapsed .toggle-icon::after { 
            content: '▶'; 
        }
        .expanded .toggle-icon::after { 
            content: '▼'; 
        }
        .folder-header { 
            cursor: pointer; 
            padding: 5px 0;
        }
        .logout-btn {
            float: right; 
            background: #f44336;
            padding: 8px 12px; 
            font-size: 14px;
            min-height: auto;
        }
        .export-options {
            margin: 15px 0;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .export-options label {
            display: block;
            margin: 10px 0;
            font-size: 16px;
        }
        .export-actions {
            margin-top: 15px;
        }
        
        /* Увеличиваем чекбоксы */
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            vertical-align: middle;
        }
        
        /* Адаптивность заголовка */
        h3 {
            font-size: 18px;
            margin-top: 40px;
            margin-right: 80px; /* место для кнопки выхода */
        }
        
        /* Медиа-запросы для десктопов */
        @media (min-width: 768px) {
            body { padding: 20px; }
            .container { padding: 20px; }
            .export-options label { display: inline-block; margin-right: 15px; }
            .children { margin-left: 25px; }
            h3 { font-size: 22px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="logout-btn" onclick="logout()">Выйти</button>
        <h3>Помощник вайб-кодера <br> Vibe coder's assistant (VCA) <br>Экспорт проекта</h3>
        <button id="scanBtn">Сканировать файловую систему</button>
        <div id="progress" class="progress"></div>
        <div id="fileTree"></div>
       
        <div id="exportSection" style="display:none;">
            <div class="export-options">
                <label>
                    <input type="radio" name="exportType" value="with_db" checked>
                    Экспорт структуры базы данных
                </label>
                <label>
                    <input type="radio" name="exportType" value="without_db">
                    Экспорт без структры базы данных
                </label>
            </div>
            <div class="export-actions">
                <button id="exportBtn" disabled>Выгрузить выбранное</button>
            </div>
        </div>
    </div>
   
    <script>
        document.getElementById('scanBtn').addEventListener('click', scanFileSystem);
        document.getElementById('exportBtn').addEventListener('click', exportData);
        let projectStructure = null;

        async function scanFileSystem() {
                const scanBtn = document.getElementById('scanBtn');
                const progress = document.getElementById('progress');
                
                scanBtn.disabled = true;
                progress.textContent = 'Сканирование...';

            try {
                const response = await fetch("{{ route('vca.scan') }}", {
                    method: 'POST', // Явно указываем метод POST
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
               
                if (data.error) {
                    progress.textContent = 'Ошибка: ' + data.error;
                    return;
                }

                projectStructure = data;
                renderFileTree(data);
                document.getElementById('exportSection').style.display = 'block';
                progress.textContent = `Найдено: ${data.stats.files} файлов, ${data.stats.folders} папок`;
            } catch(e) {
                progress.textContent = 'Ошибка сканирования: ' + e.message;
                console.error(e);
            } finally {
                scanBtn.disabled = false;
            }
        }

        function renderFileTree(data) {
            const container = document.getElementById('fileTree');
            container.innerHTML = '';
            if (data.tree && data.tree.length > 0) {
                container.appendChild(createTreeElement(data.tree[0]));
            }
        }

        function createTreeElement(item) {
            const element = document.createElement('div');
           
            if(item.type === 'folder') {
                element.className = 'folder-item expanded';
               
                const header = document.createElement('div');
                header.className = 'folder-header';
                header.innerHTML = `
                    <span class="toggle-icon"></span>
                    <label>
                        <input type="checkbox" class="folder-checkbox">
                        <strong>📁 ${item.name}</strong> (${item.size} KB)
                    </label>
                `;
                element.appendChild(header);
               
                const childrenContainer = document.createElement('div');
                childrenContainer.className = 'children';
               
                if (item.children) {
                    item.children.forEach(child => {
                        childrenContainer.appendChild(createTreeElement(child));
                    });
                }
                element.appendChild(childrenContainer);
               
                const toggleIcon = header.querySelector('.toggle-icon');
                toggleIcon.addEventListener('click', function(e) {
                    e.stopPropagation();
                    element.classList.toggle('collapsed');
                    element.classList.toggle('expanded');
                });
               
                const checkbox = header.querySelector('.folder-checkbox');
                checkbox.addEventListener('change', function() {
                    const childCheckboxes = childrenContainer.querySelectorAll('input[type="checkbox"]');
                    childCheckboxes.forEach(child => {
                        child.checked = this.checked;
                    });
                    updateExportButtonState();
                });
            } else {
                element.className = 'file-item';
                element.innerHTML = `
                    <label>
                        <input type="checkbox" class="file-checkbox" data-path="${item.path}">
                        📄 ${item.name} (${item.size} KB)
                    </label>
                `;
                const checkbox = element.querySelector('.file-checkbox');
                checkbox.addEventListener('change', updateExportButtonState);
            }
            return element;
        }

        function updateExportButtonState() {
            const exportBtn = document.getElementById('exportBtn');
            const selectedFiles = document.querySelectorAll('.file-checkbox:checked');
            exportBtn.disabled = selectedFiles.length === 0;
        }

        async function exportData() {
            const exportBtn = document.getElementById('exportBtn');
            const progress = document.getElementById('progress');
           
            const selectedFiles = [];
            document.querySelectorAll('.file-checkbox:checked').forEach(checkbox => {
                selectedFiles.push(checkbox.dataset.path);
            });

            if(selectedFiles.length === 0) {
                progress.textContent = 'Выберите файлы для экспорта';
                return;
            }

            const exportType = document.querySelector('input[name="exportType"]:checked').value;
            const includeDb = exportType === 'with_db';
           
            exportBtn.disabled = true;
            progress.textContent = 'Подготовка экспорта...';

            try {
                const response = await fetch("{{ route('vca.export') }}", {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        files: selectedFiles,
                        structure: projectStructure,
                        includeDb: includeDb
                    })
                });
               
                if (!response.ok) {
                    throw new Error(await response.text());
                }
               
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = includeDb ? 'project_export_with_db.txt' : 'project_export_without_db.txt';
                document.body.appendChild(a);
                a.click();
                a.remove();
                
                progress.textContent = 'Экспорт завершен!';
            } catch(e) {
                progress.textContent = 'Ошибка: ' + e.message;
                console.error(e);
            } finally {
                exportBtn.disabled = false;
            }
        }
       
        function logout() {
            window.location.href = "{{ route('vca.logout') }}";
        }
    </script>
</body>
</html>