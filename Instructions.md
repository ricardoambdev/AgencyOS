# 000 - PRINCÍPIOS DO PROJETO

> **AgencyOS - Plataforma Operacional Configurável**
>
> Versão: 1.0.0
>
> Este documento define os princípios fundamentais do AgencyOS. Nenhuma decisão arquitetural, funcional ou de desenvolvimento poderá contrariar estes princípios.

> o banco de dados é o "agency_os" no localhost no usuario "root" e sem senha

---

# 1. Propósito

O AgencyOS não é apenas um ERP.

Também não é apenas um CRM.

Também não é apenas um Gerenciador de Projetos.

O AgencyOS é uma Plataforma Operacional Configurável (Operational Platform), desenvolvida para administrar empresas baseadas em processos.

A primeira implementação será voltada para Agências de Marketing e Produção.

Entretanto, toda a arquitetura deverá permitir que outros segmentos utilizem a mesma plataforma apenas alterando configurações, workflows, templates e módulos.

---

# 2. Missão

Construir uma plataforma moderna, escalável e altamente configurável que centralize toda a operação de uma empresa em um único ambiente.

---

# 3. Visão

Ser uma plataforma capaz de substituir diversos sistemas independentes utilizados diariamente pelas empresas.

Exemplos:

- CRM
- Kanban
- Agenda
- Financeiro
- Portal do Cliente
- Wiki
- Gestão de Projetos
- Gestão Comercial
- Produção
- Dashboards
- Relatórios

Tudo integrado.

---

# 4. Filosofia

O AgencyOS seguirá os seguintes pilares.

## Simplicidade

Interfaces limpas.

Poucos cliques.

Poucas telas.

Poucos formulários.

---

## Organização

Cada informação possui apenas um local.

Não existe duplicação de dados.

---

## Integração

Todos os módulos compartilham informações.

Nenhum módulo funciona isoladamente.

---

## Configuração acima de Programação

Sempre que possível, novas funcionalidades deverão ser habilitadas através de configuração.

Não através de alteração no código.

---

## Automação

Tudo que puder ser automatizado deverá ser automatizado.

---

## Escalabilidade

Toda arquitetura deverá suportar crescimento contínuo.

---

## Modularidade

Novos módulos deverão ser adicionados sem alterar os módulos existentes.

---

# 5. Princípios Fundamentais

## Tudo é uma Entidade

O sistema será orientado por entidades.

Exemplos:

- Lead
- Cliente
- Projeto
- Contrato
- Usuário
- Equipamento
- Arquivo
- Comentário
- Evento
- Reunião

Cada entidade possui comportamento próprio.

---

## Tudo gera Eventos

Toda alteração importante deverá gerar um evento.

Exemplos:

LeadCriado

ClienteCriado

ProjetoCriado

ProjetoArquivado

ContratoAssinado

ArquivoEnviado

ComentárioCriado

UsuárioCriado

---

Os eventos deverão ser utilizados por:

- automações
- notificações
- auditoria
- timeline
- integrações
- filas

---

## Tudo possui Timeline

Toda entidade importante deverá possuir uma linha do tempo.

Exemplos:

Cliente

Projeto

Lead

Contrato

Equipamento

Produção

Usuário

A timeline representa a história completa daquela entidade.

---

## Tudo possui Comentários

Sempre que fizer sentido, será possível comentar.

Comentários internos.

Comentários públicos.

Comentários do cliente.

Comentários com anexos.

Menções de usuários.

---

## Tudo possui Anexos

Arquivos poderão ser anexados em praticamente qualquer entidade.

---

## Tudo possui Histórico

Nenhuma alteração importante deverá ser perdida.

---

## Tudo possui Auditoria

Sempre registrar:

Usuário

Data

Hora

IP

Ação

Valores anteriores

Novos valores

---

## Tudo pode ser Pesquisado

Toda entidade deverá ser encontrada através da pesquisa global.

---

## Tudo pode ser Favoritado

Cada usuário poderá criar sua própria lista de favoritos.

---

## Tudo pode possuir Tags

As tags serão reutilizáveis em todo o sistema.

---

## Tudo pode ser Relacionado

Exemplo:

Cliente

↓

Projetos

↓

Financeiro

↓

Arquivos

↓

Comentários

↓

Agenda

↓

Contratos

↓

Timeline

---

# 6. Processo acima do Projeto

O centro do sistema NÃO será o Projeto.

O centro será o Processo.

Fluxo esperado:

Lead

↓

Contato

↓

Reunião

↓

Proposta

↓

Negociação

↓

Contrato

↓

Projeto

↓

Execução

↓

Entrega

↓

Pós-venda

↓

Relacionamento

↓

Nova Venda

Projetos são apenas uma etapa.

---

# 7. Configurabilidade

O sistema deverá ser altamente configurável.

Sem necessidade de alterar código.

Exemplos:

Status

Categorias

Tipos

Menus

Campos

Dashboards

Permissões

Templates

Checklists

Pipelines

