<?php
/** @var App\Models\Page $page */
/** @var array<int, array<string, string>> $features */
?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="display hero-title" data-stagger>
                Um CMS em PHP + SQLite pronto para usar e customizar
            </h1>
            <p class="lead hero-subtitle" data-stagger>
                Sem dependências pesadas, sem frameworks mágicos. Código limpo em PHP 8, SQLite, templates PHP e CSS vanilla. Ideal para quem quer entregar rápido e manter o controle.
            </p>
            <div class="hero-actions" data-stagger>
                <a href="/admin" class="btn btn-primary btn-lg">Ver o código em ação</a>
                <a href="#recursos" class="btn btn-secondary btn-lg">Documentação</a>
            </div>
        </div>
        <div class="hero-visual" data-stagger>
            <div class="admin-mockup-3d">
                <div class="mockup-base">
                    <div class="mockup-sidebar">
                        <div class="mockup-logo"></div>
                        <div class="mockup-nav-item"></div>
                        <div class="mockup-nav-item"></div>
                        <div class="mockup-nav-item"></div>
                        <div class="mockup-nav-item"></div>
                    </div>
                    <div class="mockup-main">
                        <div class="mockup-header"></div>
                        <div class="mockup-content">
                            <div class="mockup-row">
                                <div class="mockup-row-dot"></div>
                                <div class="mockup-row-bar"></div>
                            </div>
                            <div class="mockup-row">
                                <div class="mockup-row-dot"></div>
                                <div class="mockup-row-bar short"></div>
                            </div>
                            <div class="mockup-row">
                                <div class="mockup-row-dot"></div>
                                <div class="mockup-row-bar"></div>
                            </div>
                            <div class="mockup-row">
                                <div class="mockup-row-dot"></div>
                                <div class="mockup-row-bar short"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mockup-float-card mockup-float-card--schema">Schema.org</div>
                <div class="mockup-float-card mockup-float-card--sitemap">sitemap.xml</div>
                <div class="mockup-float-card mockup-float-card--meta">Meta tags</div>
            </div>
        </div>
    </div>
</section>

