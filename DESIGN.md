---

# Optima UI™ — Style Reference
> Un espacio de trabajo brillante, limpio y optimista para una gestión eficiente y sin fricciones.

**Theme:** light / bootstrap-native

La interfaz de Optima UI transmite una sensación de ligereza y control. Utiliza un lienzo blanco expansivo que contrasta con textos legibles en gris carbón, acentuado por azules vibrantes y tonos sutiles (subtle colors) que guían la atención sin abrumar. Su identidad visual se basa en los componentes de Bootstrap 5, priorizando tarjetas sin bordes fuertes, sombras suaves (`shadow-sm`), esquinas redondeadas (`rounded-3`) y un uso generoso del espacio en blanco. La tipografía aprovecha las fuentes nativas del sistema para garantizar máxima velocidad y familiaridad, manteniendo un ritmo visual optimista y moderno.

## Tokens — Colors (Basado en Bootstrap 5 Variables)

| Name            | Value     | Token / Class                        | Role                                                                                              |
| --------------- | --------- | ------------------------------------ | ------------------------------------------------------------------------------------------------- |
| Canvas White    | `#ffffff` | `--bs-body-bg` / `.bg-white`         | Fondo principal de la página, tarjetas y contenedores. Crea una fundación expansiva y brillante.  |
| Soft Cloud      | `#f8f9fa` | `--bs-light` / `.bg-light`           | Fondos sutiles para secciones secundarias, encabezados de tablas o estados de hover.              |
| Charcoal Ink    | `#212529` | `--bs-body-color` / `.text-dark`     | Texto principal, títulos y datos de tablas. Contraste alto pero menos agresivo que el negro puro. |
| Slate Gray      | `#6c757d` | `--bs-secondary` / `.text-secondary` | Metadatos, subtítulos, textos de ayuda e iconos secundarios.                                      |
| Border Ash      | `#dee2e6` | `--bs-border-color` / `.border`      | Divisores muy sutiles. Se prefiere usar espacios o sombras en lugar de bordes.                    |
| Optimistic Blue | `#0d6efd` | `--bs-primary` / `.text-primary`     | Llamadas a la acción principal (CTA), enlaces interactivos e indicadores de estado activo.        |
| Vibrant Mint    | `#198754` | `--bs-success` / `.text-success`     | Indicadores de éxito, valores positivos (ej. precios) o acciones afirmativas.                     |
| Alert Ruby      | `#dc3545` | `--bs-danger` / `.text-danger`       | Acciones destructivas (eliminar) o alertas críticas. Se usa con moderación.                       |
| Subtle Blue     | `#cfe2ff` | `--bs-primary-bg-subtle`             | Fondos de insignias (badges) o filas seleccionadas, aportando un toque de color muy suave.        |

## Tokens — Typography

### System UI
- **Substitute:** system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif
- **Weights:** 300 (Light), 400 (Regular), 500 (Medium), 600 (Semi-bold), 700 (Bold)
- **Role:** Fuente universal. Al delegar en las fuentes nativas del sistema operativo, se garantiza una carga instantánea y una familiaridad optimista. Los encabezados usan pesos mayores (600/700) para destacar, mientras que los datos y el cuerpo se mantienen en 400 para legibilidad.

### Type Scale (Clases Nativas)

| Role       | Bootstrap Class     | Font Size                | Font Weight | Line Height |
| ---------- | ------------------- | ------------------------ | ----------- | ----------- |
| Display    | `.h1` / `h1`        | `calc(1.375rem + 1.5vw)` | 600 / 700   | 1.2         |
| Heading    | `.h3` / `h3`        | `calc(1.3rem + .6vw)`    | 600         | 1.2         |
| Subheading | `.h5` / `h5`        | `1.25rem`                | 500         | 1.2         |
| Body Base  | `.fs-6` / `body`    | `1rem` (16px)            | 400         | 1.5         |
| Body Small | `.small` / `.fs-7`  | `0.875rem` (14px)        | 400         | 1.5         |
| Caption    | `.text-muted.small` | `0.875rem`               | 400         | 1.5         |

## Tokens — Spacing & Shapes

**Base unit:** 1rem (16px)
**Density:** Airy / Spacious (Uso generoso de `.p-4` y `.gap-3`).

### Border Radius

| Element  | Bootstrap Class   | Value    | Concept                                                        |
| -------- | ----------------- | -------- | -------------------------------------------------------------- |
| standard | `.rounded`        | 0.375rem | Botones base, inputs pequeños.                                 |
| cards    | `.rounded-3`      | 0.5rem   | Tarjetas principales, contenedores grandes (aspecto amigable). |
| pills    | `.rounded-pill`   | 50rem    | Badges, botones de estado, etiquetas.                          |
| circular | `.rounded-circle` | 50%      | Avatares o iconos flotantes.                                   |