Workflows

Automações

Formulários

Widgets

---

# 8. Multiempresa

Toda informação pertence a uma Empresa (Workspace).

Nunca existirão dados compartilhados entre empresas.

O isolamento deverá ser garantido em toda consulta.

---

# 9. Template de Workspace

O sistema permitirá Templates.

Exemplo:

Workspace Agência

Workspace Escritório

Workspace Clínica

Workspace Consultoria

Workspace Eventos

Workspace Software House

Todos utilizam o mesmo Core.

---

# 10. Core First

O desenvolvimento deverá priorizar a construção do Core.

Os módulos nunca deverão implementar funcionalidades já existentes no Core.

---

# 11. Engines do Core

O Core será composto por Engines reutilizáveis.

Authentication Engine

Authorization Engine

Workflow Engine

Automation Engine

Notification Engine

Timeline Engine

Comment Engine

Attachment Engine

Search Engine

Dashboard Engine

Widget Engine

Audit Engine

Permission Engine

Settings Engine

Template Engine

Report Engine

Import Engine

Export Engine

AI Engine (futuro)

Todos os módulos deverão utilizar essas Engines.

Nunca reimplementar funcionalidades.

---

# 12. Eventos como Comunicação

Os módulos não deverão depender diretamente uns dos outros.

A comunicação deverá ocorrer através de:

Events

Listeners

Jobs

Queues

---

# 13. Código Limpo

Todo código deverá seguir:

SOLID

DRY

KISS

Clean Code

Convention over Configuration

Fail Fast

Separation of Concerns

---

# 14. Laravel First

Sempre utilizar recursos nativos do Laravel antes de adicionar bibliotecas externas.

Prioridade:

Policies

Events

Jobs

Queues

Observers

Notifications

Mail

Scheduler

Cache

Broadcast

Storage

Validation

Eloquent

Migrations

Factories

Seeders

Commands

---

# 15. Banco de Dados

Banco totalmente normalizado.

Foreign Keys.

Soft Deletes quando fizer sentido.

Índices.

ULIDs públicos.

Auditoria.

Migrations versionadas.

---

# 16. Interface

A interface deverá seguir princípios modernos.

Poucos cliques.

Pouca poluição visual.

Dark Mode.

Light Mode.

Desktop First.

Responsiva.

Acessível.

Feedback imediato.

---

# 17. Performance

Evitar consultas desnecessárias.

Utilizar Lazy Loading apenas quando apropriado.

Priorizar Eager Loading.

Utilizar Cache.

Utilizar Queues.

Paginação em todas as listagens grandes.

---

# 18. Segurança

Autenticação robusta.

Autorização por Policies.

CSRF.

XSS Protection.

Rate Limiting.

Logs.

Auditoria.

Permissões granulares.

---

# 19. Qualidade

Todo código deverá possuir:

Validação.

Tratamento de exceções.

Testes.

Documentação.

Tipagem consistente.

Nomenclatura padronizada.

---

# 20. Filosofia de Desenvolvimento

Antes de implementar uma funcionalidade, responder:

- Pode ser reutilizada?
- Pode ser configurável?
- Pode virar uma Engine?
- Pode ser baseada em Eventos?
- Pode servir para outros Workspaces?
- Pode ser desacoplada?

Se a resposta for "sim", a arquitetura deverá refletir essa decisão.

---

# 21. Princípios de Evolução

Nenhuma decisão tomada no MVP poderá impedir futuras expansões.

O sistema deverá ser preparado desde o início para:

- API Pública
- Aplicativo Mobile
- Inteligência Artificial
- Integrações
- Multi Idioma
- Multi Moeda
- Multi Empresa
- Multi Workspace
- Marketplace de Templates
- Marketplace de Módulos

---

# 22. Objetivo Final

Construir uma Plataforma Operacional moderna, escalável e configurável, capaz de atender empresas de diferentes segmentos através de um Core único, modular e orientado a processos, priorizando simplicidade, automação, integração e qualidade de código.

Toda decisão de arquitetura, desenvolvimento e produto deverá respeitar os princípios definidos neste documento.

# AgencyOS

> Plataforma Operacional Configurável para Gestão de Processos, Projetos e Operações Empresariais.

A primeira implementação oficial do AgencyOS será direcionada para Agências de Marketing, Publicidade, Produção, Design e Comunicação, utilizando um Workspace Template específico para esse segmento.

**Versão:** 1.1.0  
**Status:** Em Planejamento  
**Arquitetura:** Laravel 13 + PHP 8.5 + MySQL  
**Última atualização:** Julho de 2026

---

# 1. Visão Geral

O AgencyOS é uma Plataforma Operacional Configurável (Operational Platform) desenvolvida para centralizar e gerenciar processos de negócio, projetos, operações e relacionamento com clientes.

Embora sua primeira implementação seja voltada para agências criativas, sua arquitetura foi concebida para atender diferentes segmentos de mercado através de Workspaces configuráveis, Templates, Workflows, Engines e Módulos reutilizáveis.

