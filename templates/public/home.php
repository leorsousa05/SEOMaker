<?php
/** @var App\Models\Page $page */
/** @var array<int, array<string, string>> $features */

$testimonials = [
    [
        'quote' => 'A migração para o SEOMaker aumentou nosso tráfego orgânico em 140% no primeiro trimestre. A velocidade de carregamento é incomparável.',
        'author' => 'Guilherme Silva',
        'role' => 'Diretor de Crescimento, TechSoft',
        'initials' => 'GS',
        'stars' => 5
    ],
    [
        'quote' => 'Criar landing pages com o editor de blocos é extremamente simples. O SEO técnico é feito em segundo plano automaticamente.',
        'author' => 'Juliana Costa',
        'role' => 'Fundadora, Studio Criativo',
        'initials' => 'JC',
        'stars' => 5
    ],
    [
        'quote' => 'Uma obra-prima em termos de performance. O fato de não ter dependências pesadas faz com que a nota no Lighthouse seja sempre 100/100.',
        'author' => 'Marcos Ramos',
        'role' => 'CTO, DevAgency',
        'initials' => 'MR',
        'stars' => 5
    ],
    [
        'quote' => 'Os esquemas estruturados JSON-LD e o sitemap automático pouparam semanas de desenvolvimento de SEO técnico.',
        'author' => 'Beatriz Santos',
        'role' => 'Especialista em SEO, GrowthLab',
        'initials' => 'BS',
        'stars' => 5
    ],
    [
        'quote' => 'Excelente suporte para múltiplos redirects e gerenciamento de mídias direto pelo painel. Não precisamos de mais nada.',
        'author' => 'Rodrigo Lima',
        'role' => 'Gerente de Produto, E-Commerce Pro',
        'initials' => 'RL',
        'stars' => 5
    ],
    [
        'quote' => 'O painel é limpo, direto ao ponto e não assusta os clientes que precisam editar seus próprios textos e blogs.',
        'author' => 'Fernanda Rocha',
        'role' => 'Agente Digital, PixelMedia',
        'initials' => 'FR',
        'stars' => 5
    ]
];

$faqs = [
    [
        'q' => 'Como funciona a otimização de SEO automática?',
        'a' => 'O SEOMaker gera automaticamente tags meta canônicas, dados estruturados JSON-LD (como breadcrumbs, organizações e produtos) e monta o arquivo sitemap.xml sempre que uma nova página é publicada, sem necessidade de plug-ins de terceiros.'
    ],
    [
        'q' => 'É necessário conhecimento técnico para gerenciar o conteúdo?',
        'a' => 'Não. O painel administrativo conta com um Editor de Blocos intuitivo (Drag and Drop / Visual), onde você pode adicionar textos, imagens, galerias, vídeos e mapas clicando e preenchendo formulários simples.'
    ],
    [
        'q' => 'O template suporta Dark Mode nativo?',
        'a' => 'Sim. O design foi construído utilizando Tailwind CSS v4 com a diretiva dark:, respondendo instantaneamente às preferências visuais do sistema operacional do seu visitante.'
    ],
    [
        'q' => 'Como posso hospedar meu projeto?',
        'a' => 'Qualquer hospedagem simples que suporte PHP 8.0+ e SQLite é suficiente. O banco de dados é um único arquivo contido na pasta do projeto, dispensando a configuração complexa de servidores MySQL.'
    ]
];

$pricing = [
    [
        'name' => 'Starter',
        'price' => 'Grátis',
        'period' => 'para sempre',
        'desc' => 'Perfeito para projetos pessoais e validações rápidas de portfólios.',
        'features' => ['Até 5 páginas ativas', 'Editor de Blocos visual', 'SEO On-Page básico', 'Banco de Dados SQLite incluso', 'Suporte da comunidade'],
        'highlight' => false,
        'cta' => 'Começar Agora'
    ],
    [
        'name' => 'Professional',
        'price' => 'R$ 49',
        'period' => 'por mês',
        'desc' => 'O plano ideal para criadores profissionais e negócios locais em expansão.',
        'features' => ['Páginas ilimitadas', 'Editor de Blocos avançado', 'JSON-LD Schemas customizados', 'Gerenciador de Redirecionamentos', 'Upload de Mídias de até 5MB', 'Suporte prioritário por email'],
        'highlight' => true,
        'cta' => 'Assinar Profissional'
    ],
    [
        'name' => 'Enterprise',
        'price' => 'Sob Consulta',
        'period' => 'contato comercial',
        'desc' => 'Para agências que necessitam de integrações e customização sob medida.',
        'features' => ['Tudo do plano Profissional', 'Infraestrutura dedicada / Docker', 'Treinamento de equipe', 'Relatórios de SEO customizados', 'SLAs de suporte garantidos', 'Acesso direto via API'],
        'highlight' => false,
        'cta' => 'Falar com Especialista'
    ]
];

