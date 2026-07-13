(function () {
    'use strict';

    var editor, blocks = [], hiddenInput, mediaCache = {};

    function initBlockEditor(container) {
        editor = container;
        hiddenInput = document.getElementById('content_blocks');
        
        var data = editor.dataset.blocks || '[]';
        try {
            blocks = JSON.parse(data);
        } catch (e) {
            blocks = [{type: 'text', content: '<p>Comece a editar...</p>'}];
        }

        // Fetch media list to populate mediaCache for previews
        fetch('/admin/media/json?perPage=100')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success && data.items) {
                    data.items.forEach(function (item) {
                        mediaCache[item.id] = item.path;
                    });
                }
                renderBlocks();
            })
            .catch(function () {
                // Ignore error, fallback will handle it
            });

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
                    blocks[idx].media_id = 0; // reset media_id since we are assigning src url
                    renderBlocks();
                    syncHiddenInput();
                }, false);
            });
        });

        // Image source input change listener
        container.querySelectorAll('.be-image-src').forEach(function (input) {
            input.addEventListener('change', function () {
                renderBlocks();
            });
        });

        // Gallery choose button
        container.querySelectorAll('.be-gallery-choose').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var idx = parseInt(btn.dataset.index);
                openMediaModal(function (items) {
                    var ids = [];
                    var mediaItems = [];
                    items.forEach(function (item) {
                        ids.push(item.id);
                        mediaItems.push(item);
                        mediaCache[item.id] = item.path;
                    });
                    blocks[idx].media_ids = ids;
                    blocks[idx].media_items = mediaItems;
                    renderBlocks();
                    syncHiddenInput();
                }, true);
            });
        });

        // Remove image from gallery block
        container.querySelectorAll('.be-gallery-remove-img').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var idx = parseInt(btn.dataset.index);
                var idToRemove = parseInt(btn.dataset.id);
                if (blocks[idx] && blocks[idx].media_ids) {
                    blocks[idx].media_ids = blocks[idx].media_ids.filter(function (id) {
                        return id !== idToRemove;
                    });
                    if (blocks[idx].media_items) {
                        blocks[idx].media_items = blocks[idx].media_items.filter(function (item) {
                            return item.id !== idToRemove;
                        });
                    }
                    renderBlocks();
                    syncHiddenInput();
                }
            });
        });

        // Checkbox inputs (Lazy Loading)
        container.querySelectorAll('.be-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                var idx = parseInt(checkbox.dataset.index);
                var prop = checkbox.dataset.prop;
                blocks[idx][prop] = checkbox.checked;
                syncHiddenInput();
            });
        });
    }

    function openMediaModal(callback, allowMultiple) {
        var overlay = document.getElementById('media-modal-overlay');
        var grid = document.getElementById('media-modal-grid');
        if (!overlay || !grid) {
            // Fallback: simple prompt
            var url = prompt('URL da imagem:');
            if (url) {
                if (allowMultiple) {
                    callback([{ id: 0, path: url }]);
                } else {
                    callback(url);
                }
            }
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
                    html += '<div class="media-modal-item" data-id="' + item.id + '" data-url="' + escapeHtml(item.path) + '">';
                    html += '<img src="' + escapeHtml(item.path) + '" alt="" loading="lazy">';
                    html += '<div class="media-modal-select">✓</div>';
                    html += '</div>';
                });
                grid.innerHTML = html;

                grid.querySelectorAll('.media-modal-item').forEach(function (item) {
                    item.addEventListener('click', function () {
                        if (allowMultiple) {
                            item.classList.toggle('selected');
                        } else {
                            grid.querySelectorAll('.media-modal-item').forEach(function (i) { i.classList.remove('selected'); });
                            item.classList.add('selected');
                        }
                    });
                });

                document.getElementById('media-modal-confirm').onclick = function () {
                    overlay.style.display = 'none';
                    if (allowMultiple) {
                        var selectedItems = [];
                        grid.querySelectorAll('.media-modal-item.selected').forEach(function (el) {
                            selectedItems.push({
                                id: parseInt(el.dataset.id),
                                path: el.dataset.url
                            });
                        });
                        callback(selectedItems);
                    } else {
                        var selectedEl = grid.querySelector('.media-modal-item.selected');
                        if (selectedEl) {
                            callback([{ id: parseInt(selectedEl.dataset.id), path: selectedEl.dataset.url }]);
                        }
                    }
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
                var imgSrc = block.src || '';
                if (!imgSrc && block.media_id && mediaCache[block.media_id]) {
                    imgSrc = mediaCache[block.media_id];
                }
                if (imgSrc) {
                    html += '<div class="be-image-preview" style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px; border: 1px solid #ccc; padding: 5px; border-radius: 4px;">';
                    html += '<img src="' + escapeHtml(imgSrc) + '" style="max-height: 80px; max-width: 80px; object-fit: cover; border-radius: 4px;">';
                    html += '<div style="word-break: break-all;">';
                    html += '<div style="font-weight: bold; font-size: 0.85rem;">' + escapeHtml(imgSrc.split('/').pop()) + '</div>';
                    html += '<div style="font-size: 0.75rem; color: #666;">' + escapeHtml(imgSrc) + '</div>';
                    html += '</div>';
                    html += '</div>';
                }
                html += '<div class="be-image-row">';
                html += '<input type="text" class="be-input be-image-src" data-index="' + i + '" data-prop="src" value="' + escapeHtml(imgSrc) + '" placeholder="URL da imagem">';
                html += '<button type="button" class="be-btn be-image-gallery" data-index="' + i + '">📁 Galeria</button>';
                html += '</div>';
                html += '<input type="text" class="be-input" data-index="' + i + '" data-prop="alt" value="' + escapeHtml(block.alt || '') + '" placeholder="Descrição da imagem (alt)">';
                html += '<input type="text" class="be-input" data-index="' + i + '" data-prop="caption" value="' + escapeHtml(block.caption || '') + '" placeholder="Legenda">';
                html += '<select class="be-input" data-index="' + i + '" data-prop="align">';
                html += '<option value="left"' + (block.align === 'left' ? ' selected' : '') + '>Esquerda</option>';
                html += '<option value="center"' + (block.align === 'center' ? ' selected' : '') + '>Centro</option>';
                html += '<option value="right"' + (block.align === 'right' ? ' selected' : '') + '>Direita</option>';
                html += '</select>';
                var imageLazyChecked = (block.lazy !== false) ? ' checked' : '';
                html += '<div style="margin-top: 5px;"><label><input type="checkbox" class="be-checkbox" data-index="' + i + '" data-prop="lazy"' + imageLazyChecked + '> Lazy Loading</label></div>';
                break;
            case 'gallery':
                html += '<div class="be-gallery-preview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 10px; margin-bottom: 10px;">';
                var ids = block.media_ids || [];
                ids.forEach(function (id) {
                    var path = '';
                    if (mediaCache[id]) {
                        path = mediaCache[id];
                    } else if (block.media_items) {
                        var found = block.media_items.find(function(item) { return item.id == id; });
                        if (found) path = found.path;
                    }
                    
                    if (path) {
                        html += '<div class="be-gallery-preview-item" style="position: relative; width: 80px; height: 80px; border: 1px solid #ccc; border-radius: 4px; overflow: hidden;">';
                        html += '<img src="' + escapeHtml(path) + '" style="width: 100%; height: 100%; object-fit: cover;">';
                        html += '<button type="button" class="be-gallery-remove-img" data-index="' + i + '" data-id="' + id + '" style="position: absolute; top: 2px; right: 2px; background: rgba(0,0,0,0.6); color: white; border: none; border-radius: 50%; width: 18px; height: 18px; line-height: 14px; font-size: 12px; cursor: pointer; text-align: center; padding: 0;">&times;</button>';
                        html += '</div>';
                    } else {
                        html += '<div class="be-gallery-preview-item" style="position: relative; width: 80px; height: 80px; border: 1px dashed #ccc; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; color: #999;">';
                        html += 'ID: ' + id;
                        html += '<button type="button" class="be-gallery-remove-img" data-index="' + i + '" data-id="' + id + '" style="position: absolute; top: 2px; right: 2px; background: rgba(0,0,0,0.6); color: white; border: none; border-radius: 50%; width: 18px; height: 18px; line-height: 14px; font-size: 12px; cursor: pointer; text-align: center; padding: 0;">&times;</button>';
                        html += '</div>';
                    }
                });
                html += '</div>';
                
                html += '<button type="button" class="be-btn be-gallery-choose" data-index="' + i + '" style="margin-bottom: 10px;">📁 Escolher Imagens</button>';
                html += '<div style="margin-bottom: 5px;">';
                html += '<label>Colunas: <input type="number" class="be-input be-gallery-columns" data-index="' + i + '" data-prop="columns" value="' + (block.columns || 3) + '" min="1" max="6" style="width: 80px; display: inline-block; margin-left: 5px;"></label>';
                html += '</div>';
                var galleryLazyChecked = (block.lazy !== false) ? ' checked' : '';
                html += '<div><label><input type="checkbox" class="be-checkbox" data-index="' + i + '" data-prop="lazy"' + galleryLazyChecked + '> Lazy Loading</label></div>';
                break;
            case 'video':
                html += '<input type="text" class="be-input" data-index="' + i + '" data-prop="url" value="' + escapeHtml(block.url || '') + '" placeholder="URL do YouTube ou Vimeo">';
                var videoLazyChecked = (block.lazy !== false) ? ' checked' : '';
                html += '<div style="margin-top: 5px;"><label><input type="checkbox" class="be-checkbox" data-index="' + i + '" data-prop="lazy"' + videoLazyChecked + '> Lazy Loading</label></div>';
                break;
            case 'map':
                html += '<input type="number" class="be-input" data-index="' + i + '" data-prop="zoom" value="' + (block.zoom || 15) + '" placeholder="Zoom (1-20)">';
                html += '<p class="help-text">O mapa usa o endereço cadastrado na aba "Endereço".</p>';
                var mapLazyChecked = (block.lazy !== false) ? ' checked' : '';
                html += '<div style="margin-top: 5px;"><label><input type="checkbox" class="be-checkbox" data-index="' + i + '" data-prop="lazy"' + mapLazyChecked + '> Lazy Loading</label></div>';
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
    window.openMediaModal = openMediaModal;
})();