O sistema não será apenas um CRM, ERP, Kanban ou Gerenciador de Projetos. Ele será o sistema operacional da empresa, centralizando toda a operação em uma única plataforma.

---

# 2. Propósito

Permitir que empresas organizem toda sua operação em uma única plataforma, desde a prospecção comercial até o relacionamento pós-venda.

O primeiro Workspace disponibilizado será destinado a Agências de Marketing e Produção, mas o Core deverá permitir a criação de Workspaces para outros segmentos sem alterações estruturais no código.

---

# 3. Objetivos Estratégicos

- Centralizar informações em uma única plataforma.
- Padronizar processos.
- Automatizar tarefas repetitivas.
- Eliminar retrabalho.
- Melhorar a colaboração entre equipes.
- Reduzir dependência de múltiplos sistemas.
- Disponibilizar indicadores em tempo real.
- Possibilitar crescimento através de configuração e não de customizações.

---

# 4. Filosofia do Produto

O AgencyOS será construído sobre os seguintes pilares:

- Simplicidade
- Organização
- Automação
- Integração
- Configurabilidade
- Escalabilidade
- Modularidade
- Segurança
- Performance

---

# 5. Visão do Produto

O sistema será orientado por processos de negócio.

Fluxo padrão:

Lead → Contato → Reunião → Proposta → Negociação → Contrato → Projeto → Produção → Revisão → Entrega → Pós-venda → Relacionamento → Nova Venda.

Projetos são consequência do processo comercial e não o centro do sistema.

## 5.1 Plataforma Baseada em Workspaces

O AgencyOS será composto por um Core único e Workspaces especializados.

Exemplos:

- Agência de Marketing
- Software House
- Consultoria
- Escritório Jurídico
- Produtora de Eventos
- Clínica

Cada Workspace poderá possuir:

- Workflows
- Templates
- Dashboards
- Campos personalizados
- Menus
- Configurações

Sempre reutilizando o mesmo Core.

---

# 6. O Problema

Empresas utilizam diversas ferramentas isoladas (CRM, agenda, planilhas, chat, armazenamento, financeiro, tarefas), gerando duplicidade de dados, retrabalho e falta de visão operacional.

O AgencyOS elimina essa fragmentação centralizando processos e informações.

---

# 7. A Solução

- Plataforma única.
- Informação única.
- Integração total entre módulos.
- Comunicação baseada em eventos.
- Workflows configuráveis.
- Automações.
- Dashboards inteligentes.

---

# 8. Público-Alvo

A primeira versão será destinada a:

- Agências de Marketing
- Agências Digitais
- Agências de Publicidade
- Estúdios Criativos
- Produtoras
- Empresas de Comunicação

A arquitetura deverá permitir expansão para outros segmentos.

---

# 9. Personas

## Diretor

Indicadores, produtividade, faturamento e rentabilidade.

## Comercial

CRM, propostas, contratos, follow-up e agenda.

## Atendimento

Projetos, clientes, aprovações e comunicação.

## Produção

Cronogramas, tarefas, arquivos e briefings.

## Financeiro

Fluxo de caixa, custos, receitas e indicadores.

## Cliente

Portal para acompanhar projetos, aprovar materiais e acessar arquivos.

---

# 10. Escopo

## Core Platform

- Autenticação
- Permissões
- Workflows
- Automações
- Timeline
- Comentários
- Arquivos
- Auditoria
- Pesquisa Global
- Dashboards
- Widgets
- Configurações
- Notificações

## Módulos de Negócio

- CRM
- Comercial
- Clientes
- Projetos
- Produção
- Agenda
- Financeiro
- Portal do Cliente
- Wiki
- Equipamentos
- Relatórios

---

# 11. Diferenciais

## Workflow Engine

Fluxos totalmente configuráveis.

## Automation Engine

Regras "SE/ENTÃO" baseadas em eventos.

## Templates

Projetos gerados automaticamente com tarefas, checklists, cronogramas e equipes.

## Campos Personalizados

Configuráveis sem alterar código.

## Workspaces

Especializações para diferentes segmentos utilizando o mesmo Core.

## Dashboard Personalizado

Cada usuário monta sua própria tela inicial.

## Timeline Universal

Histórico completo das entidades.

## Plataforma Orientada a Eventos

Toda ação relevante gera eventos internos para automações, integrações e auditoria.

## Auditoria

Registro completo das alterações.

---

# 12. Princípios Arquiteturais

- SOLID
- Clean Code
- DRY
- KISS
- Convention over Configuration
- Laravel First
- Service Layer
- DTOs
- Form Requests
- Policies
- Events
- Jobs
- Queues
- Observers

Toda regra de negócio deverá permanecer fora dos Controllers.

---

# 13. Tecnologias

## Backend

- Laravel 13
- PHP 8.5
- MySQL

## Frontend

- Blade
- Livewire 4
- Alpine.js
- Tailwind CSS 4

## Infraestrutura