// Fetch featured products for home section
$featuredProducts = [];
try {
    $featuredProducts = \App\Core\Database::fetchAll(
        'SELECT p.*, m.path AS image_path FROM products p LEFT JOIN media m ON p.image_id = m.id WHERE p.featured = 1 AND p.is_active = 1 ORDER BY p.id DESC LIMIT 12'
    );
} catch (\Throwable $e) {
    // Table might not exist yet during first run
}
?>

<!-- Section 1: Hero Banner & Interactive Browser Mockup -->
<section class="relative overflow-hidden pt-24 pb-32 bg-white dark:bg-zinc-950 transition-colors duration-300">
    <!-- Fluid background glow nodes -->
    <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-[radial-gradient(circle_at_center,rgba(124,58,237,0.1)_0%,transparent_70%)] pointer-events-none blur-3xl"></div>
    <div class="absolute bottom-10 right-1/4 w-[600px] h-[600px] bg-[radial-gradient(circle_at_center,rgba(59,130,246,0.08)_0%,transparent_70%)] pointer-events-none blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
        <!-- Badge -->
        <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-semibold bg-violet-50 dark:bg-violet-950/50 text-violet-600 dark:text-violet-400 border border-violet-100 dark:border-violet-900/30 mb-8 animate-fade-in">
            ⚡ Lançamento Oficial v1.0
        </span>
        
        <!-- Large Headline -->
        <h1 class="text-5xl md:text-8xl font-black font-title tracking-tight leading-none text-zinc-900 dark:text-white mb-8">
            Crie Landing Pages <br class="hidden md:inline" />
            <span class="bg-gradient-to-r from-violet-600 via-indigo-500 to-blue-500 bg-clip-text text-transparent">Otimizadas para SEO</span>
        </h1>
        
        <!-- Lead text -->
        <p class="text-lg md:text-2xl text-zinc-600 dark:text-zinc-400 max-w-3xl mx-auto mb-12 leading-relaxed">
            SEOMaker é um CMS modular que une a simplicidade de um editor visual de blocos com um mecanismo de indexação ultra veloz. Hospede em qualquer lugar sem configurações complexas de banco de dados.
        </p>
        
        <!-- Actions buttons -->
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mb-20">
            <a href="#contato" class="w-full sm:w-auto bg-violet-600 hover:bg-violet-500 hover:shadow-[0_0_25px_rgba(124,58,237,0.4)] text-white font-bold py-4 px-10 rounded-xl transition-all active:scale-97 text-center">
                Começar Agora Grátis
            </a>
            <a href="#recursos" class="w-full sm:w-auto border border-zinc-200 dark:border-zinc-800 text-zinc-950 dark:text-white hover:bg-zinc-50 dark:hover:bg-zinc-900 font-bold py-4 px-10 rounded-xl transition-all text-center">
                Ver Funcionalidades
            </a>
        </div>

        <!-- Interactive Tech Mockup (Simulated Dashboard Console) -->
        <div class="max-w-5xl mx-auto rounded-2xl border border-zinc-200/60 dark:border-zinc-800/60 bg-white/70 dark:bg-zinc-900/40 backdrop-blur-md shadow-2xl overflow-hidden p-3 md:p-6 transition-all duration-300">
            <!-- Browser Header bar -->
            <div class="flex items-center justify-between border-b border-zinc-200/60 dark:border-zinc-800/60 pb-4 mb-6">
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded-full bg-rose-500/80"></span>
                    <span class="w-3.5 h-3.5 rounded-full bg-amber-500/80"></span>
                    <span class="w-3.5 h-3.5 rounded-full bg-emerald-500/80"></span>
                </div>
                <div class="bg-zinc-100 dark:bg-zinc-950/70 border border-zinc-200/50 dark:border-zinc-800/50 rounded-lg py-1 px-12 md:px-24 text-xs text-zinc-500 dark:text-zinc-400 font-mono tracking-wide">
                    seomaker.studio/admin/dashboard
                </div>
                <div class="w-6"></div>
            </div>
            
            <!-- Dashboard Grid mockup -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                <!-- Stat 1 -->
                <div class="bg-zinc-50 dark:bg-zinc-950/40 border border-zinc-200/40 dark:border-zinc-800/40 rounded-xl p-5">
                    <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Pontuação de SEO</span>
                    <div class="flex items-end justify-between mt-2">
                        <span class="text-3xl font-black font-title text-zinc-900 dark:text-white">100/100</span>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-500/10 py-1 px-2 rounded-md">Lighthouse</span>
                    </div>
                </div>
                <!-- Stat 2 -->
                <div class="bg-zinc-50 dark:bg-zinc-950/40 border border-zinc-200/40 dark:border-zinc-800/40 rounded-xl p-5">
                    <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Tempo de Resposta</span>
                    <div class="flex items-end justify-between mt-2">
                        <span class="text-3xl font-black font-title text-zinc-900 dark:text-white">&lt; 42ms</span>
                        <span class="text-xs font-bold text-blue-600 bg-blue-500/10 py-1 px-2 rounded-md">Fast Response</span>
                    </div>
                </div>
                <!-- Stat 3 -->
                <div class="bg-zinc-50 dark:bg-zinc-950/40 border border-zinc-200/40 dark:border-zinc-800/40 rounded-xl p-5">
                    <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Erros Estruturados</span>
                    <div class="flex items-end justify-between mt-2">
                        <span class="text-3xl font-black font-title text-zinc-900 dark:text-white">0 Erros</span>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-500/10 py-1 px-2 rounded-md">Validados</span>
                    </div>
                </div>
            </div>

            <!-- Simulated diagram / data lines -->
            <div class="mt-6 bg-zinc-50 dark:bg-zinc-950/20 border border-zinc-200/40 dark:border-zinc-800/40 rounded-xl p-6 text-left">
                <h4 class="text-sm font-bold text-zinc-900 dark:text-white mb-4">Monitoramento de Indexação Googlebot</h4>
                <div class="flex flex-col gap-3">
                    <div class="flex justify-between text-xs text-zinc-500 dark:text-zinc-400">
                        <span>Página Inicial (/)</span>
                        <span class="text-emerald-500 font-bold">100% Indexada</span>
                    </div>
                    <div class="w-full bg-zinc-200 dark:bg-zinc-800 rounded-full h-2">
                        <div class="bg-violet-600 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-zinc-500 dark:text-zinc-400 mt-2">
                        <span>Página Sobre (/sobre)</span>
                        <span class="text-emerald-500 font-bold">100% Indexada</span>
                    </div>
                    <div class="w-full bg-zinc-200 dark:bg-zinc-800 rounded-full h-2">
                        <div class="bg-violet-600 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 2: Logo Cloud / Trusted Partners -->
