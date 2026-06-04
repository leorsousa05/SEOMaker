# Spec: Admin Settings Tabs

## MODIFIED

### templates/admin/settings.php
- Adicionar navegação de tabs no topo
- Dividir form em `.tabs-panel` por categoria
- Cada panel contém os campos do grupo
- Guardar tab ativa no URL hash (`#seo`, `#contato`) para persistência

### templates/admin/layout.php
- Adicionar script JS global para tabs (ou inline no settings)

### src/Admin/SettingsController.php
- Definir array `$groups` com mapeamento tab -> keys
- Passar `$groups` e `$activeTab` para a view
- `$activeTab` vindo do POST `active_tab` hidden field (fallback 'geral')

### public/assets/admin.css
- Estilos para `.tabs-nav`, `.tabs-nav-item`, `.tabs-panel`
- Estado ativo com border-bottom ou background
- Mobile: `overflow-x: auto` na nav

## ADDED

### public/assets/tabs.js
```js
function initTabs(container) {
  const nav = container.querySelector('.tabs-nav');
  const panels = container.querySelectorAll('.tabs-panel');
  nav.addEventListener('click', (e) => {
    if (!e.target.dataset.tab) return;
    const tab = e.target.dataset.tab;
    panels.forEach(p => p.classList.toggle('active', p.dataset.tab === tab));
    nav.querySelectorAll('.tabs-nav-item').forEach(b => {
      b.classList.toggle('active', b.dataset.tab === tab);
    });
    history.replaceState(null, '', '#' + tab);
    container.querySelector('input[name="active_tab"]').value = tab;
  });
  // Activate from hash
  const hash = location.hash.slice(1);
  if (hash) nav.querySelector(`[data-tab="${hash}"]`)?.click();
}
```

## Tests
- Tabs.js: clique em tab muda panel ativo
- Tabs.js: hash URL ativa tab correta no load
- SettingsController: `$activeTab` default é 'geral'
- SettingsController: `$activeTab` vindo de POST hidden field