- Laravel Queue
- Scheduler
- Notifications
- Cache
- Mail
- Storage Local (preparado para S3)

Priorizar sempre recursos nativos do Laravel.

---

# 14. Requisitos Funcionais

O sistema deverá permitir:

- CRM completo
- Gestão Comercial
- Clientes
- Contratos
- Projetos
- Tarefas
- Controle de Horas
- Agenda
- Produção
- Aprovações
- Equipamentos
- Financeiro
- Relatórios
- Dashboards
- Portal do Cliente
- Workflows
- Automações
- Campos Personalizados

---

# 15. Requisitos Não Funcionais

- Alto desempenho
- Segurança
- Responsividade
- Escalabilidade
- Banco normalizado
- Auditoria
- Backup
- Código limpo
- Facilidade de manutenção

---

# 16. MVP

- Core Platform
- Login
- Multiempresa
- Usuários
- Permissões
- CRM
- Clientes
- Projetos
- Tarefas
- Workflow Engine
- Timeline Engine
- Sistema de Eventos
- Sistema de Comentários
- Agenda
- Produção
- Portal do Cliente
- Financeiro Básico
- Dashboard
- Relatórios Principais

---

# 17. Roadmap

- Marketplace de Workspaces
- Marketplace de Templates
- Marketplace de Módulos
- API Pública
- Aplicativo Mobile
- BI Avançado
- IA para automações
- Chat interno
- Assinatura eletrônica
- Integrações com WhatsApp, Google Workspace e Microsoft 365

---

# 18. Critérios de Qualidade

Todo código deverá possuir:

- Tipagem consistente
- Validação
- Tratamento de erros
- Testes
- Documentação
- Nomenclatura padronizada

---

# 19. Visão de Longo Prazo

O AgencyOS deverá evoluir como uma plataforma operacional configurável, permitindo novos módulos, Workspaces e Engines sem reestruturação do Core.

A arquitetura deverá suportar múltiplas empresas, múltiplos Workspaces, APIs, aplicações móveis e integrações futuras.

---

# 20. Plataforma em Evolução

O Core deverá permanecer estável enquanto módulos e Workspaces evoluem de forma independente.

Toda evolução deverá preservar compatibilidade com versões anteriores sempre que possível.

---

# 21. Missão do Projeto

Construir uma plataforma operacional moderna, modular e configurável capaz de unificar CRM, Comercial, Projetos, Operações, Produção, Financeiro e Relacionamento com Clientes em um único ecossistema, oferecendo alta produtividade, escalabilidade e excelente experiência de uso.

# 01 - ARQUITETURA

> Documento de Arquitetura de Software (Software Architecture Document - SAD)

**Produto:** AgencyOS

**Versão:** 1.0.0

**Última atualização:** Julho de 2026

---

# 1. Objetivo

Este documento define toda a arquitetura do AgencyOS.

Seu objetivo é servir como referência para todas as decisões técnicas do projeto.

Toda implementação deverá seguir as definições aqui descritas.

Caso exista conflito entre este documento e qualquer outro documento da documentação, este documento possui prioridade técnica.

---

# 2. Visão Arquitetural

O AgencyOS foi concebido como uma Plataforma Operacional Configurável.

Sua arquitetura foi projetada para permitir:

- Alta escalabilidade
- Baixo acoplamento
- Alta coesão
- Facilidade de manutenção
- Facilidade de testes
- Reutilização de código
- Evolução contínua

O sistema deverá ser capaz de crescer durante anos sem necessidade de reestruturação completa.

---

# 3. Princípios Arquiteturais

Toda decisão técnica deverá respeitar os seguintes princípios.

## Simplicidade

A solução mais simples sempre será preferida.

Complexidade somente será adicionada quando realmente necessária.

---

## Modularidade

O sistema será dividido em Domínios de Negócio independentes.

Cada domínio deverá possuir responsabilidades claramente definidas.

---

## Baixo Acoplamento

Os módulos nunca deverão depender diretamente uns dos outros.

Toda comunicação ocorrerá através do Core ou através de Eventos.

---

## Alta Coesão

Cada componente deverá possuir apenas uma responsabilidade principal.

---

## Reutilização

Sempre que uma funcionalidade puder ser reutilizada por outros módulos, ela deverá ser implementada no Core.

---

## Configuração acima de Programação

Sempre que possível, funcionalidades deverão ser configuradas pelo usuário.

Nunca implementadas especificamente para um único cenário.

---

## Orientação a Eventos

Toda alteração importante deverá gerar Eventos internos.

Esses eventos serão utilizados por:

- Automações
- Notificações
- Auditoria
- Timeline
- Integrações
- Processamentos assíncronos

---

## Escalabilidade

Nenhuma decisão arquitetural poderá impedir crescimento futuro.

---

# 4. Filosofia da Arquitetura

O AgencyOS será dividido em duas grandes partes.

## Core Platform

O Core contém funcionalidades reutilizáveis por qualquer Workspace.

O Core nunca deverá conhecer regras de negócio específicas.