<section class="py-12 border-y border-zinc-200/50 dark:border-zinc-800/50 bg-zinc-50 dark:bg-zinc-950/20 transition-colors">
    <div class="max-w-7xl mx-auto px-6">
        <p class="text-center text-xs font-bold uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-8">
            Empoderando criadores digitais em marcas confiáveis
        </p>
        <div class="flex flex-wrap items-center justify-center gap-x-16 gap-y-8 opacity-60 grayscale dark:invert">
            <span class="font-title font-extrabold text-2xl tracking-tighter">ACME.IO</span>
            <span class="font-title font-bold text-2xl tracking-tight">GLOBEX CO.</span>
            <span class="font-title font-black text-2xl tracking-normal">INITECH</span>
            <span class="font-title font-extrabold text-2xl tracking-widest text-violet-600">UMBRELLA</span>
            <span class="font-title font-semibold text-2xl tracking-wide">SOYLENT</span>
        </div>
    </div>
</section>

<!-- Section 3: Bento Grid Core Features -->
<section id="recursos" class="py-24 bg-zinc-50 dark:bg-zinc-900/10 transition-colors">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-20">
            <span class="text-xs font-bold text-violet-600 dark:text-violet-400 uppercase tracking-widest">Diferenciais</span>
            <h2 class="text-4xl md:text-5xl font-black font-title tracking-tight text-zinc-900 dark:text-white mt-3">
                Projetado para Performance Extrema
            </h2>
            <p class="text-zinc-600 dark:text-zinc-400 mt-4 max-w-2xl mx-auto text-[17px] leading-relaxed">
                Cada módulo foi meticulosamente desenhado para garantir que os motores de busca acessem e indexem sua página em frações de segundos.
            </p>
        </div>
        
        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1: Main Highlight (Large) -->
            <div class="group md:col-span-2 bg-white dark:bg-zinc-900 border border-zinc-200/60 dark:border-zinc-800/60 rounded-3xl p-8 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-violet-950/40 text-violet-600 dark:text-violet-400 flex items-center justify-center text-2xl mb-6">
                        📈
                    </div>
                    <h3 class="text-2xl font-bold font-title tracking-tight text-zinc-900 dark:text-white mb-3">
                        Geração de Schema Org JSON-LD Dinâmico
                    </h3>
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed text-[15px] max-w-xl">
                        Insira os dados do seu negócio ou produto através de formulários nativos. O SEOMaker injeta e valida dados estruturados complexos direto no cabeçalho das páginas, garantindo que o Google exiba rich snippets ideais nos resultados de busca.
                    </p>
                </div>
                <div class="mt-8 pt-6 border-t border-zinc-100 dark:border-zinc-800/50 flex gap-4 text-xs font-semibold text-zinc-400">
                    <span>✓ Organizações</span>
                    <span>✓ Produtos</span>
                    <span>✓ Negócios Locais</span>
                </div>
            </div>

            <!-- Card 2: Speed (Vertical) -->
            <div class="group bg-white dark:bg-zinc-900 border border-zinc-200/60 dark:border-zinc-800/60 rounded-3xl p-8 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-violet-950/40 text-violet-600 dark:text-violet-400 flex items-center justify-center text-2xl mb-6">
                        ⚡
                    </div>
                    <h3 class="text-2xl font-bold font-title tracking-tight text-zinc-900 dark:text-white mb-3">
                        Zero Frameworks
                    </h3>
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed text-[15px]">
                        Código PHP limpo e otimizado com autoloader dinâmico e banco de dados SQLite nativo. Sem processos Node extras rodando no seu servidor de produção, entregando velocidade máxima no primeiro byte.
                    </p>
                </div>
                <span class="mt-8 text-xs font-bold text-violet-600 dark:text-violet-400">100% PHP Nativo &rarr;</span>
            </div>

            <!-- Card 3: Block Editor -->
            <div class="group bg-white dark:bg-zinc-900 border border-zinc-200/60 dark:border-zinc-800/60 rounded-3xl p-8 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-violet-950/40 text-violet-600 dark:text-violet-400 flex items-center justify-center text-2xl mb-6">
                        🧱
                    </div>
                    <h3 class="text-2xl font-bold font-title tracking-tight text-zinc-900 dark:text-white mb-3">
                        Editor de Blocos
                    </h3>
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed text-[15px]">
                        Monte páginas arrastando e configurando blocos de Texto, Imagens, Galerias de Mídia, Vídeos, Mapas Dinâmicos e FAQs de forma autônoma.
                    </p>
                </div>
                <span class="mt-8 text-xs font-bold text-violet-600 dark:text-violet-400">Arraste & Solte &rarr;</span>
            </div>

            <!-- Card 4: SEO Diagnostics (Large) -->
            <div class="group md:col-span-2 bg-white dark:bg-zinc-900 border border-zinc-200/60 dark:border-zinc-800/60 rounded-3xl p-8 hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-violet-950/40 text-violet-600 dark:text-violet-400 flex items-center justify-center text-2xl mb-6">
                        🎯
                    </div>
                    <h3 class="text-2xl font-bold font-title tracking-tight text-zinc-900 dark:text-white mb-3">
                        Painel de Diagnósticos & Visualização de SERP
                    </h3>
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed text-[15px] max-w-xl">
                        Monitore a aparência das suas páginas no Google antes mesmo de publicá-las. A ferramenta de pré-visualização de SERP integrada mostra exatamente o tamanho de caracteres recomendados para títulos e descrições meta em tempo real.
                    </p>
                </div>
                <div class="mt-8 pt-6 border-t border-zinc-100 dark:border-zinc-800/50 flex gap-4 text-xs font-semibold text-zinc-400">
                    <span>✓ Contador de Caracteres</span>
                    <span>✓ Prévia Google Mobile/Desktop</span>
                    <span>✓ Alertas de Títulos Longos</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 4: Performance Showcase Stats -->
