(function () {
    'use strict';

    var editor, blocks = [], hiddenInput;

    function initBlockEditor(container) {
        editor = container;
        hiddenInput = document.getElementById('content_blocks');
        
        var data = editor.dataset.blocks || '[]';
        try {
            blocks = JSON.parse(data);
        } catch (e) {
            blocks = [{type: 'text', content: '<p>Comece a editar...</p>'}];
        }

        renderEditor();
        renderBlocks();
    }

    function renderEditor() {
        var html = '<div class="be-toolbar">';
        html += '<span class="be-label">Blocos:</span>';
        html += '<button type="button" class="be-btn" data-add="text">+ Texto</button>';
        html += '<button type="button" class="be-btn" data-add="image">+ Imagem</button>';
        html += '<button type="button" class="be-btn" data-add="gallery">+ Galeria</button>';
        html += '<button type="button" class="be-btn" data-add="video">+ Vídeo</button>';
        html += '<button type="button" class="be-btn" data-add="map">+ Mapa</button>';
        html += '<button type="button" class="be-btn" data-add="cta">+ Botão</button>';
        html += '<button type="button" class="be-btn" data-add="faq">+ FAQ</button>';
        html += '<button type="button" class="be-btn" data-add="spacer">+ Espaço</button>';
        html += '</div>';
        html += '<div class="be-blocks"></div>';
        editor.innerHTML = html;

        // Add block buttons
        editor.querySelectorAll('[data-add]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                addBlock(btn.dataset.add);
            });
        });

        syncHiddenInput();
    }

    function addBlock(type) {
        var block;
        switch (type) {
            case 'text':
                block = {type: 'text', content: '<p>Novo parágrafo...</p>'};
                break;
            case 'image':
                block = {type: 'image', media_id: 0, alt: '', caption: '', align: 'center'};
                break;
            case 'gallery':
                block = {type: 'gallery', media_ids: [], columns: 3};
                break;
            case 'video':
                block = {type: 'video', url: '', provider: 'youtube'};
                break;
            case 'map':
                block = {type: 'map', address_id: 0, zoom: 15};
                break;
            case 'cta':
                block = {type: 'cta', text: 'Clique Aqui', url: '#', style: 'primary'};
                break;
            case 'faq':
                block = {type: 'faq', items: [{question: 'Pergunta?', answer: 'Resposta.'}]};
                break;
            case 'spacer':
                block = {type: 'spacer', height: 40};
                break;
            default:
                block = {type: 'text', content: ''};
        }
        blocks.push(block);
        renderBlocks();
        syncHiddenInput();
    }

    function removeBlock(index) {
        blocks.splice(index, 1);
        renderBlocks();
        syncHiddenInput();
    }

    function moveBlock(index, direction) {
        var newIndex = index + direction;
        if (newIndex < 0 || newIndex >= blocks.length) return;
        var temp = blocks[index];
        blocks[index] = blocks[newIndex];
        blocks[newIndex] = temp;
        renderBlocks();
        syncHiddenInput();
    }

    function renderBlocks() {
        var container = editor.querySelector('.be-blocks');
        if (!container) return;

        var html = '';
        if (blocks.length === 0) {
            html = '<p class="be-empty">Nenhum bloco ainda. Clique nos botões acima para adicionar conteúdo.</p>';
        } else {
            blocks.forEach(function (block, i) {
                html += renderBlockItem(block, i);
            });
        }
        container.innerHTML = html;

        // Attach event handlers
        container.querySelectorAll('.be-block-remove').forEach(function (btn) {
            btn.addEventListener('click', function () {
                removeBlock(parseInt(btn.dataset.index));
            });
        });

        container.querySelectorAll('.be-block-up').forEach(function (btn) {
            btn.addEventListener('click', function () {
                moveBlock(parseInt(btn.dataset.index), -1);
            });
        });

        container.querySelectorAll('.be-block-down').forEach(function (btn) {
            btn.addEventListener('click', function () {
                moveBlock(parseInt(btn.dataset.index), 1);
            });
        });

        // Text contenteditable
        container.querySelectorAll('.be-text-content').forEach(function (el) {
            el.addEventListener('input', function () {
                var idx = parseInt(el.dataset.index);
                blocks[idx].content = el.innerHTML;
                syncHiddenInput();
            });
        });

        // Regular inputs
        container.querySelectorAll('.be-input').forEach(function (input) {
            input.addEventListener('input', function () {
                var idx = parseInt(input.dataset.index);
                var prop = input.dataset.prop;
                blocks[idx][prop] = input.value;
                syncHiddenInput();
            });
        });

        // FAQ items
        container.querySelectorAll('.be-faq-q, .be-faq-a').forEach(function (el) {
            el.addEventListener('input', function () {
                var idx = parseInt(el.dataset.index);
                var itemIdx = parseInt(el.dataset.item);
                var isQ = el.classList.contains('be-faq-q');
                if (!blocks[idx].items[itemIdx]) blocks[idx].items[itemIdx] = {};
                blocks[idx].items[itemIdx][isQ ? 'question' : 'answer'] = el.value;
                syncHiddenInput();
            });
        });

        // Add FAQ item
        container.querySelectorAll('.be-faq-add').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var idx = parseInt(btn.dataset.index);
                blocks[idx].items.push({question: 'Nova pergunta?', answer: 'Resposta.'});
                renderBlocks();
                syncHiddenInput();
            });
        });

        // Remove FAQ item
        container.querySelectorAll('.be-faq-remove').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var idx = parseInt(btn.dataset.index);
                var itemIdx = parseInt(btn.dataset.item);
                blocks[idx].items.splice(itemIdx, 1);
                renderBlocks();
                syncHiddenInput();
            });
        });

        // Image gallery button
        container.querySelectorAll('.be-image-gallery').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var idx = parseInt(btn.dataset.index);
                openMediaModal(function (url) {
                    blocks[idx].src = url;
                    var input = editor.querySelector('.be-image-src[data-index="' + idx + '"]');
                    if (input) input.value = url;
                    syncHiddenInput();
                });
            });
        });
    }

    function openMediaModal(callback) {
        var overlay = document.getElementById('media-modal-overlay');
        var grid = document.getElementById('media-modal-grid');
        if (!overlay || !grid) {
            // Fallback: simple prompt
            var url = prompt('URL da imagem:');
            if (url) callback(url);
            return;
        }

        overlay.style.display = 'flex';
        grid.innerHTML = '<p>Carregando...</p>';

        fetch('/admin/media/json')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.success || !data.items || data.items.length === 0) {
                    grid.innerHTML = '<p>Nenhuma imagem na galeria.</p>';
                    return;
                }
                var html = '';
                data.items.forEach(function (item) {
                    html += '<div class="media-modal-item" data-url="' + escapeHtml(item.path) + '">';
                    html += '<img src="' + escapeHtml(item.path) + '" alt="" loading="lazy">';
                    html += '<div class="media-modal-select">✓</div>';
                    html += '</div>';
                });
                grid.innerHTML = html;

                var selectedUrl = null;
                grid.querySelectorAll('.media-modal-item').forEach(function (item) {
                    item.addEventListener('click', function () {
                        grid.querySelectorAll('.media-modal-item').forEach(function (i) { i.classList.remove('selected'); });
                        item.classList.add('selected');
                        selectedUrl = item.dataset.url;
                    });
                });

                document.getElementById('media-modal-confirm').onclick = function () {
                    overlay.style.display = 'none';
                    if (selectedUrl) callback(selectedUrl);
                };
            })
            .catch(function () {
                grid.innerHTML = '<p>Erro ao carregar galeria.</p>';
            });

        document.getElementById('media-modal-cancel').onclick = function () {
            overlay.style.display = 'none';
        };
        document.getElementById('media-modal-close').onclick = function () {
            overlay.style.display = 'none';
        };
        overlay.onclick = function (e) {
            if (e.target === overlay) overlay.style.display = 'none';
        };
    }

    function renderBlockItem(block, i) {
        var html = '<div class="be-block" data-index="' + i + '">';
        html += '<div class="be-block-header">';
        html += '<span class="be-block-type">' + blockLabel(block.type) + '</span>';
        html += '<div class="be-block-actions">';
        html += '<button type="button" class="be-block-up" data-index="' + i + '" title="Mover para cima">↑</button>';
        html += '<button type="button" class="be-block-down" data-index="' + i + '" title="Mover para baixo">↓</button>';
        html += '<button type="button" class="be-block-remove" data-index="' + i + '" title="Remover">×</button>';
        html += '</div></div>';
        html += '<div class="be-block-body">';

        switch (block.type) {
            case 'text':
                html += '<div class="be-text-content" contenteditable="true" data-index="' + i + '">' + (block.content || '<p>Digite o texto aqui...</p>') + '</div>';
                html += '<div class="be-text-tools">';
                html += '<button type="button" onclick="document.execCommand(\'bold\')"><b>B</b></button>';
                html += '<button type="button" onclick="document.execCommand(\'italic\')"><i>I</i></button>';
                html += '<button type="button" onclick="document.execCommand(\'insertUnorderedList\')">Lista</button>';
                html += '<button type="button" onclick="document.execCommand(\'formatBlock\', false, \'H2\')">H2</button>';
                html += '<button type="button" onclick="document.execCommand(\'formatBlock\', false, \'H3\')">H3</button>';
                html += '</div>';
                break;
            case 'image':
                html += '<div class="be-image-row">';
                html += '<input type="text" class="be-input be-image-src" data-index="' + i + '" data-prop="src" value="' + escapeHtml(block.src || '') + '" placeholder="URL da imagem">';
                html += '<button type="button" class="be-btn be-image-gallery" data-index="' + i + '">📁 Galeria</button>';
                html += '</div>';
                html += '<input type="text" class="be-input" data-index="' + i + '" data-prop="alt" value="' + escapeHtml(block.alt || '') + '" placeholder="Descrição da imagem (alt)">';
                html += '<input type="text" class="be-input" data-index="' + i + '" data-prop="caption" value="' + escapeHtml(block.caption || '') + '" placeholder="Legenda">';
                html += '<select class="be-input" data-index="' + i + '" data-prop="align">';
                html += '<option value="left"' + (block.align === 'left' ? ' selected' : '') + '>Esquerda</option>';
                html += '<option value="center"' + (block.align === 'center' ? ' selected' : '') + '>Centro</option>';
                html += '<option value="right"' + (block.align === 'right' ? ' selected' : '') + '>Direita</option>';
                html += '</select>';
                break;
            case 'gallery':
                html += '<input type="text" class="be-input" data-index="' + i + '" data-prop="media_ids" value="' + escapeHtml((block.media_ids || []).join(',')) + '" placeholder="IDs das imagens separados por vírgula">';
                html += '<input type="number" class="be-input" data-index="' + i + '" data-prop="columns" value="' + (block.columns || 3) + '" placeholder="Colunas (1-6)">';
                break;
            case 'video':
                html += '<input type="text" class="be-input" data-index="' + i + '" data-prop="url" value="' + escapeHtml(block.url || '') + '" placeholder="URL do YouTube ou Vimeo">';
                break;
            case 'map':
                html += '<input type="number" class="be-input" data-index="' + i + '" data-prop="zoom" value="' + (block.zoom || 15) + '" placeholder="Zoom (1-20)">';
                html += '<p class="help-text">O mapa usa o endereço cadastrado na aba "Endereço".</p>';
                break;
            case 'cta':
                html += '<input type="text" class="be-input" data-index="' + i + '" data-prop="text" value="' + escapeHtml(block.text || '') + '" placeholder="Texto do botão">';
                html += '<input type="text" class="be-input" data-index="' + i + '" data-prop="url" value="' + escapeHtml(block.url || '') + '" placeholder="Link">';
                html += '<select class="be-input" data-index="' + i + '" data-prop="style">';
                html += '<option value="primary"' + (block.style === 'primary' ? ' selected' : '') + '>Primário</option>';
                html += '<option value="secondary"' + (block.style === 'secondary' ? ' selected' : '') + '>Secundário</option>';
                html += '<option value="outline"' + (block.style === 'outline' ? ' selected' : '') + '>Contorno</option>';
                html += '</select>';
                break;
            case 'faq':
                html += '<div class="be-faq-list">';
                (block.items || []).forEach(function (item, j) {
                    html += '<div class="be-faq-item">';
                    html += '<input type="text" class="be-input be-faq-q" data-index="' + i + '" data-item="' + j + '" value="' + escapeHtml(item.question || '') + '" placeholder="Pergunta">';
                    html += '<textarea class="be-input be-faq-a" data-index="' + i + '" data-item="' + j + '" rows="2" placeholder="Resposta">' + escapeHtml(item.answer || '') + '</textarea>';
                    html += '<button type="button" class="be-faq-remove" data-index="' + i + '" data-item="' + j + '">Remover</button>';
                    html += '</div>';
                });
                html += '<button type="button" class="be-faq-add" data-index="' + i + '">+ Adicionar Pergunta</button>';
                html += '</div>';
                break;
            case 'spacer':
                html += '<label>Altura (px): <input type="range" class="be-input" data-index="' + i + '" data-prop="height" min="8" max="200" value="' + (block.height || 40) + '"></label>';
                html += '<span class="be-spacer-val">' + (block.height || 40) + 'px</span>';
                break;
        }

        html += '</div></div>';
        return html;
    }

    function blockLabel(type) {
        var labels = {
            text: 'Texto',
            image: 'Imagem',
            gallery: 'Galeria',
            video: 'Vídeo',
            map: 'Mapa',
            cta: 'Botão',
            faq: 'FAQ',
            spacer: 'Espaçador'
        };
        return labels[type] || type;
    }

    function syncHiddenInput() {
        if (hiddenInput) {
            hiddenInput.value = JSON.stringify(blocks);
        }
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    document.addEventListener('DOMContentLoaded', function () {
        var container = document.getElementById('block-editor');
        if (container) initBlockEditor(container);
    });

    window.BlockEditor = { init: initBlockEditor };
})();
