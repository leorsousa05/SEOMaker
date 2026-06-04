(function () {
    'use strict';

    var fieldDefs = {};

    function initSchemaEditor(container) {
        var typeSelect = container.querySelector('#schema_type');
        var fieldsContainer = container.querySelector('#schema-fields-container');
        var hiddenInput = container.querySelector('#schema_data');
        var form = container.closest('form');

        if (!typeSelect || !fieldsContainer || !hiddenInput || !form) return;

        // Parse initial values from data attribute
        var initialValues = {};
        try {
            initialValues = JSON.parse(fieldsContainer.dataset.initial || '{}');
        } catch (e) {}

        function renderFields(type) {
            var defs = window.schemaFieldDefs ? window.schemaFieldDefs[type] : [];
            if (!defs || !defs.length) {
                fieldsContainer.innerHTML = '<p class="help-text">Selecione um tipo de schema para ver os campos disponíveis.</p>';
                return;
            }

            var html = '<div class="schema-fields">';
            defs.forEach(function (field) {
                var val = initialValues[field.name] || '';
                var placeholder = field.placeholder || '';
                html += '<div class="form-group">';
                html += '<label for="' + field.name + '">' + escapeHtml(field.label) + '</label>';
                if (field.type === 'textarea') {
                    html += '<textarea id="' + field.name + '" name="' + field.name + '" rows="3" placeholder="' + escapeHtml(placeholder) + '">' + escapeHtml(val) + '</textarea>';
                } else {
                    html += '<input type="' + field.type + '" id="' + field.name + '" name="' + field.name + '" value="' + escapeHtml(val) + '" placeholder="' + escapeHtml(placeholder) + '">';
                }
                html += '</div>';
            });
            html += '</div>';
            fieldsContainer.innerHTML = html;
        }

        typeSelect.addEventListener('change', function () {
            renderFields(typeSelect.value);
        });

        form.addEventListener('submit', function (e) {
            var type = typeSelect.value;
            var defs = window.schemaFieldDefs ? window.schemaFieldDefs[type] : [];
            if (!defs || !defs.length) return;

            var jsonData = {'@context': 'https://schema.org', '@type': type};
            defs.forEach(function (field) {
                var input = form.querySelector('[name="' + field.name + '"]');
                if (!input || !input.value) return;
                setNestedValue(jsonData, field.key, input.value);
            });

            hiddenInput.value = JSON.stringify(jsonData, null, 2);
        });

        // Initial render
        renderFields(typeSelect.value);
    }

    function setNestedValue(obj, path, value) {
        var keys = path.split('.');
        var current = obj;
        for (var i = 0; i < keys.length; i++) {
            var key = keys[i];
            if (i === keys.length - 1) {
                current[key] = value;
            } else {
                if (!current[key] || typeof current[key] !== 'object') {
                    current[key] = {};
                }
                current = current[key];
            }
        }
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    document.addEventListener('DOMContentLoaded', function () {
        var container = document.querySelector('[data-schema-editor]');
        if (container) initSchemaEditor(container);
    });

    window.SchemaEditor = { init: initSchemaEditor };
})();