<section class="py-24 bg-white dark:bg-zinc-950 transition-colors">
    <div class="max-w-7xl mx-auto px-6">
        <div class="bg-gradient-to-br from-violet-600 to-indigo-700 rounded-3xl p-8 md:p-16 text-white relative overflow-hidden shadow-2xl">
            <!-- Background element -->
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_right,rgba(59,130,246,0.3)_0%,transparent_50%)]"></div>
            
            <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                <div class="flex flex-col items-center">
                    <span class="text-6xl md:text-7xl font-black font-title tracking-tighter mb-2">100%</span>
                    <span class="text-xs uppercase font-bold tracking-widest text-violet-200">Pontuação de SEO</span>
                    <p class="text-sm text-violet-100 mt-2 max-w-xs leading-relaxed">Código sem bloqueio de renderização e sem tags pesadas desnecessárias no head.</p>
                </div>
                <div class="flex flex-col items-center border-y md:border-y-0 md:border-x border-white/10 py-8 md:py-0">
                    <span class="text-6xl md:text-7xl font-black font-title tracking-tighter mb-2">&lt; 0.1s</span>
                    <span class="text-xs uppercase font-bold tracking-widest text-violet-200">Carregamento Inicial</span>
                    <p class="text-sm text-violet-100 mt-2 max-w-xs leading-relaxed">Resposta instantânea do servidor por conta do peso insignificante do SQLite.</p>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-6xl md:text-7xl font-black font-title tracking-tighter mb-2">Zero</span>
                    <span class="text-xs uppercase font-bold tracking-widest text-violet-200">Erros de Validação</span>
                    <p class="text-sm text-violet-100 mt-2 max-w-xs leading-relaxed">Totalmente em conformidade com as diretrizes do Schema.org para indexação otimizada.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 5: Pricing Matrix Table -->