<section class="section features" id="recursos">
    <div class="container">
        <div class="section-header" data-reveal>
            <span class="section-label">Stack</span>
            <h2 class="title-1 section-title">Feito para desenvolvedores</h2>
            <p class="lead">Tudo que você precisa para rodar, customizar e entregar um site com admin e SEO sem complicar.</p>
        </div>

        <div class="features-grid">
            <?php foreach ($features as $index => $feature): ?>
            <div class="feature-card" data-reveal style="transition-delay: <?= $index * 100 ?>ms">
                <div class="feature-icon">
                    <?= $feature['icon'] ?>
                </div>
                <h3 class="title-2"><?= htmlspecialchars($feature['title']) ?></h3>
                <p><?= htmlspecialchars($feature['desc']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section showcase-section" id="editor">
    <div class="container">
        <div class="showcase-content" data-reveal>
            <span class="section-label">Editor</span>
            <h2 class="title-1 section-title">Crie páginas com blocos, sem sair do admin</h2>
            <p class="lead">Editor visual com blocos de texto, chamadas, FAQ, vídeo e espaçadores. O conteúdo é salvo em JSON, renderizado em PHP e otimizado para SEO.</p>

            <ul class="showcase-list">
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Blocos prontos: texto, CTA, FAQ, vídeo e espaçador</span>
                </li>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Ordenamento simples e sanitização de HTML</span>
                </li>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Preview imediato no front-end</span>
                </li>
            </ul>
        </div>

        <div class="showcase-visual" data-reveal>
            <div class="admin-mockup-3d admin-mockup-3d--editor">
                <div class="mockup-base mockup-base--light mockup-base--stacked">
                    <div class="mockup-panel-header">
                        <span>Editor de Página</span>
                    </div>
                    <div class="mockup-form">
                        <div class="mockup-field">
                            <div class="mockup-label">Título da página</div>
                            <div class="mockup-input">Sobre nós</div>
                        </div>
                        <div class="mockup-field">
                            <div class="mockup-label">Slug</div>
                            <div class="mockup-input">/sobre-nos</div>
                        </div>
                        <div class="mockup-blocks">
                            <div class="mockup-block mockup-block--text">
                                <div class="mockup-block-type">Texto</div>
                                <div class="mockup-block-lines">
                                    <div></div>
                                    <div></div>
                                    <div class="short"></div>
                                </div>
                            </div>
                            <div class="mockup-block mockup-block--cta">
                                <div class="mockup-block-type">CTA</div>
                                <div class="mockup-block-btn">Botão principal</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mockup-float-card mockup-float-card--json">Blocos JSON</div>
                <div class="mockup-float-card mockup-float-card--sanitize">Sanitize HTML</div>
                <div class="mockup-float-card mockup-float-card--reorder">Reorder</div>
            </div>
        </div>
    </div>
</section>

<section class="section showcase-section showcase-section--reverse showcase-section--dark" id="seo">
    <div class="container">
        <div class="showcase-visual" data-reveal>
            <div class="admin-mockup-3d admin-mockup-3d--seo">
                <div class="mockup-base mockup-base--stacked">
                    <div class="mockup-panel-header">
                        <span>SEO da Página</span>
                    </div>
                    <div class="mockup-form">
                        <div class="mockup-field">
                            <div class="mockup-label">Meta title</div>
                            <div class="mockup-input">Sobre nós — Test Site</div>
                        </div>
                        <div class="mockup-field">
                            <div class="mockup-label">Meta description</div>
                            <div class="mockup-textarea">
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                        <div class="mockup-schema">
                            <div class="mockup-label">Schema.org: LocalBusiness</div>
                            <div class="mockup-schema-grid">
                                <div class="mockup-schema-item"><span>@type</span><span>LocalBusiness</span></div>
                                <div class="mockup-schema-item"><span>name</span><span>Test Site</span></div>
                                <div class="mockup-schema-item"><span>telephone</span><span>+55...</span></div>
                                <div class="mockup-schema-item"><span>address</span><span>PostalAddress</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mockup-float-card mockup-float-card--metatags">Meta tags</div>
                <div class="mockup-float-card mockup-float-card--schemaorg">Schema.org</div>
                <div class="mockup-float-card mockup-float-card--sitemapfile">sitemap.xml</div>
            </div>
        </div>

        <div class="showcase-content" data-reveal>
            <span class="section-label">SEO</span>
            <h2 class="title-1 section-title">Schema.org, sitemap e meta tags no mesmo lugar</h2>
            <p class="lead">Configure títulos, descrições, Open Graph, Twitter Cards e dados estruturados sem tocar no código. O CMS gera sitemap.xml e robots.txt automaticamente.</p>

            <ul class="showcase-list">
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Meta tags por página</span>
                </li>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Schema.org: LocalBusiness, Organization, Product, FAQ</span>
                </li>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Sitemap.xml e robots.txt dinâmicos</span>
                </li>
            </ul>
        </div>
    </div>
</section>

<section class="section cta-section">
    <div class="container">
        <div class="section-header" data-reveal>
            <h2 class="title-1 section-title">Quer ver como funciona por dentro?</h2>
            <p class="lead">Acesse o painel administrativo com o usuário demo e explore o código, a estrutura e as funcionalidades.</p>
            <a href="/admin" class="btn btn-white btn-lg">Abrir demonstração</a>
        </div>
    </div>
</section>

<section class="section contact" id="contato">
    <div class="container">
        <div class="section-header" data-reveal>
            <span class="section-label">Contato</span>
            <h2 class="title-1 section-title">Dúvidas ou sugestões?</h2>
            <p class="lead">Mande uma mensagem. Se for sobre código, melhor ainda.</p>
        </div>

        <div class="contact-form" data-reveal>
            <?php if (!empty($_SESSION['contact_success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['contact_success']) ?></div>
                <?php unset($_SESSION['contact_success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['contact_errors'])): ?>
                <div class="alert alert-danger">
                    <?php foreach ($_SESSION['contact_errors'] as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['contact_errors']); ?>
            <?php endif; ?>

            <form action="/contact" method="POST" class="form">
                <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_SESSION['contact_data']['name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_SESSION['contact_data']['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Telefone (opcional)</label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($_SESSION['contact_data']['phone'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="message">Mensagem</label>
                    <textarea id="message" name="message" rows="5" required><?= htmlspecialchars($_SESSION['contact_data']['message'] ?? '') ?></textarea>
                </div>
                <?php if (isset($_SESSION['contact_data'])) unset($_SESSION['contact_data']); ?>
                <button type="submit" class="btn btn-primary btn-lg">Enviar mensagem</button>
            </form>
        </div>
    </div>
</section>
