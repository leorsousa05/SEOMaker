# Tasks: 011-ajax-contact-success-modal

Checklist de tarefas para implementar o envio via AJAX e o modal de sucesso.

## 🧭 1. Controller e Respostas JSON
- [ ] Editar `src/Public/SiteController.php`:
  - Adicionar a detecção de requisição AJAX/JSON.
  - Responder JSON com `{success: false, errors: ...}` nos casos de erro de validação ou bloqueio de rate-limit.
  - Responder JSON com `{success: true, message: ...}` após o envio de e-mail bem-sucedido.

## 📦 2. Markup do Modal e Script JS
- [ ] Editar `templates/public/home.php`:
  - Adicionar `id="contact-form"` ao elemento `<form>`.
  - Inserir o markup HTML do modal de sucesso animado no rodapé da página.
  - Implementar o script JavaScript para capturar o envio, gerenciar estados de loading no botão, abrir/fechar o modal, tratar erros inline e verificar o fallback de query param.

## 🧪 3. Verificação
- [ ] Rodar testes locais com `php tests/run.php` para atestar a estabilidade.