<section class="py-24 bg-zinc-50 dark:bg-zinc-900/10 border-y border-zinc-200/50 dark:border-zinc-800/50 transition-colors">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-20">
            <span class="text-xs font-bold text-violet-600 dark:text-violet-400 uppercase tracking-widest">Tabela de Preços</span>
            <h2 class="text-4xl md:text-5xl font-black font-title tracking-tight text-zinc-900 dark:text-white mt-3">
                Planos Sob Medida Para Sua Ideia
            </h2>
            <p class="text-zinc-600 dark:text-zinc-400 mt-4 max-w-xl mx-auto text-[17px] leading-relaxed">
                Comece de graça e faça o upgrade conforme suas necessidades de infraestrutura aumentarem.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
            <?php foreach ($pricing as $p): ?>
            <div class="bg-white dark:bg-zinc-900 border <?= $p['highlight'] ? 'border-violet-600 shadow-[0_0_30px_rgba(124,58,237,0.15)] ring-2 ring-violet-600/30' : 'border-zinc-200/60 dark:border-zinc-800/60' ?> rounded-3xl p-8 flex flex-col justify-between relative overflow-hidden transition-all hover:scale-102">
                <?php if ($p['highlight']): ?>
                    <span class="absolute top-4 right-4 bg-violet-600 text-white text-[10px] uppercase font-black py-1 px-3 rounded-full tracking-wider">
                        Mais Popular
                    </span>
                <?php endif; ?>
                <div>
                    <h3 class="text-xl font-bold font-title text-zinc-900 dark:text-white mb-2"><?= htmlspecialchars($p['name']) ?></h3>
                    <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-6"><?= htmlspecialchars($p['desc']) ?></p>
                    
                    <!-- Price block -->
                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-4xl font-black font-title text-zinc-900 dark:text-white"><?= htmlspecialchars($p['price']) ?></span>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400">/ <?= htmlspecialchars($p['period']) ?></span>
                    </div>

                    <!-- Features items list -->
                    <ul class="flex flex-col gap-3.5 mb-8">
                        <?php foreach ($p['features'] as $f): ?>
                        <li class="flex items-center gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                            <span class="w-4 h-4 rounded-full bg-violet-50 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center text-[10px] font-bold">✓</span>
                            <span><?= htmlspecialchars($f) ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <a href="#contato" class="w-full py-3.5 px-6 rounded-xl font-bold text-sm text-center transition-all <?= $p['highlight'] ? 'bg-violet-600 hover:bg-violet-500 text-white shadow-lg' : 'border border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-white hover:bg-zinc-50 dark:hover:bg-zinc-950' ?>">
                    <?= htmlspecialchars($p['cta']) ?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Section 6: Testimonials Grid (Wall of Love) -->
<section class="py-24 bg-white dark:bg-zinc-950 transition-colors">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-20">
            <span class="text-xs font-bold text-violet-600 dark:text-violet-400 uppercase tracking-widest">Prova Social</span>
            <h2 class="text-4xl md:text-5xl font-black font-title tracking-tight text-zinc-900 dark:text-white mt-3">
                Quem Usa, Aprova
            </h2>
            <p class="text-zinc-600 dark:text-zinc-400 mt-4 max-w-xl mx-auto text-[17px]">
                Veja o que os profissionais de desenvolvimento e especialistas em SEO dizem sobre nossa plataforma.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($testimonials as $t): ?>
            <div class="bg-zinc-50 dark:bg-zinc-900/20 border border-zinc-200/50 dark:border-zinc-800/50 rounded-3xl p-8 flex flex-col justify-between shadow-sm">
                <div>
                    <!-- Stars -->
                    <div class="flex gap-1 mb-5 text-amber-500 text-xs">
                        <?php for ($i = 0; $i < $t['stars']; $i++): ?>★<?php endfor; ?>
                    </div>
                    <blockquote class="text-zinc-600 dark:text-zinc-400 text-[15px] leading-relaxed mb-6 italic">
                        "<?= htmlspecialchars($t['quote']) ?>"
                    </blockquote>
                </div>
                <div class="flex items-center gap-4 border-t border-zinc-200/40 dark:border-zinc-800/40 pt-5">
                    <div class="w-10 h-10 rounded-full bg-violet-100 dark:bg-violet-950/60 text-violet-700 dark:text-violet-400 flex items-center justify-center font-bold text-xs">
                        <?= htmlspecialchars($t['initials']) ?>
                    </div>
                    <div class="flex flex-col">
                        <h4 class="text-[14px] font-bold text-zinc-900 dark:text-white"><?= htmlspecialchars($t['author']) ?></h4>
                        <span class="text-xs text-zinc-400 dark:text-zinc-500"><?= htmlspecialchars($t['role']) ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Section 7: Accordion FAQ block -->
