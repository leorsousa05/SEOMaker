# Proposal: Tela de Configuração com Abas

## WHY
A tela atual de configurações exibe todos os campos em uma única coluna, sem organização visual. Com o crescimento das opções (SEO, contato, email, redes sociais, analytics), a UX fica confusa. Abas agrupam settings por categoria e melhoram a navegabilidade.

## Scope
- Refatorar `/admin/settings` para usar abas (tabs) no frontend
- Agrupar settings em categorias: Geral, SEO, Contato, Email, Redes Sociais
- Manter backend intacto (mesmo endpoint, mesma lógica de salvamento)
- Design responsivo e acessível

## Constraints
- HTML/CSS/JS vanilla (sem frameworks frontend)
- Sem quebrar compatibilidade com settings existentes
- PHP 8.1+ puro
