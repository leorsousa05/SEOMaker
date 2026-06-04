(function () {
    'use strict';

    var uploadZone = document.getElementById('upload-zone');
    var uploadInput = document.getElementById('upload-input');
    var progressBar = document.getElementById('upload-progress-bar');
    var progressContainer = document.getElementById('upload-progress');

    if (!uploadZone || !uploadInput) return;

    // Drag & drop
    uploadZone.addEventListener('dragover', function (e) {
        e.preventDefault();
        uploadZone.classList.add('upload-zone--dragover');
    });

    uploadZone.addEventListener('dragleave', function () {
        uploadZone.classList.remove('upload-zone--dragover');
    });

    uploadZone.addEventListener('drop', function (e) {
        e.preventDefault();
        uploadZone.classList.remove('upload-zone--dragover');
        if (e.dataTransfer.files.length > 0) {
            handleFiles(e.dataTransfer.files);
        }
    });

    // File input
    uploadInput.addEventListener('change', function () {
        if (uploadInput.files.length > 0) {
            handleFiles(uploadInput.files);
        }
    });

    function handleFiles(files) {
        var validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        var maxSize = 5 * 1024 * 1024;
        var formData = new FormData();
        var validCount = 0;

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            if (!validTypes.includes(file.type)) {
                alert('Tipo não permitido: ' + file.name);
                continue;
            }
            if (file.size > maxSize) {
                alert('Arquivo muito grande: ' + file.name);
                continue;
            }
            formData.append('files[]', file);
            validCount++;
        }

        if (validCount === 0) return;

        // Show progress
        progressContainer.style.display = 'block';
        progressBar.style.width = '0%';

        var xhr = new XMLHttpRequest();

        xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
                var percent = Math.round((e.loaded / e.total) * 100);
                progressBar.style.width = percent + '%';
            }
        });

        xhr.addEventListener('load', function () {
            progressBar.style.width = '100%';
            setTimeout(function () {
                progressContainer.style.display = 'none';
                progressBar.style.width = '0%';
            }, 500);

            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    window.location.reload();
                } else {
                    alert(response.error || 'Erro no upload');
                }
            } catch (e) {
                window.location.reload();
            }
        });

        xhr.addEventListener('error', function () {
            progressContainer.style.display = 'none';
            alert('Erro ao enviar arquivo(s)');
        });

        xhr.open('POST', '/admin/media/upload');
        xhr.send(formData);
    }

    // Copy URL buttons
    document.querySelectorAll('.btn-copy-url').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var url = btn.dataset.url;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(function () {
                    btn.classList.add('copied');
                    setTimeout(function () { btn.classList.remove('copied'); }, 1500);
                });
            } else {
                var ta = document.createElement('textarea');
                ta.value = url;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                btn.classList.add('copied');
                setTimeout(function () { btn.classList.remove('copied'); }, 1500);
            }
        });
    });

    // Modal functionality
    window.openMediaModal = function (callback) {
        var overlay = document.getElementById('media-modal-overlay');
        if (!overlay) return;

        overlay.style.display = 'flex';
        var selectedUrl = null;

        overlay.querySelectorAll('.media-modal-item').forEach(function (item) {
            item.classList.remove('selected');
            item.onclick = function () {
                overlay.querySelectorAll('.media-modal-item').forEach(function (i) { i.classList.remove('selected'); });
                item.classList.add('selected');
                selectedUrl = item.dataset.url;
            };
        });

        document.getElementById('media-modal-cancel').onclick = function () {
            overlay.style.display = 'none';
        };

        document.getElementById('media-modal-close').onclick = function () {
            overlay.style.display = 'none';
        };

        document.getElementById('media-modal-confirm').onclick = function () {
            overlay.style.display = 'none';
            if (callback && selectedUrl) {
                callback(selectedUrl);
            }
        };

        overlay.onclick = function (e) {
            if (e.target === overlay) {
                overlay.style.display = 'none';
            }
        };
    };
})();