<section class="py-24 bg-zinc-50 dark:bg-zinc-900/10 border-y border-zinc-200/50 dark:border-zinc-800/50 transition-colors">
    <div class="max-w-4xl mx-auto px-6">
        <h2 class="text-3xl md:text-4xl font-bold font-title tracking-tight text-zinc-900 dark:text-white text-center mb-16">
            Perguntas Frequentes
        </h2>
        <div class="flex flex-col gap-4">
            <?php foreach ($faqs as $faq): ?>
            <details class="group bg-white dark:bg-zinc-900 border border-zinc-200/60 dark:border-zinc-800/60 rounded-2xl overflow-hidden transition-all duration-200">
                <summary class="p-6 font-title font-semibold text-lg text-zinc-900 dark:text-white cursor-pointer list-none flex justify-between items-center select-none">
                    <?= htmlspecialchars($faq['q']) ?>
                    <span class="text-zinc-400 group-open:rotate-45 transition-transform duration-250 text-xl font-light">&rarr;</span>
                </summary>
                <div class="px-6 pb-6 text-zinc-600 dark:text-zinc-400 text-[15px] leading-relaxed border-t border-zinc-100 dark:border-zinc-800/40 pt-4">
                    <?= htmlspecialchars($faq['a']) ?>
                </div>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Section: Nossos Produtos -->