Sua responsabilidade é fornecer serviços compartilhados.

---

## Business Domains

Os Domínios representam as regras de negócio.

Cada domínio implementa apenas seu comportamento específico.

Os Domínios utilizam o Core.

O Core nunca utiliza os Domínios.

---

# 5. Arquitetura em Camadas

O projeto utilizará arquitetura em camadas.

```

```

┌─────────────────────────────────────┐
│ Presentation Layer                  │
│ Blade • Livewire • Controllers      │
└─────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────┐
│ Application Layer                   │
│ Actions • DTOs • Use Cases          │
└─────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────┐
│ Domain Layer                        │
│ Entities • Services • Policies      │
│ Events • Value Objects              │
└─────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────┐
│ Infrastructure Layer                │
│ MySQL • Storage • Queue • Cache     │
└─────────────────────────────────────┘

```

Cada camada possui responsabilidades específicas.

Nenhuma camada poderá violar as responsabilidades das demais.

---

# 6. Organização do Projeto

A estrutura principal da aplicação deverá seguir o padrão abaixo.

```

```
app/

Core/

Domains/

Shared/

Support/

Infrastructure/

```

---

# 7. Core Platform

O Core representa a fundação do sistema.

Ele deverá ser completamente independente dos módulos de negócio.

O Core jamais deverá possuir regras específicas de CRM, Financeiro, Projetos ou qualquer outro domínio.

Sua única responsabilidade será fornecer funcionalidades reutilizáveis.

---

## Engines do Core

O Core será composto pelas seguintes Engines.

### Authentication Engine

Responsável pela autenticação.

---

### Authorization Engine

Responsável por permissões.

Policies.

Roles.

Capabilities.

---

### Workflow Engine

Responsável pela execução dos Workflows.

Estados.

Transições.

Validações.

---

### Automation Engine

Executa ações automáticas.

Baseada em Eventos.

Baseada em Regras.

---

### Timeline Engine

Mantém histórico cronológico de qualquer entidade.

---

### Notification Engine

Centraliza todas as notificações.

Sistema.

Email.

Futuras integrações.

---

### Comment Engine

Sistema universal de comentários.

---

### Attachment Engine

Sistema universal de arquivos.

---

### Search Engine

Pesquisa Global.

---

### Dashboard Engine

Widgets.

Painéis.

Indicadores.

---

### Audit Engine

Registro de alterações.

---

### Report Engine

Relatórios reutilizáveis.

---

### Settings Engine

Configurações do sistema.

---

### Template Engine

Templates de Workspaces.

Templates de Projetos.

Templates de Checklists.

Templates de Formulários.

---

# 8. Business Domains

Os Domínios representam as funcionalidades específicas do sistema.

Cada Domínio deverá ser completamente independente.

Os Domínios nunca deverão compartilhar regras diretamente.

Toda comunicação ocorrerá através do Core.

---

Os Domínios iniciais serão:

CRM

Comercial

Clientes

Projetos

Produção

Agenda

Financeiro

Portal do Cliente

Wiki

Equipamentos

Usuários

Relatórios

Configurações

---

# 9. Estrutura de um Domínio

Todos os Domínios deverão seguir exatamente a mesma organização.

```

```

Domain/

Application/

Infrastructure/

Presentation/

```

---

## Domain

Contém as regras de negócio.

Exemplos:

Entities

Enums

Policies

Events

Value Objects

Interfaces

Rules

Services

---

## Application

Responsável pelos casos de uso.

Exemplos:

Actions

DTOs

UseCases

Commands

Queries

Jobs

---

## Infrastructure

Persistência.

Integrações.

Storage.

Implementações.

---

## Presentation

Controllers.

Livewire.

Requests.

Resources.

Views.

---

# 10. Fluxo de uma Requisição

Toda requisição deverá seguir o fluxo abaixo.

```

```
Usuário

↓

Livewire

↓

Controller

↓

Action

↓

Domain Service

↓

Repository

↓

Model

↓

MySQL
```

A resposta seguirá o caminho inverso.

Jamais colocar regras de negócio dentro dos Controllers.

Jamais acessar Models diretamente pelas Views.

---

# 11. Responsabilidades

## Controller

Receber a requisição.

Validar permissões.

Executar Actions.

Retornar Response.

Nunca implementar regras de negócio.

---

## Action

Executar um único caso de uso.

Cada Action deverá possuir apenas uma responsabilidade.

---

## Domain Service

Implementar regras de negócio.

---

## Repository

Abstrair acesso aos dados quando houver lógica de persistência complexa.

Não utilizar Repository para simples operações CRUD.

---

## Model

Representar o estado persistido da entidade.

Jamais concentrar regras complexas de negócio.

---

# 12. Convenções Gerais

Todos os nomes deverão seguir um padrão consistente.

Classes:

Singular.

Exemplo:

Cliente

Projeto

Lead

Contrato

---

Actions:

Verbo + Entidade

Exemplos:

CreateProjectAction

