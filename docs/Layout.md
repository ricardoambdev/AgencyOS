# Refatoração Completa do Sistema - Novo Design System + AdminJS

## Objetivo

Realize uma refatoração completa da interface do sistema, padronizando todo o layout utilizando um Design System baseado na identidade visual da Agência Sentapúa.

Além disso, substitua completamente o painel administrativo existente pelo AdminJS, mantendo todas as funcionalidades já implementadas.

**Importante:** Esta tarefa consiste em uma refatoração visual e arquitetural. Nenhuma funcionalidade existente deve ser removida.

---

# Parte 1 - Novo Design System

## Paleta Oficial

### Primary

```text
50  #F6FFF2
100 #D9FAC9
200 #D1FAB8
300 #C2F269
400 #BDF04D
500 #A3D825
600 #8EC91F
700 #73A61A
800 #5A7F16
900 #3E5910
```

### Neutral

```text
50  #FFFFFF
100 #F6FFF2
200 #E8F2E3
300 #D5DDD1
400 #B5CBAA
500 #9BAA94
600 #7D8479
700 #5F645C
800 #4E514C
900 #2E312D
```

---

## Tipografia

Utilizar:

- Inter
- Geist
- Nunito

Priorizar Inter.

---

## Estilo Visual

O sistema deverá seguir uma identidade:

- moderna
- minimalista
- clean
- corporativa
- profissional
- responsiva
- com bastante espaçamento
- bordas arredondadas (12~16px)
- sombras suaves
- animações discretas

Inspirar-se em:

- Linear
- Vercel
- Notion
- Stripe Dashboard
- Clerk
- Shadcn/UI

---

## Componentes

Padronizar TODOS os componentes.

Criar componentes reutilizáveis para:

- Button
- Input
- Textarea
- Checkbox
- Radio
- Select
- MultiSelect
- DatePicker
- TimePicker
- Modal
- Dialog
- Drawer
- Dropdown
- Tooltip
- Popover
- Toast
- Badge
- Card
- Tabs
- Accordion
- Breadcrumb
- Avatar
- Table
- Pagination
- Empty State
- Loading
- Skeleton
- Alert
- Progress
- Charts
- Timeline
- Search
- Command Palette

Todos devem seguir a paleta de cores.

---

# Sidebar

Redesenhar completamente.

Características:

- largura confortável
- ícones Lucide
- menus recolhíveis
- animações suaves
- destaque verde da marca
- indicador da página atual
- suporte a submenu
- totalmente responsiva

---

# Header

Novo Header contendo:

- Logo
- Breadcrumb
- Pesquisa global
- Notificações
- Perfil
- Tema claro/escuro
- Configurações

---

# Dashboard

Redesenhar completamente.

Adicionar:

Cards modernos

Estatísticas

Gráficos

Atividades recentes

Ações rápidas

Indicadores

Widgets

Tudo utilizando a nova identidade.

---

# Formulários

Todos os formulários devem ser refeitos.

Requisitos:

Layout limpo

Espaçamento consistente

Mensagens de erro elegantes

Validação em tempo real

Estados:

- Focus
- Hover
- Disabled
- Error
- Success

---

# Tabelas

Substituir todas as tabelas.

Adicionar:

- paginação
- ordenação
- filtros
- pesquisa
- seleção múltipla
- exportação
- ações rápidas
- menu contextual

---

# Responsividade

Todo o sistema deve funcionar perfeitamente em:

Desktop

Notebook

Tablet

Celular

---

# Dark Mode

Criar tema escuro completo.

Não apenas inverter cores.

Criar uma identidade escura utilizando:

Background:

Neutral 900

Cards:

Neutral 800

Destaques:

Primary 400

---

# Animações

Adicionar animações utilizando Framer Motion.

Exemplos:

Fade

Slide

Scale

Hover

Loading

Page Transition

---

# Ícones

Utilizar exclusivamente:

Lucide Icons

---

# Componentes

Utilizar:

Shadcn/UI

---

# Parte 2 - Migração completa para AdminJS

Substituir completamente o painel administrativo atual por AdminJS.

Toda a área administrativa deverá funcionar através do AdminJS.

---

## Instalação

Instalar:

- adminjs
- @adminjs/express
- @adminjs/prisma (ou adapter correspondente)
- componentes personalizados

---

## Estrutura

Criar:

```
src/admin
```

com:

```
admin.ts

branding.ts

resources/

components/

actions/

hooks/

pages/

providers/

dashboard/

utils/
```

---

# Branding

Personalizar completamente o AdminJS.

Utilizar a identidade da Agência Sentapúa.

Aplicar:

Logo

Paleta

Ícones

Tipografia

Favicon

Tema

Cores

Espaçamentos

Botões

Cards

Sidebar

Header

Dashboard

---

## Dashboard Admin

Criar um dashboard personalizado contendo:

Indicadores

Gráficos

Últimos acessos

Estatísticas

Logs

Resumo geral

Atividades recentes

Cards

Widgets

---

# Recursos

Transformar TODAS as entidades existentes em Resources do AdminJS.

Cada Resource deve possuir:

List

Show

Edit

New

Delete

Search

Filters

Bulk Actions

Import

Export

---

# Recursos customizados

Criar páginas específicas quando necessário.

Exemplos:

Configurações

Uploads

Relatórios

Logs

Dashboard

Usuários

Permissões

Sistema

---

# Permissões

Implementar RBAC.

Perfis:

Administrador

Gestor

Operador

Professor

Usuário

Cada perfil deve possuir permissões específicas.

---

# Login

Integrar o login atual ao AdminJS.

Manter autenticação existente.

Caso necessário utilizar:

AdminJS Authentication

JWT

Session

Cookies

---

# Uploads

Integrar upload de arquivos ao AdminJS.

Permitir:

imagens

pdf

documentos

áudios

vídeos

---

# Logs

Adicionar:

Logs de acesso

Logs de alterações

Logs de exclusão

Logs de criação

Auditoria

---

# Auditoria

Registrar:

Usuário

Data

IP

Alteração

Valor anterior

Valor novo

---

# Dashboard Público

Toda a área administrativa deverá utilizar AdminJS.

A área pública do sistema NÃO deve ser alterada em sua arquitetura, apenas em seu design para seguir o novo Design System.

---

# Organização do Projeto

Refatorar toda a estrutura de componentes.

Criar:

```
components/

ui/

layout/

forms/

tables/

charts/

dashboard/

admin/

shared/
```

Separar responsabilidades.

Eliminar código duplicado.

---

# Código

Seguir boas práticas:

SOLID

Clean Architecture

Clean Code

Componentização

Hooks reutilizáveis

Tipagem completa

Sem uso de any

Sem código morto

Sem duplicações

---

# Objetivo Final

Ao concluir esta tarefa, o sistema deverá apresentar:

- Interface moderna e consistente baseada na identidade visual da Agência Sentapúa.
- Design System completo utilizando a paleta oficial.
- Componentes reutilizáveis e padronizados.
- Experiência responsiva e acessível.
- Tema claro e escuro.
- Animações suaves.
- Painel administrativo totalmente migrado para o AdminJS, preservando todas as funcionalidades existentes.
- Dashboard administrativo moderno, customizado e alinhado visualmente ao restante do sistema.
- Código organizado, modular, escalável e preparado para futuras evoluções.

**Importante:** Antes de alterar qualquer funcionalidade, analise toda a estrutura existente, identifique dependências e realize a migração de forma incremental, garantindo compatibilidade retroativa e evitando regressões. Ao final, execute uma revisão completa do projeto, removendo componentes antigos não utilizados, atualizando imports e assegurando que todas as telas utilizem exclusivamente o novo Design System e o AdminJS.