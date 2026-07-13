# Design Spec: Public Frontend Visual Overhaul

> 🎨 **Designer Spec Suite**
> Esse documento define a identidade visual, escala de tokens, comportamento responsivo e especificações de movimento para o novo frontend público do SEOMaker.

---

## 1. Brand Narrative & Case-Study Frame

- **Problem:** O template público padrão do SEOMaker carece de polimento e profissionalismo, gerando desconfiança ao usuário final e reduzindo as taxas de conversão de leads orgânicos.
- **Audience:** Clientes finais buscando autoridade técnica, facilidade de contato e clareza nos serviços prestados. Eles valorizam rapidez de resposta e interfaces responsivas limpas.
- **Insight:** Interfaces com tipografia geométrica proeminente e micro-efeitos de profundidade (glassmorphism/luzes sutis) elevam imediatamente o valor percebido de um produto SaaS ou agência de serviço.
- **Solution:** Redesenhar o portal público em uma estrutura minimalista com profundidade translúcida (estilo Vercel/Linear), utilizando fontes premium e carregamento inteligente (lazy loading) visível.

---

## 2. Aesthetic Direction Statement

A identidade visual selecionada é **Linear-like Minimalist com nuances de Futuristic Glassmorphic**. 
O design utiliza uma paleta de cinzas profundos (no modo escuro) e brancos puros (no modo claro), demarcados por linhas finas de 1px e desfoques de fundo translúcidos. A tipografia destaca títulos expressivos com a fonte geométrica **Outfit** em contraste com o corpo legível em **Inter**, gerando uma sensação de produto moderno de alto desempenho.

---

## 3. Color System (HSL Adaptive)

Todas as cores são declaradas como componentes HSL brutos para possibilitar opacidades reativas (ex: `hsl(var(--primary) / 0.1)`).

| Token | Light Mode | Dark Mode | Usage |
| :--- | :--- | :--- | :--- |
| `--bg-primary` | `0 0% 100%` | `240 10% 3.9%` | Background principal da página |
| `--bg-surface` | `0 0% 98%` | `240 10% 6%` | Cards de recursos, depoimentos e caixas |
| `--bg-elevated` | `0 0% 100%` | `240 10% 10%` | Navbar flutuante, modais e alertas |
| `--text-primary` | `240 10% 4%` | `0 0% 98%` | Títulos e texto de corpo principal |
| `--text-secondary` | `240 5% 35%` | `240 5% 65%` | Descrições curtas e subtítulos |
| `--text-muted` | `240 5% 55%` | `240 5% 45%` | Rodapé atenuado, placeholders e metadados |
| `--primary` | `262 83% 58%` | `262 83% 62%` | Cor de destaque primária (Violeta Neon) |
| `--primary-foreground`| `0 0% 100%` | `0 0% 100%` | Texto sobre botões primários |
| `--border` | `240 6% 90%` | `240 6% 15%` | Bordas finas de 1px e divisórias |
| `--success` | `142 76% 36%` | `142 72% 29%` | Estados de sucesso / Alerta positivo |
| `--error` | `0 84% 60%` | `0 84% 60%` | Mensagens de validação e erros |
| `--focus` | `262 83% 58%` | `262 83% 62%` | Contorno de foco de teclado / glow |

---

## 4. Typography System

Utilização de Outfit (Display) e Inter (Text) importados diretamente do Google Fonts.

| Level | Font | Size | Line-Height | Letter-Spacing | Weight | Usage |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **H1** | `Outfit` | `3.5rem (56px)` | `1.1` | `-0.04em` | `800` | Hero headlines principais |
| **H2** | `Outfit` | `2.25rem (36px)`| `1.2` | `-0.02em` | `700` | Títulos de seções principais |
| **H3** | `Outfit` | `1.5rem (24px)` | `1.3` | `-0.01em` | `600` | Títulos de cards de recursos |
| **Body** | `Inter` | `1rem (16px)` | `1.6` | `0` | `400` | Parágrafos e texto corrido |
| **Body-sm**| `Inter` | `0.875rem (14px)`| `1.5` | `0` | `400` | Legendas, rodapés e metas |
| **Label** | `Inter` | `0.75rem (12px)` | `1` | `0.05em` | `600` | Badges, textos de inputs |
| **Button**| `Inter` | `0.95rem (15px)` | `1` | `-0.01em` | `500` | Botões e links de nav |

---

## 5. Design Tokens

### Spacing Scale (4px base)
- `--space-xs`: `4px`
- `--space-sm`: `8px`
- `--space-md`: `16px`
- `--space-lg`: `24px`
- `--space-xl`: `32px`
- `--space-2xl`: `48px`
- `--space-3xl`: `64px`

### Border Radius
- `--radius-sm`: `6px` (pequenos inputs, badges)
- `--radius-md`: `10px` (botões normais, dropdowns)
- `--radius-lg`: `16px` (cards de recursos, formulário)
- `--radius-full`: `9999px` (pills, avatares)

### Elevation & Depth Shadows
- `--shadow-resting`: `0 1px 3px rgba(0,0,0,0.05)` (estado normal de cards)
- `--shadow-hover`: `0 20px 40px -15px rgba(0,0,0,0.1)` (efeito de flutuação no mouse hover)
- `--shadow-glow`: `0 0 20px hsl(var(--primary) / 0.15)` (destaque neon ativado)