ConvertLeadAction

ApproveProjectAction

FinishTaskAction

---

Events:

Entidade + Evento

Exemplo:

ProjectCreated

LeadConverted

ClientCreated

TaskFinished

---

Jobs

Verbo + Objeto

Exemplo:

SendEmailJob

GenerateReportJob

CreateTimelineJob
---

# 13. Comunicação entre Domínios

Um dos princípios fundamentais do AgencyOS é o baixo acoplamento entre os Domínios.

Os Domínios nunca deverão depender diretamente uns dos outros.

Exemplo incorreto:

CRM chama diretamente Financeiro.

Projetos chama diretamente Clientes.

Produção chama diretamente Comercial.

Este tipo de comunicação gera dependências difíceis de manter.

Em vez disso, toda comunicação deverá ocorrer através do Core utilizando Eventos.

Fluxo esperado:

Lead Convertido

↓

Evento

↓

Core

↓

Listeners

↓

Clientes

↓

Projetos

↓

Financeiro

↓

Timeline

↓

Notificações

Cada domínio executa apenas sua responsabilidade.

Nenhum domínio conhece a implementação do outro.

---

# 14. Arquitetura Orientada a Eventos

Toda alteração importante dentro do sistema deverá gerar Eventos.

Exemplos:

LeadCreated

LeadUpdated

LeadConverted

ClientCreated

ProjectCreated

ProjectApproved

ProjectFinished

ContractSigned

TaskCreated

TaskFinished

CommentCreated

AttachmentUploaded

InvoiceGenerated

PaymentReceived

UserInvited

WorkspaceCreated

AutomationExecuted

WorkflowStarted

WorkflowFinished

Os Eventos representam fatos ocorridos dentro do sistema.

Eles nunca devem conter regras de negócio.

Seu único objetivo é informar que algo aconteceu.

---

# 15. Listeners

Cada Evento poderá possuir um ou mais Listeners.

Exemplo:

ProjectCreated

↓

CreateTimelineListener

↓

CreateDefaultTasksListener

↓

NotifyTeamListener

↓

RegisterAuditListener

↓

ExecuteAutomationListener

↓

GenerateProjectNumberListener

Cada Listener deverá possuir apenas uma responsabilidade.

Listeners nunca deverão depender uns dos outros.

---

# 16. Workflow Engine

A Workflow Engine será responsável por controlar o ciclo de vida das entidades.

Ela não conhece CRM.

Não conhece Projetos.

Não conhece Financeiro.

Ela conhece apenas:

Estados

↓

Transições

↓

Regras

↓

Permissões

↓

Eventos

Cada Workflow será configurável.

Exemplo:

Projeto

↓

Briefing

↓

Planejamento

↓

Produção

↓

Revisão

↓

Cliente

↓

Finalizado

Outra empresa poderá criar um Workflow completamente diferente.

Sem alterar código.

---

# 17. Automation Engine

A Automation Engine será baseada em regras.

Cada regra possuirá:

Evento

↓

Condição

↓

Ação

Exemplo

SE

Projeto mudou para Revisão

E

Cliente é Premium

ENTÃO

Criar tarefa

↓

Enviar Email

↓

Criar Notificação

↓

Atualizar Timeline

↓

Registrar Auditoria

As ações deverão ser desacopladas.

Cada ação será executada individualmente.

---

# 18. Timeline Engine

Toda entidade importante deverá possuir Timeline.

Exemplos:

Lead

Cliente

Projeto

Contrato

Equipamento

Produção

Usuário

Workspace

A Timeline deverá registrar:

Criações

Alterações

Comentários

Mudanças de Status

Arquivos

Automações

Notificações importantes

Eventos

Tudo em ordem cronológica.

---

# 19. Comment Engine

O sistema possuirá um mecanismo único de comentários.

Não existirão comentários específicos para Projetos.

Ou comentários específicos para Clientes.

Existirá apenas uma Engine.

Ela poderá ser utilizada por qualquer entidade.

Cada comentário poderá possuir:

Texto

Autor

Data

Menções

Anexos

Respostas

Reações

Visibilidade

Interna

Pública

Cliente

---

# 20. Attachment Engine

Todo arquivo será tratado da mesma forma.

Não existirão sistemas diferentes de upload.

A Attachment Engine será responsável por:

Upload

Versionamento

Download

Permissões

Miniaturas

Metadados

Relacionamentos

Qualquer entidade poderá possuir arquivos.

---

# 21. Notification Engine

Toda notificação será centralizada.

Tipos:

Sistema

Email

Push (futuro)

Webhook (futuro)

Cada notificação deverá possuir:

Título

Mensagem

Usuário

Origem

Data

Status

Lida

Não lida

---

# 22. Search Engine

O sistema possuirá Pesquisa Global.

A pesquisa deverá localizar:

Clientes

Projetos

Leads

Usuários

Comentários

Arquivos

Equipamentos

Contratos

Produções

Tarefas

Relatórios

Sempre respeitando as permissões do usuário.