### Shadows (Elevación)

| Name    | Bootstrap Class | Purpose                                                                      |
| ------- | --------------- | ---------------------------------------------------------------------------- |
| none    | `.shadow-none`  | Elementos a ras de lienzo, fondos secundarios.                               |
| subtle  | `.shadow-sm`    | **Estándar para todo el diseño.** Tarjetas de datos, contenedores de tablas. |
| regular | `.shadow`       | Elementos en estado hover o menús desplegables (Dropdowns).                  |
| large   | `.shadow-lg`    | Modales y diálogos superpuestos que requieren foco total.                    |

## Components (Bootstrap Compositions)

### Primary Filled Button
**Role:** Llamada a la acción principal (ej. "Nuevo Registro")
**Construcción:** `.btn .btn-primary .px-4 .rounded-pill`. Color sólido, texto blanco, bordes completamente redondeados para un aspecto amigable.

### Outline Action Button
**Role:** Acciones secundarias o herramientas (ej. "Editar", "Exportar")
**Construcción:** `.btn .btn-outline-secondary .btn-sm`. Fondo transparente, texto y borde sutil, no distrae de la acción principal.

### Feature Card (Contenedor Principal)
**Role:** Envolver listas, tablas o formularios.
**Construcción:** `.card .border-0 .shadow-sm .rounded-3`. Sin borde sólido (`border-0`), fondo blanco, esquinas suaves y elevación ligera. 
*Header de la tarjeta:* `.card-header .bg-white .border-bottom-0 .py-3`.

### Data Table
**Role:** Mostrar información (ej. Productos, Categorías).
**Construcción:** `.table .table-hover .table-borderless .align-middle`. Sin líneas verticales, alineación vertical centrada perfecta. Encabezados con `.text-secondary .fw-normal .small`.

### Soft Badge
**Role:** Indicador de estado o categoría.
**Construcción:** `.badge .bg-primary-subtle .text-primary .rounded-pill`. Evitar los colores de fondo sólidos; usar las utilidades "subtle" de Bootstrap.

## Do's and Don'ts

### Do
*   **Utiliza clases de utilidad:** Aplica flexbox (`.d-flex`, `.align-items-center`, `.gap-2`, `.gap-3`) en lugar de escribir CSS personalizado.
*   **Mantén el fondo limpio:** Prioriza el fondo blanco (`.bg-white`) con sombra ligera (`.shadow-sm`) sobre el fondo gris de la página (`.bg-light`).
*   **Simplifica las tablas:** Usa `.align-middle` en `<tbody>` para alinear botones e imágenes.
*   **Agrupa acciones:** Usa `.btn-group` con botones de iconos para ahorrar espacio.

### Don't
*   **Evita bordes duros:** No uses `border-radius: 0` ni la clase `.card` sin `.border-0 .shadow-sm`.
*   **No satures de color:** Evita `.table-primary` en toda la tabla. Deja que el color destaque en botones o insignias.
*   **Evita CSS en línea:** No uses `style=""` ni atributos arcaicos como `width="50"`.

## Surfaces & Elevation

| Level | Background  | Shadows      | Purpose                                                      |
| ----- | ----------- | ------------ | ------------------------------------------------------------ |
| 0     | `.bg-light` | Ninguna      | Lienzo base de la aplicación `<body class="bg-light">`.      |
| 1     | `.bg-white` | `.shadow-sm` | Tarjetas de contenido, contenedores de tablas y formularios. |
| 2     | `.bg-white` | `.shadow-lg` | Menús flotantes (dropdowns) y Modales.                       |

## Agent Prompt Guide (Reference for AI)

### Quick Concept Reference
*   **Estructura Base:** `card border-0 shadow-sm rounded-3`
*   **Layout Interno:** Flexbox con `gap-3` y `p-4`
*   **Interacciones:** `btn-outline-secondary` para acciones menores, `*-subtle` para etiquetas.

### Example Prompts
1.  **Refactor Table:** "Convierte esta tabla al estilo Optima UI usando `.table-borderless`, `.table-hover` y `.align-middle`. Envuélvela en un contenedor `.card .border-0 .shadow-sm`. Agrupa los botones con `.btn-group`."
2.  **Create Header:** "Crea un encabezado con fondo `.bg-white` en un `.card-header`. Usa `.d-flex .justify-content-between .align-items-center` para alinear un `h4` a la izquierda y un botón `.btn .btn-primary .rounded-pill` a la derecha."
3.  **Design Badge:** "Genera una etiqueta usando `.badge`, `.rounded-pill`, `.bg-primary-subtle` y `.text-primary`."