---

## 6. Component Specs

### 1. Botão Primário (`.btn-primary`)
- **Estilos:** Background `hsl(var(--primary))`, cor `hsl(var(--primary-foreground))`, padding `12px 24px`, borda neutra, radius `--radius-md`.
- **Estados:**
  - *Hover:* Background atenuado sutilmente, sombra `--shadow-glow`.
  - *Active:* Escala reduzida a `97%` (`transform: scale(0.97)`).
  - *Focus:* Contorno circular violeta com offset de 2px.

### 2. Cards Glassmorphic (`.card`)
- **Estilos:** Background `hsl(var(--card))`, borda `1px solid hsl(var(--border))`, radius `--radius-lg`, padding `--space-xl`, sombra `--shadow-resting`.
- **Hover:** `translateY(-6px)`, borda ligeiramente mais clara, sombra `--shadow-hover`.

### 3. Campos de Formulário (`.form-control`)
- **Estilos:** Background translúcido, borda `1px solid hsl(var(--border))`, radius `--radius-sm`, fonte `Inter`, transição de borda 0.2s.
- **Foco:** Borda assume `hsl(var(--primary))`, glow interno sutil `box-shadow: 0 0 0 3px hsl(var(--primary) / 0.15)`.

---

## 7. Layout Structure (Landing Sequence)

Abaixo está o mapeamento conceitual da sequência da página inicial pública:

```
+-------------------------------------------------------------+
| Navbar [Brand/Logo] .......... [Menu Links Dynamic] [Admin] | (Sticky + Blur)
+-------------------------------------------------------------+
|                                                             |
|                       HERO SECTION                          |
|         H1: Titulo Otimizado com Gradiente Neon             |
|         p: Descrição clara do produto/serviço               |
|            [ Botão CTA Primário ] [ Saiba Mais ]            |
|                                                             |
+-------------------------------------------------------------+
|                                                             |
|                    SERVICES / FEATURES                      |
|       +---------------------------------------------+       |
|       | Card 1         | Card 2        | Card 3     |       | (Glass Cards)
|       | Icon + Title   | Icon + Title  | Icon + Title       |
|       +---------------------------------------------+       |
|                                                             |
+-------------------------------------------------------------+
|                                                             |
|                    TESTIMONIALS SECTION                     |
|       +---------------------------------------------+       |
|       | "Depoimento 1" | "Depoimento 2"| "Depoimento"       | (Avatares + Grid)
|       | - Autor 1      | - Autor 2     | - Autor 3  |       |
|       +---------------------------------------------+       |
|                                                             |
+-------------------------------------------------------------+
|                                                             |
|                      CONTACT FORM                           |
|       +---------------------------------------------+       |
|       | Nome: [                     ]               |       | (Clean Input Foci)
|       | E-mail: [                   ]               |       |
|       | Mensagem: [                 ]               |       |
|       |              [ Enviar Mensagem ]            |       |
|       +---------------------------------------------+       |
|                                                             |
+-------------------------------------------------------------+
| Footer: [Brand]   |  Links: [Home/Sobre/etc]  | Contato info| (3-Column Grid)
+-------------------------------------------------------------+
```

---

## 8. Real-State Specs

- **Loading States:** Imagens e galerias usarão um placeholder cinza leve (`--bg-surface`) e opacidade reduzida antes do carregamento completo (com suporte a micro-shimmer no CSS).
- **Empty States:** Listagens vazias do menu exibirão texto explicativo elegante em `--text-secondary` centrado com link direto para o administrador.
- **Error States:** Alertas de envio do formulário estilizados com background suave de erro (`hsl(var(--error) / 0.1)`) e bordas avermelhadas.
- **Success States:** Mensagem flutuante verde translúcida indicando envio do formulário, com animação elástica de entrada.

---

## 9. Presentation Mockups

1. **Browser-Frame Mockup:** No desktop, a navbar flutua centralizada com cantos arredondados e desfoque sobreposto à imagem do Hero, com linhas finas cinzas delimitando o viewport de 1200px.
2. **Device Mockup:** No celular, a navbar colapsa de forma fluida e o grid de 3 colunas de recursos e depoimentos se ajusta automaticamente para 1 coluna empilhada sem perda de espaçamento.
3. **Comparison:** Substituição das fontes sem peso e tabelas cinzas cruas por blocos espaçados com sombras flutuantes e botões reativos que indicam estado de clique instantâneo.

---

## 10. Motion Choreography

| Animação | Gatilho | Propriedade | Duração | Easing | Stagger | Reduced-Motion Fallback |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **Page load reveal** | Load | `opacity, translateY` | `400ms` | `cubic-bezier(0.16, 1, 0.3, 1)` | `index * 40ms` | Fade simples instantâneo |
| **Card lift** | Hover | `translateY, shadow` | `200ms` | `cubic-bezier(0.16, 1, 0.3, 1)` | Nenhuma | Apenas borda destaca |
| **Active button press**| Click | `scale` | `100ms` | `ease-out` | Nenhuma | Nenhuma |

---

## 11. Asset List

- **Icons:** Uso de ícones inline em SVG otimizados e consistentes (estilo outline moderno de 24px).
- **Textures:** Fundo geral com padrão de ruído de grão fino em modo escuro para aumentar a textura premium, e luz de fundo esférica violeta difusa (`radial-gradient`) sob o texto do Hero.
