<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Explorateur de fichiers Windows 11</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}
		body {
			background: linear-gradient(135deg, #0c1445 0%, #450c2d 100%);
			height: 100vh;
			overflow: hidden;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		.explorer {
			display: flex;
			flex-direction: column;
			height: 90vh;
			width: 90vw;
			background-color: #202020;
			box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
			border-radius: 8px;
			overflow: hidden;
			position: relative;
			color: #ffffff;
		}
		.window-controls {
			position: absolute;
			top: 12px;
			right: 15px;
			z-index: 200;
			display: flex;
			gap: 8px;
		}
		.window-button {
			width: 14px;
			height: 14px;
			border-radius: 50%;
			cursor: pointer;
		}
		.minimize { background-color: #ffd830; }
		.maximize { background-color: #28ca41; }
		.close { background-color: #ff5f57; }
		
		.title-bar {
			height: 32px;
			background-color: #323232;
			display: flex;
			align-items: center;
			padding: 0 15px;
			color: #ffffff;
			font-size: 12px;
			font-weight: 500;
		}
		.header {
			height: 40px;
			background-color: #2d2d2d;
			border-bottom: 1px solid #3a3a3a;
			display: flex;
			align-items: center;
			padding: 0 10px;
			z-index: 100;
		}
		.address-bar {
			display: flex;
			align-items: center;
			background-color: rgba(45, 45, 45, 0.8);
			border-radius: 4px;
			padding: 5px 10px;
			margin: 0 10px;
			flex-grow: 1;
			border: 1px solid #3a3a3a;
			color: #ffffff;
		}
		.address-bar i {
			margin-right: 10px;
			color: #61aaee;
			cursor: pointer;
			font-size: 14px;
		}
		.address-input {
			background: transparent;
			border: none;
			color: white;
			flex-grow: 1;
			outline: none;
			font-size: 12px;
		}
		.navigation-button {
			width: 24px;
			height: 24px;
			display: flex;
			align-items: center;
			justify-content: center;
			border-radius: 4px;
			margin-right: 5px;
			color: #ffffff;
			cursor: pointer;
		}
		.navigation-button:hover {
			background-color: #3f3f3f;
		}
		.content-container {
			display: flex;
			flex-grow: 1;
			overflow: hidden;
		}
		.sidebar {
			width: 230px;
			background-color: rgb(23, 23, 23);
			border-right: 1px solid #3a3a3a;
			overflow-y: auto;
		}
		.sidebar-section {
			margin-bottom: 15px;
		}
		.sidebar-header {
			padding: 8px 15px;
			font-size: 12px;
			color: #b0b0b0;
			font-weight: 600;
		}
		.sidebar ul {
			list-style-type: none;
		}
		.sidebar li {
			padding: 8px 15px;
			display: flex;
			align-items: center;
			cursor: pointer;
			font-size: 13px;
			color: #ffffff;
		}
		.sidebar li:hover {
			background-color: #3a3a3a;
		}
		.sidebar li.active {
			background-color: #353535;
		}
		.sidebar li i {
			margin-right: 10px;
			color: #61aaee;
			width: 18px;
			text-align: center;
			font-size: 16px;
		}
		.content {
			flex-grow: 1;
			padding: 15px;
			overflow-y: auto;
			background-color: rgb(24, 24, 24);
		}
		.breadcrumb {
			margin-bottom: 15px;
			color: #b0b0b0;
			display: flex;
			align-items: center;
			font-size: 12px;
		}
		.breadcrumb span {
			margin: 0 5px;
			cursor: pointer;
		}
		.breadcrumb span:hover {
			color: #61aaee;
			text-decoration: underline;
		}
		.files {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
			gap: 15px;
			padding: 5px 0;
		}
		.file {
			display: flex;
			flex-direction: column;
			align-items: center;
			cursor: pointer;
			padding: 10px 5px;
			border-radius: 4px;
			text-align: center;
			color: #ffffff;
		}
		.file:hover {
			background-color: #2a2a2a;
		}
		.file.selected {
			background-color: #353535;
		}
		.file i {
			font-size: 32px;
			margin-bottom: 6px;
		}
		.folder {
			color: #fcc936;
			font-size: 11px;
			overflow: hidden;
			text-overflow: ellipsis;
			width: 100%;
			max-width: 90px;
			text-align: center;
		}
		.file-name {
			width: 100%;
			max-width: 90px;
			text-align: center;
			font-size: 11px;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}
		.status-bar {
			height: 25px;
			background-color: rgb(28,28,28);
			border-top: 1px solid rgb(28,28,28);
			display: flex;
			align-items: center;
			padding: 0 15px;
			font-size: 11px;
			color: rgb(255, 255, 255);
		}
		.search-modal {
			position: absolute;
			top: 80px;
			right: 15px;
			width: 300px;
			background-color: #2d2d2d;
			border: 1px solid #3a3a3a;
			border-radius: 4px;
			padding: 15px;
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
			display: none;
			z-index: 300;
		}
		.search-input {
			width: 100%;
			background-color: #1d1d1d;
			border: 1px solid #3a3a3a;
			border-radius: 4px;
			padding: 8px;
			color: white;
			margin-bottom: 10px;
		}
		.search-results {
			max-height: 200px;
			overflow-y: auto;
		}
		.search-item {
			padding: 8px;
			border-radius: 4px;
			cursor: pointer;
			display: flex;
			align-items: center;
		}
		.search-item:hover {
			background-color: #3a3a3a;
		}
		.search-item i {
			margin-right: 8px;
			width: 16px;
			text-align: center;
		}
		.search-path {
			color: #999;
			font-size: 10px;
			margin-left: 24px;
		}
		.no-results {
			text-align: center;
			color: #999;
			padding: 10px;
		}
	</style>
</head>
<body>
	<div class="explorer">
		<div class="window-controls">
			<div class="window-button minimize"></div>
			<div class="window-button maximize"></div>
			<div class="window-button close"></div>
		</div>
		
		<div class="title-bar">
			Explorateur de fichiers
		</div>
		
		<div class="header">
			<div class="navigation-button" id="back-button"><i class="fas fa-chevron-left"></i></div>
			<div class="navigation-button" id="forward-button"><i class="fas fa-chevron-right"></i></div>
			<div class="navigation-button" id="up-button"><i class="fas fa-arrow-up"></i></div>
			
			<div class="address-bar">
				<i class="fas fa-folder"></i>
				<input type="text" class="address-input" id="path-input" placeholder="Entrez un chemin (ex: Bureau/Dossier)">
			</div>
			
			<div class="navigation-button" id="search-button"><i class="fas fa-search"></i></div>
		</div>
		
		<div class="content-container">
			<div class="sidebar">
				<div class="sidebar-section">
					<div class="sidebar-header">Accès rapide</div>
					<ul>
						<li class="active" onclick="navigateTo('home')"><i class="fas fa-home"></i> Accueil</li>
						<li onclick="navigateTo('desktop')"><i class="fas fa-desktop"></i> Bureau</li>
						<li onclick="navigateTo('documents')"><i class="fas fa-folder"></i> Documents</li>
						<li onclick="navigateTo('downloads')"><i class="fas fa-download"></i> Téléchargements</li>
					</ul>
				</div>
				
				<div class="sidebar-section">
					<div class="sidebar-header">Ce PC</div>
					<ul>
						<li onclick="navigateTo('images')"><i class="fas fa-image"></i> Images</li>
						<li onclick="navigateTo('music')"><i class="fas fa-music"></i> Musique</li>
						<li onclick="navigateTo('videos')"><i class="fas fa-video"></i> Vidéos</li>
						<li onclick="navigateTo('projects')"><i class="fas fa-project-diagram"></i> Projets</li>
					</ul>
				</div>
			</div>
			
			<div class="content" id="content">
				<!-- Content will be loaded here -->
			</div>
		</div>
		
		<div class="status-bar">
			<span>4 éléments</span>
		</div>

		<div class="search-modal" id="search-modal">
			<input type="text" class="search-input" id="search-input" placeholder="Rechercher un fichier ou dossier">
			<div class="search-results" id="search-results">
				<!-- Search results will be displayed here -->
			</div>
		</div>
	</div>

	<script>
		// File system structure definition
		const fileSystem = {
			home: {
				type: 'folder',
				name: 'Accueil',
				contents: {
					'desktop.lnk': { type: 'shortcut', target: 'desktop' },
					'documents.lnk': { type: 'shortcut', target: 'documents' },
					'downloads.lnk': { type: 'shortcut', target: 'downloads' }
				}
			},
			desktop: {
				type: 'folder',
				name: 'Bureau',
				contents: {
					'Nouveau document.txt': { type: 'file', fileType: 'text' },
					'Présentation.pptx': { type: 'file', fileType: 'powerpoint' },
					'Raccourci vers Projets.lnk': { type: 'shortcut', target: 'projects' }
				}
			},
			documents: {
				type: 'folder',
				name: 'Documents',
				contents: {
					'CV.pdf': { type: 'file', fileType: 'pdf' },
					'Rapport.docx': { type: 'file', fileType: 'word' },
					'Notes.txt': { type: 'file', fileType: 'text' }
				}
			},
			downloads: {
				type: 'folder',
				name: 'Téléchargements',
				contents: {
					'installer.exe': { type: 'file', fileType: 'executable' },
					'archive.zip': { type: 'file', fileType: 'archive' }
				}
			},
			images: {
				type: 'folder',
				name: 'Images',
				contents: {
					'Vacances': {
						type: 'folder',
						contents: {
							'plage.jpg': { type: 'file', fileType: 'image' },
							'montagne.jpg': { type: 'file', fileType: 'image' }
						}
					},
					'profile.png': { type: 'file', fileType: 'image' }
				}
			},
			music: {
				type: 'folder',
				name: 'Musique',
				contents: {
					'Favoris': {
						type: 'folder',
						contents: {
							'chanson1.mp3': { type: 'file', fileType: 'audio' },
							'chanson2.mp3': { type: 'file', fileType: 'audio' }
						}
					}
				}
			},
			videos: {
				type: 'folder',
				name: 'Vidéos',
				contents: {
					'Vacances.mp4': { type: 'file', fileType: 'video' }
				}
			},
			projects: {
				type: 'folder',
				name: 'Projets',
				contents: {
					'Site Web': {
						type: 'folder',
						contents: {
							'index.html': { type: 'file', fileType: 'html', link: 'https://example.com' },
							'style.css': { type: 'file', fileType: 'css' }
						}
					},
					'Application Mobile': {
						type: 'folder',
						contents: {
							'app.js': { type: 'file', fileType: 'javascript' },
							'README.md': { type: 'file', fileType: 'markdown', link: 'https://github.com' }
						}
					}
				}
			}
		};

		// Map des noms localisés pour la navigation
		const nameToKeyMap = {
			'accueil': 'home',
			'bureau': 'desktop',
			'documents': 'documents',
			'téléchargements': 'downloads',
			'images': 'images',
			'musique': 'music',
			'vidéos': 'videos',
			'projets': 'projects'
		};

		// Navigation path tracking
		let currentPath = ['home'];
		// Navigation history
		const history = {
			past: [],
			future: []
		};
		
		function getIconClass(item) {
			if (item.type === 'folder') return 'fas fa-folder folder';
			if (item.type === 'shortcut') return 'fas fa-external-link-alt';
			
			// File type icons
			const fileTypeIcons = {
				'text': 'fas fa-file-alt',
				'pdf': 'fas fa-file-pdf',
				'word': 'fas fa-file-word',
				'powerpoint': 'fas fa-file-powerpoint',
				'image': 'fas fa-file-image',
				'audio': 'fas fa-file-audio',
				'video': 'fas fa-file-video',
				'archive': 'fas fa-file-archive',
				'executable': 'fas fa-file-code',
				'html': 'fab fa-html5',
				'css': 'fab fa-css3-alt',
				'javascript': 'fab fa-js',
				'markdown': 'fab fa-markdown'
			};
			
			return fileTypeIcons[item.fileType] || 'fas fa-file file-icon';
		}

		function navigateTo(folderPath, addToHistory = true) {
			if (addToHistory) {
				// Add current path to history
				history.past.push([...currentPath]);
				history.future = []; // Clear forward history when navigating
			}
			
			if (typeof folderPath === 'string') {
				currentPath = [folderPath]; // Reset path when clicking sidebar
			}
			
			// Find the current folder based on path
			let current = fileSystem;
			let pathDisplay = [];
			
			for (const segment of currentPath) {
				if (current[segment]) {
					current = current[segment];
					pathDisplay.push(current.name || segment);
				} else if (current.contents && current.contents[segment]) {
					current = current.contents[segment];
					pathDisplay.push(current.name || segment);
				}
			}

			// Update address bar input
			document.getElementById('path-input').value = pathDisplay.join('/');
			
			// Set active state in sidebar
			document.querySelectorAll('.sidebar li').forEach(item => {
				item.classList.remove('active');
				if (item.getAttribute('onclick').includes(currentPath[0])) {
					item.classList.add('active');
				}
			});
			
			// Generate content HTML
			const content = document.getElementById('content');
			let html = `
				<div class="breadcrumb">
					<span onclick="navigateTo('home')"><i class="fas fa-home"></i></span>
			`;
			
			// Add breadcrumb path
			let breadcrumbPath = [];
			for (let i = 0; i < currentPath.length; i++) {
				breadcrumbPath.push(currentPath[i]);
				const pathName = fileSystem[currentPath[i]]?.name || currentPath[i];
				html += `<span> > </span><span onclick="navigateToBreadcrumb(${i})">${pathName}</span>`;
			}
			
			html += `</div><div class="files">`;
			
			// If we're at the root of the file system
			if (currentPath.length === 1) {
				const folder = fileSystem[currentPath[0]];
				
				if (folder && folder.contents) {
					// Display folder contents
					for (const [name, item] of Object.entries(folder.contents)) {
						html += generateFileHtml(name, item);
					}
				}
			} else {
				// Navigate to the correct nested folder
				let currentFolder = fileSystem[currentPath[0]];
				for (let i = 1; i < currentPath.length; i++) {
					if (currentFolder.contents && currentFolder.contents[currentPath[i]]) {
						currentFolder = currentFolder.contents[currentPath[i]];
					}
				}
				
				if (currentFolder && currentFolder.contents) {
					// Display folder contents
					for (const [name, item] of Object.entries(currentFolder.contents)) {
						html += generateFileHtml(name, item);
					}
				}
			}
			
			html += `</div>`;
			content.innerHTML = html;
			
			// Update status bar
			updateStatusBar();
			
			// Update back/forward buttons state
			updateNavigationButtons();
		}

		function updateNavigationButtons() {
			document.getElementById('back-button').style.opacity = history.past.length ? '1' : '0.5';
			document.getElementById('forward-button').style.opacity = history.future.length ? '1' : '0.5';
		}

		function goBack() {
			if (history.past.length === 0) return;
			
			// Add current path to future history
			history.future.push([...currentPath]);
			
			// Go to previous path
			currentPath = history.past.pop();
			navigateTo(undefined, false);
		}

		function goForward() {
			if (history.future.length === 0) return;
			
			// Add current path to past history
			history.past.push([...currentPath]);
			
			// Go to next path
			currentPath = history.future.pop();
			navigateTo(undefined, false);
		}

		function goUp() {
			if (currentPath.length <= 1) return;
			
			// Remove the last segment of the path
			currentPath.pop();
			navigateTo(undefined, true);
		}

		function updateStatusBar() {
			let count = 0;
			if (currentPath.length === 1) {
				const folder = fileSystem[currentPath[0]];
				if (folder && folder.contents) {
					count = Object.keys(folder.contents).length;
				}
			} else {
				let currentFolder = fileSystem[currentPath[0]];
				for (let i = 1; i < currentPath.length; i++) {
					if (currentFolder.contents && currentFolder.contents[currentPath[i]]) {
						currentFolder = currentFolder.contents[currentPath[i]];
					}
				}
				if (currentFolder && currentFolder.contents) {
					count = Object.keys(currentFolder.contents).length;
				}
			}
			document.querySelector('.status-bar span').textContent = `${count} élément${count > 1 ? 's' : ''}`;
		}

		function generateFileHtml(name, item) {
			const iconClass = getIconClass(item);
			const clickAction = item.type === 'folder' 
				? `openFolder('${name}')`
				: item.type === 'shortcut'
				? `navigateTo('${item.target}')`
				: item.link 
				? `window.open('${item.link}', '_blank')`
				: '';
			
			return `
				<div class="file" onclick="${clickAction}">
					<i class="${iconClass}"></i>
					<div class="file-name">${name}</div>
				</div>
			`;
		}

		function openFolder(folderName) {
			currentPath.push(folderName);
			navigateTo(undefined, true);
		}

		function navigateToBreadcrumb(index) {
			// Truncate path to the selected breadcrumb index
			currentPath = currentPath.slice(0, index + 1);
			navigateTo(undefined, true);
		}

		function navigateByPath(path) {
			// Handles both '/' and '>' as separators
			let segments;
			if (path.includes('/')) {
				segments = path.split('/').map(segment => segment.trim());
			} else if (path.includes('\\')) {
				segments = path.split('\\').map(segment => segment.trim());
			} else {
				segments = path.split(' > ').map(segment => segment.trim());
			}
			
			if (segments.length === 0) return;
			
			// Try to find the root folder
			let rootKey = null;
			let rootSegment = segments[0].toLowerCase();
			
			// Check for direct match or localized name match
			if (fileSystem[rootSegment]) {
				rootKey = rootSegment;
			} else {
				for (const [key, folder] of Object.entries(fileSystem)) {
					if ((folder.name && folder.name.toLowerCase() === rootSegment) || 
						(nameToKeyMap[rootSegment] === key)) {
						rootKey = key;
						break;
					}
				}
			}
			
			if (!rootKey) {
				alert(`Dossier non trouvé : ${segments[0]}`);
				return;
			}
			
			// Start new path with root folder
			const newPath = [rootKey];
			
			// Process remaining segments
			if (segments.length > 1) {
				let currentFolder = fileSystem[rootKey];
				
				for (let i = 1; i < segments.length; i++) {
					let segmentFound = false;
					const segment = segments[i].toLowerCase();
					
					// Look through current folder contents
					if (currentFolder.contents) {
						for (const [name, item] of Object.entries(currentFolder.contents)) {
							if (name.toLowerCase() === segment || 
								(item.name && item.name.toLowerCase() === segment)) {
								// Add this segment to path
								newPath.push(name);
								currentFolder = item;
								segmentFound = true;
								break;
							}
						}
					}
					
					if (!segmentFound) {
						alert(`Chemin non trouvé : ${segments[i]} dans ${segments[i-1]}`);
						return;
					}
				}
			}
			
			// Navigate to the found path
			currentPath = newPath;
			navigateTo(undefined, true);
		}

		// Search functionality
		function searchFiles(query) {
			if (!query || query.trim() === '') {
				return [];
			}
			
			query = query.toLowerCase();
			const results = [];
			
			// Recursive function to search through file system
			function searchInFolder(folder, folderPath = [], folderName = '') {
				if (folder.contents) {
					for (const [name, item] of Object.entries(folder.contents)) {
						// Check if name matches the query
						if (name.toLowerCase().includes(query)) {
							results.push({
								name,
								path: [...folderPath],
								fullPath: `${folderName}/${name}`,
								item
							});
						}
						
						// If it's a folder, search inside it
						if (item.type === 'folder') {
							searchInFolder(item, [...folderPath, name], `${folderName}/${name}`);
						}
					}
				}
			}
			
			// Start search from root folders
			for (const [key, folder] of Object.entries(fileSystem)) {
				if (key.toLowerCase().includes(query)) {
					results.push({
						name: folder.name || key,
						path: [key],
						fullPath: folder.name || key,
						item: folder
					});
				}
				searchInFolder(folder, [key], folder.name || key);
			}
			
			return results;
		}

		function displaySearchResults(results) {
			const resultsContainer = document.getElementById('search-results');
			
			if (results.length === 0) {
				resultsContainer.innerHTML = '<div class="no-results">Aucun résultat trouvé</div>';
				return;
			}
			
			let html = '';
			
			for (const result of results) {
				const iconClass = getIconClass(result.item);
				
				html += `
					<div class="search-item" onclick="navigateToSearchResult(${JSON.stringify(result.path).replace(/"/g, '&quot;')})">
						<i class="${iconClass}"></i>
						<div>
							<div>${result.name}</div>
							<div class="search-path">${result.fullPath}</div>
						</div>
					</div>
				`;
			}
			
			resultsContainer.innerHTML = html;
		}

		function navigateToSearchResult(path) {
			currentPath = path;
			navigateTo(undefined, true);
			toggleSearchModal(false);
		}

		function toggleSearchModal(show) {
			const modal = document.getElementById('search-modal');
			modal.style.display = show ? 'block' : 'none';
			
			if (show) {
				document.getElementById('search-input').focus();
				document.getElementById('search-input').value = '';
				document.getElementById('search-results').innerHTML = '';
			}
		}

		// Event listeners
		window.onload = function() {
			// Initialize with home folder
			navigateTo('home');
			
			// Add event listeners for navigation buttons
			document.getElementById('back-button').addEventListener('click', goBack);
			document.getElementById('forward-button').addEventListener('click', goForward);
			document.getElementById('up-button').addEventListener('click', goUp);
			
			// Path input navigation
			document.getElementById('path-input').addEventListener('keydown', (event) => {
				if (event.key === 'Enter') {
					navigateByPath(event.target.value);
				}
			});
			
			// Search functionality
			document.getElementById('search-button').addEventListener('click', () => {
				toggleSearchModal(true);
			});
			
			document.getElementById('search-input').addEventListener('input', (event) => {
				const results = searchFiles(event.target.value);
				displaySearchResults(results);
			});
			
			// Close search when clicking outside
			document.addEventListener('click', (event) => {
				const searchModal = document.getElementById('search-modal');
				const searchButton = document.getElementById('search-button');
				
				if (searchModal.style.display === 'block' && 
					!searchModal.contains(event.target) &&
					!searchButton.contains(event.target)) {
					toggleSearchModal(false);
				}
			});
		};
	</script>
</body>
</html>