---

# 23. Audit Engine

Toda alteração importante deverá gerar auditoria.

Registrar:

Usuário

IP

Data

Hora

Ação

Tabela

Registro

Valor anterior

Novo valor

Origem

A auditoria nunca poderá ser removida.

---

# 24. Dashboard Engine

Os Dashboards serão totalmente configuráveis.

Cada Dashboard será composto por Widgets.

Cada usuário poderá montar seu Dashboard.

Exemplos de Widgets:

Projetos

Leads

Financeiro

Agenda

Calendário

Indicadores

Equipe

Horas

Produção

Funil Comercial

---

# 25. Widget Engine

Widgets serão componentes independentes.

Cada Widget deverá possuir:

Configuração

Fonte de Dados

Permissões

Atualização

Cache

Renderização

Widgets poderão ser reutilizados em qualquer Dashboard.

---

# 26. Report Engine

Todo relatório utilizará a mesma Engine.

Cada relatório possuirá:

Fonte

Filtros

Colunas

Agrupamentos

Exportação

Permissões

Os formatos inicialmente suportados serão:

PDF

Excel

CSV

---

# 27. Multiempresa

Toda informação pertence obrigatoriamente a uma Empresa.

Não existirão registros globais.

Todas as consultas deverão respeitar o escopo da empresa ativa.

A troca de empresa deverá alterar completamente o contexto do sistema.

Nenhum dado poderá ser compartilhado entre empresas, salvo por futuras funcionalidades explícitas de compartilhamento.

---

# 28. Workspace

Cada Empresa utilizará um Workspace.

O Workspace representa a especialização da plataforma.

Exemplo:

Workspace Agência

Workspace Software House

Workspace Escritório Jurídico

Workspace Clínica

Cada Workspace poderá possuir:

Menus

Workflows

Templates

Dashboards

Widgets

Campos personalizados

Permissões

Automações

Sem modificar o Core.

---

# 29. Templates

Templates deverão reduzir trabalho manual.

Exemplo:

Criar Projeto Website

↓

Aplicar Template Website

↓

Criar Checklist

↓

Criar Equipe

↓

Criar Cronograma

↓

Criar Tarefas

↓

Criar Aprovações

↓

Criar Estrutura de Pastas

Tudo automaticamente.

---

# 30. Campos Personalizados

O sistema permitirá criação de campos personalizados.

Tipos suportados:

Texto

Número

Moeda

Data

Hora

Booleano

Arquivo

Imagem

Select

Multi Select

Relacionamento

Os campos deverão poder ser utilizados em:

Filtros

Relatórios

Dashboards

Pesquisa

Automações

Workflows
---

# 31. Estratégia de Banco de Dados

O banco de dados será modelado seguindo princípios de normalização, integridade referencial e escalabilidade.

## Princípios

- Utilizar InnoDB em todas as tabelas.
- Todas as tabelas possuirão chave primária.
- Toda relação deverá utilizar Foreign Keys.
- Índices deverão ser criados sempre que necessários para consultas frequentes.
- Evitar duplicação de informações.
- Utilizar Soft Deletes apenas quando fizer sentido para o negócio.
- Toda migration deverá ser reversível.

## Identificadores

Cada registro possuirá dois identificadores:

- ID (BIGINT AUTO_INCREMENT) para relacionamentos internos.
- ULID para identificação pública e URLs.

Exemplo:

```
id: 1548
ulid: 01K123ABCDXYZ...
```

O ID nunca deverá ser exposto externamente.

---

# 32. Estratégia de Persistência

O Eloquent será o ORM oficial do projeto.

O uso de Repository Pattern será reservado para cenários em que:

- houver consultas complexas;
- múltiplas fontes de dados;
- necessidade de desacoplamento da persistência.

Para operações CRUD simples, utilizar diretamente o Model + Query Builder.

Evitar abstrações desnecessárias.

---

# 33. Estratégia de Cache

O cache será utilizado apenas onde houver ganho real de desempenho.

Exemplos:

- Configurações
- Permissões
- Menus
- Dashboards
- Widgets
- Templates
- Campos personalizados

Nunca armazenar informações críticas que possam causar inconsistência.

Sempre invalidar o cache após alterações relevantes.

---

# 34. Estratégia de Filas (Queues)

Toda operação demorada deverá ser executada de forma assíncrona.

Exemplos:

- Envio de e-mails
- Geração de PDFs
- Exportações
- Importações
- Processamento de imagens
- Geração de relatórios
- Execução de automações
- Integrações externas

A interface nunca deverá aguardar esse processamento.

---

# 35. Estratégia de Storage

O sistema utilizará inicialmente o Storage Local do Laravel.

A arquitetura deverá permitir migração para:

- Amazon S3
- Cloudflare R2
- MinIO

sem alterações na regra de negócio.

Toda manipulação de arquivos deverá utilizar exclusivamente a Storage API do Laravel.

---

# 36. Segurança

A segurança será tratada como responsabilidade transversal em toda a aplicação.