<?php if (!empty($featuredProducts)): ?>
<section id="produtos" class="py-24 bg-zinc-50 dark:bg-zinc-900/20 border-y border-zinc-200/50 dark:border-zinc-800/50 transition-colors">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 text-violet-600 dark:text-violet-400 text-sm font-semibold uppercase tracking-widest mb-4">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                Catálogo
            </div>
            <h2 class="text-4xl font-bold text-zinc-900 dark:text-white tracking-tight mb-4">Nossos Produtos</h2>
            <p class="text-zinc-500 dark:text-zinc-400 text-lg">Confira os produtos em destaque da nossa loja.</p>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($featuredProducts as $prod): ?>
            <?php
                $hasPromo  = !empty($prod['promo_price']) && (float)$prod['promo_price'] > 0;
                $finalPrice = $hasPromo ? (float)$prod['promo_price'] : (float)$prod['price'];
                $productUrl = !empty($prod['external_link']) ? htmlspecialchars($prod['external_link']) : '/produtos/' . htmlspecialchars($prod['slug']);
                $isExternal = !empty($prod['external_link']);
            ?>
            <article class="group relative bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <!-- Image -->
                <div class="relative aspect-square bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                    <?php if (!empty($prod['image_path'])): ?>
                        <img
                            src="<?= htmlspecialchars($prod['image_path']) ?>"
                            alt="<?= htmlspecialchars($prod['name']) ?>"
                            loading="lazy"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        >
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-content-center text-zinc-300 dark:text-zinc-700" style="display:flex;align-items:center;justify-content:center;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                    <?php endif; ?>
                    <?php if ($hasPromo): ?>
                        <span class="absolute top-3 left-3 bg-violet-600 text-white text-xs font-bold px-2.5 py-1 rounded-full">Em Promoção</span>
                    <?php endif; ?>
                    <?php if (!empty($prod['category'])): ?>
                        <span class="absolute top-3 right-3 bg-white/90 dark:bg-zinc-900/90 backdrop-blur-sm text-zinc-600 dark:text-zinc-300 text-xs font-medium px-2.5 py-1 rounded-full border border-zinc-200/60 dark:border-zinc-700/60"><?= htmlspecialchars($prod['category']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Info -->
                <div class="p-5">
                    <h3 class="font-semibold text-zinc-900 dark:text-white text-sm leading-snug mb-1 line-clamp-2"><?= htmlspecialchars($prod['name']) ?></h3>
                    <?php if (!empty($prod['short_description'])): ?>
                        <p class="text-zinc-500 dark:text-zinc-400 text-xs leading-relaxed mb-3 line-clamp-2"><?= htmlspecialchars($prod['short_description']) ?></p>
                    <?php endif; ?>

                    <!-- Price -->
                    <div class="flex items-baseline gap-2 mb-4">
                        <?php if ($hasPromo): ?>
                            <span class="text-xs text-zinc-400 line-through">R$ <?= number_format((float)$prod['price'], 2, ',', '.') ?></span>
                        <?php endif; ?>
                        <span class="text-lg font-bold <?= $hasPromo ? 'text-violet-600 dark:text-violet-400' : 'text-zinc-900 dark:text-white' ?>">
                            R$ <?= number_format($finalPrice, 2, ',', '.') ?>
                        </span>
                    </div>

                    <!-- CTA -->
                    <a
                        href="<?= $productUrl ?>"
                        <?= $isExternal ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
                        class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 text-sm font-semibold hover:bg-violet-600 dark:hover:bg-violet-500 dark:hover:text-white transition-colors duration-200"
                    >
                        <?= $isExternal ? 'Ver Oferta' : 'Saiba Mais' ?>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Section 8: Redesigned High-End Contact & Location Form -->
<section id="contato" class="py-24 bg-white dark:bg-zinc-950 transition-colors relative">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Left Info Block -->
            <div class="lg:col-span-5 flex flex-col gap-8">
                <div>
                    <span class="text-xs font-bold text-violet-600 dark:text-violet-400 uppercase tracking-widest">Contato</span>
                    <h2 class="text-4xl font-black font-title tracking-tight text-zinc-900 dark:text-white mt-3 mb-6">
                        Pronto Para Aumentar Seu Tráfego Orgânico?
                    </h2>
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed text-[16px]">
                        Preencha o formulário para falar com nosso time comercial. Responderemos em menos de 24 horas úteis com uma análise inicial gratuita de SEO do seu site atual.
                    </p>
                </div>
                
                <!-- Contact info cards list -->
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-4 bg-zinc-50 dark:bg-zinc-900/40 border border-zinc-200/50 dark:border-zinc-800/50 rounded-2xl p-5">
                        <span class="text-2xl">✉</span>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider">E-mail Comercial</span>
                            <a href="mailto:<?= htmlspecialchars(App\Core\Config::get('contact_email', 'contato@seomaker.studio')) ?>" class="text-[15px] font-semibold text-zinc-900 dark:text-white hover:text-violet-600 dark:hover:text-violet-400">
                                <?= htmlspecialchars(App\Core\Config::get('contact_email', 'contato@seomaker.studio')) ?>
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 bg-zinc-50 dark:bg-zinc-900/40 border border-zinc-200/50 dark:border-zinc-800/50 rounded-2xl p-5">
                        <span class="text-2xl">☏</span>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Telefone corporativo</span>
                            <a href="tel:<?= htmlspecialchars(App\Core\Config::get('contact_phone', '+55 (11) 99999-9999')) ?>" class="text-[15px] font-semibold text-zinc-900 dark:text-white">
                                <?= htmlspecialchars(App\Core\Config::get('contact_phone', '+55 (11) 99999-9999')) ?>
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 bg-zinc-50 dark:bg-zinc-900/40 border border-zinc-200/50 dark:border-zinc-800/50 rounded-2xl p-5">
                        <span class="text-2xl">📍</span>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Endereço</span>
                            <span class="text-[14px] text-zinc-700 dark:text-zinc-300">
                                <?= htmlspecialchars(App\Core\Config::get('contact_address', 'Av. Paulista, 1000 - São Paulo/SP')) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Form Card -->
            <div class="lg:col-span-7">
                <?php if (!empty($_SESSION['contact_success'])): ?>
                    <div class="bg-emerald-500/10 border border-emerald-500/25 text-emerald-600 dark:text-emerald-400 rounded-2xl p-5 mb-8 text-sm font-semibold">
                        <?= htmlspecialchars($_SESSION['contact_success']) ?>
                    </div>
                    <?php unset($_SESSION['contact_success']); ?>
                <?php endif; ?>
                
                <?php if (!empty($_SESSION['contact_errors'])): ?>
                    <div class="bg-rose-500/10 border border-rose-500/25 text-rose-600 dark:text-rose-400 rounded-2xl p-5 mb-8 text-sm">
                        <?php foreach ($_SESSION['contact_errors'] as $error): ?>
                            <p class="mb-1.5 last:mb-0 font-medium"><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                    <?php unset($_SESSION['contact_errors']); ?>
                <?php endif; ?>
                
                <form id="contact-form" action="/contact" method="POST" class="bg-zinc-50 dark:bg-zinc-900/60 border border-zinc-200/60 dark:border-zinc-800/60 rounded-3xl p-8 md:p-12 shadow-xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Nome Completo</label>
                            <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_SESSION['contact_data']['name'] ?? '') ?>" class="w-full bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl p-3.5 text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-violet-600 dark:focus:border-violet-500 focus:ring-4 focus:ring-violet-600/10 transition-all">
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">E-mail corporativo</label>
                            <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_SESSION['contact_data']['email'] ?? '') ?>" class="w-full bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl p-3.5 text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-violet-600 dark:focus:border-violet-500 focus:ring-4 focus:ring-violet-600/10 transition-all">
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="phone" class="block text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Telefone com DDD (opcional)</label>
                        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($_SESSION['contact_data']['phone'] ?? '') ?>" class="w-full bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl p-3.5 text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-violet-600 dark:focus:border-violet-500 focus:ring-4 focus:ring-violet-600/10 transition-all">
                    </div>
                    
                    <div class="mb-8">
                        <label for="message" class="block text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-2">Descreva seu projeto ou objetivo</label>
                        <textarea id="message" name="message" rows="5" required class="w-full bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl p-3.5 text-sm text-zinc-900 dark:text-white focus:outline-none focus:border-violet-600 dark:focus:border-violet-500 focus:ring-4 focus:ring-violet-600/10 transition-all" placeholder="Quais são as principais metas de SEO do seu site?"><?= htmlspecialchars($_SESSION['contact_data']['message'] ?? '') ?></textarea>
                    </div>
                    
                    <?php if (isset($_SESSION['contact_data'])) unset($_SESSION['contact_data']); ?>
                    <button type="submit" class="w-full bg-violet-600 hover:bg-violet-500 hover:shadow-[0_0_20px_rgba(124,58,237,0.35)] text-white font-bold py-4 px-6 rounded-xl transition-all active:scale-97 text-center">
                        Enviar Mensagem Comercial
                    </button>
                </form>
            </div>
            
        </div>
    </div>
