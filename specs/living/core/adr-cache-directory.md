# ADR: Diretório de Cache para Sitemap e Robots

## Contexto
Os endpoints `/sitemap.xml` e `/robots.txt` são requisitados com frequência por crawlers. Gerar o conteúdo dinamicamente a cada hit consulta o SQLite e aumenta a carga do servidor.

## Decisão
Cachear o conteúdo gerado em arquivos dentro de `config/cache/`, fora do document root (`public/`).

## Motivação
- **Performance:** reduz consultas repetidas ao banco para recursos estáticos por natureza.
- **Segurança:** manter o cache fora de `public/` evita acesso direto via web e possíveis vazamentos.
- **Simplicidade:** não adiciona dependências (Redis/Memcached) e usa o filesystem já disponível.

## Consequências
- É necessário garantir permissão de escrita em `config/cache/`.
- Falhas de escrita são tratadas com fallback para geração dinâmica e log silencioso.
- O cache é invalidado event-driven nas mutações relevantes (páginas, configurações, redirects).