## Autenticação

- Laravel Authentication
- Sessões seguras
- Recuperação de senha
- Verificação de e-mail (quando aplicável)

## Autorização

Utilizar exclusivamente:

- Gates
- Policies

Nunca validar permissões diretamente nas Views.

## Proteções obrigatórias

- CSRF
- XSS
- SQL Injection
- Mass Assignment
- Rate Limiting
- Validação de Uploads
- Sanitização de entradas

---

# 37. Observabilidade

Toda ação importante deverá gerar registros.

## Logs

Registrar:

- erros;
- exceções;
- autenticação;
- falhas de integração;
- falhas de filas.

## Auditoria

Registrar alterações de negócio.

## Métricas

Monitorar:

- tempo de resposta;
- consultas lentas;
- filas;
- consumo de recursos.

---

# 38. Estrutura Oficial do Projeto

```
app/

├── Core/
│
├── Domains/
│   ├── CRM/
│   ├── Comercial/
│   ├── Clientes/
│   ├── Projetos/
│   ├── Produção/
│   ├── Financeiro/
│   ├── Agenda/
│   ├── Equipamentos/
│   ├── PortalCliente/
│   ├── Wiki/
│   ├── Usuarios/
│   └── Relatorios/
│
├── Shared/
│
├── Support/
│
└── Infrastructure/
```

Cada novo domínio deverá seguir exatamente a mesma organização.

---

# 39. Convenções de Desenvolvimento

## Classes

Sempre em singular.

Exemplos:

Cliente

Projeto

Contrato

Lead

---

## Controllers

Sempre finalizar com:

Controller

Exemplo:

ProjectController

---

## Actions

Sempre utilizar:

Verbo + Entidade + Action

Exemplos:

CreateProjectAction

ApproveProjectAction

FinishTaskAction

ConvertLeadAction

---

## Events

Entidade + Evento

ProjectCreated

LeadConverted

InvoicePaid

---

## Jobs

Verbo + Objeto + Job

GenerateReportJob

SendEmailJob

CreateInvoiceJob

---

## Policies

Nome da entidade seguido de Policy.

ProjectPolicy

ClientPolicy

LeadPolicy

---

## Requests

Verbo + Entidade + Request

StoreProjectRequest

UpdateClientRequest

ApproveContractRequest

---

## DTOs

Nome da entidade seguido de Data.

ProjectData

ClientData

LeadData

---

# 40. Fluxo Completo de um Caso de Uso

Exemplo: criação de um Projeto.

```
Usuário

↓

Livewire

↓

Controller

↓

StoreProjectRequest

↓

CreateProjectAction

↓

ProjectService

↓

Project Model

↓

Banco de Dados

↓

ProjectCreated Event

↓

Listeners

↓

Timeline

↓

Automation Engine

↓

Notification Engine

↓

Audit Engine

↓

Dashboard Update
```

Toda operação do sistema deverá seguir essa filosofia.

---

# 41. Dependências Externas

O projeto deverá priorizar recursos nativos do Laravel.

Antes de adicionar qualquer biblioteca externa, responder:

- O Laravel já resolve este problema?
- A biblioteca possui manutenção ativa?
- Ela reduz significativamente o esforço de desenvolvimento?
- O benefício supera o custo de manutenção?

Caso a resposta seja negativa, a dependência não deverá ser adicionada.

---

# 42. Evolução da Arquitetura

Toda evolução deverá preservar os seguintes princípios:

- Compatibilidade com o Core.
- Baixo acoplamento.
- Alta coesão.
- Configuração acima de código.
- Reutilização.
- Escalabilidade.
- Testabilidade.
- Modularidade.

Nenhum novo módulo poderá violar esses princípios.

---

# 43. Checklist Arquitetural

Antes de implementar qualquer funcionalidade, verificar:

- [ ] A responsabilidade está no domínio correto?
- [ ] Existe alguma Engine do Core que possa ser reutilizada?
- [ ] A funcionalidade deve gerar eventos?
- [ ] Existe necessidade de automação?
- [ ] As permissões foram definidas?
- [ ] Há registros de auditoria?
- [ ] A Timeline será atualizada?
- [ ] Existe tratamento de exceções?
- [ ] A operação deve utilizar filas?
- [ ] Existe necessidade de cache?
- [ ] O código está desacoplado?
- [ ] A funcionalidade poderá ser reutilizada?

---

# 44. Considerações Finais

A arquitetura do AgencyOS foi projetada para sustentar uma plataforma operacional de longo prazo.

O Core deverá permanecer estável enquanto os Domínios evoluem de forma independente.

Toda funcionalidade deverá priorizar simplicidade, reutilização e configuração em vez de customizações específicas.

O sucesso da plataforma dependerá da disciplina arquitetural. Sempre que surgir uma nova necessidade, a solução deverá fortalecer o Core e preservar a independência dos Domínios, evitando acoplamentos desnecessários e garantindo que o sistema continue evoluindo de forma organizada e sustentável.