</section>

<!-- Success Modal -->
<div id="contact-success-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200/60 dark:border-zinc-800/60 rounded-3xl p-8 max-w-md w-full shadow-2xl transform scale-95 transition-transform duration-300 relative overflow-hidden">
        <!-- Background light glow effect -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-violet-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="flex flex-col items-center text-center relative z-10">
            <!-- Icon -->
            <div class="w-16 h-16 bg-emerald-500/10 text-emerald-500 rounded-full flex items-center justify-center mb-6 ring-8 ring-emerald-500/5">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <!-- Content -->
            <h3 class="text-xl font-bold font-title text-zinc-900 dark:text-white mb-2">Mensagem Enviada!</h3>
            <p class="text-zinc-600 dark:text-zinc-400 text-sm leading-relaxed mb-8">
                Obrigado pelo seu contato. Nossa equipe de especialistas analisará sua mensagem e retornará em breve.
            </p>
            
            <!-- Close Button -->
            <button onclick="closeContactModal()" class="w-full bg-zinc-900 dark:bg-white hover:bg-zinc-800 dark:hover:bg-zinc-100 text-white dark:text-zinc-900 font-bold py-3.5 px-6 rounded-xl transition-all active:scale-97 cursor-pointer">
                Entendido
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    const modal = document.getElementById('contact-success-modal');
    const modalContent = modal ? modal.querySelector('div') : null;
    
    // Check if redirect query string contact=sent is present
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('contact') === 'sent') {
        openContactModal();
        // Clean url parameter without reloading page
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="inline-flex items-center gap-2 justify-center">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Enviando...
                </span>
            `;
            
            const formData = new FormData(form);
            
            fetch('/contact', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                
                if (data.success) {
                    form.reset();
                    // Clear error messages if any
                    const errorContainer = document.getElementById('contact-error-container');
                    if (errorContainer) errorContainer.classList.add('hidden');
                    
                    openContactModal();
                } else {
                    // Show validation or rate limit errors
                    showErrors(data.errors);
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                console.error('Error:', error);
                // Fallback to standard form submission if AJAX fails
                form.submit();
            });
        });
    }
    
    function openContactModal() {
        if (!modal) return;
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            if (modalContent) {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }
        }, 10);
    }
    
    window.closeContactModal = function() {
        if (!modal) return;
        modal.classList.add('opacity-0');
        if (modalContent) {
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
        }
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    };
    
    function showErrors(errors) {
        let errorContainer = document.getElementById('contact-error-container');
        if (!errorContainer) {
            errorContainer = document.createElement('div');
            errorContainer.id = 'contact-error-container';
            errorContainer.className = 'bg-rose-500/10 border border-rose-500/25 text-rose-600 dark:text-rose-400 rounded-2xl p-5 mb-8 text-sm';
            form.parentNode.insertBefore(errorContainer, form);
        }
        
        errorContainer.innerHTML = '';
        errorContainer.classList.remove('hidden');
        
        Object.keys(errors).forEach(key => {
            const p = document.createElement('p');
            p.className = 'mb-1.5 last:mb-0 font-medium';
            p.textContent = errors[key];
            errorContainer.appendChild(p);
        });
        
        // Scroll to error container
        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>
