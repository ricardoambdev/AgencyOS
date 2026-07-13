# Uso e Processos — AgencyOS

Guia operacional para administradores e times da agência.

---

## 1. Primeiro acesso e workspace

1. Acesse `/register`.
2. Informe **nome**, **e-mail**, **senha** e **nome da empresa**.
3. O sistema cria:
   - a **empresa** (workspace) com `workspace_template = agency`,
   - o **usuário proprietário** com papel `owner` (capacidade `*`).
4. Um e-mail de **verificação** é enviado. Clique no link para ativar a conta.
5. Faça login em `/login`.

> Sem verificação, um banner no dashboard lembra o usuário de confirmar o e-mail.

## 2. Papéis e capacidades (RBAC)

- Cada usuário pertence a uma empresa via `CompanyUser` (papel + status).
- **Papéis** definem `capabilities` (ex.: `foo.view`, `config.manage`). `*` = acesso total.
- Gerencie em **Config → Usuários** (convite) e **Config → Papéis**.
- Rotas protegidas por `can:cap:xxx`; o menu lateral oculta itens sem capacidade.

## 3. Processos operacionais

### CRM / Lead
1. Crie um Lead (nome, empresa, valor, responsável, estágio).
2. Adicione **tags** e campos personalizados.
3. Favorite clicando na estrela.
4. Nos comentários, use **@nome** para mencionar e **reações** (👍 ❤️ 🎉).
5. Anexe arquivos na aba de anexos.
6. Mova o estágio conforme o funil.

### Cliente → Projeto → Tarefas
1. Converta/crie um Cliente.
2. Crie Projeto a partir de um **Template** (gera tarefas padrão) ou do zero.
3. Distribua tarefas (status, prioridade, horas estimadas, responsável, dependências).
4. Aponte **horas** nas tarefas; aprove conforme o fluxo.
5. Produção: brief → entregáveis → versões → aprovação (interna e do cliente).

### Financeiro
- Lançe contas a pagar/receber, categorize e marque como pagas.
- Relatório de fluxo de caixa em **Financeiro**.

### Comercial / Contratos
- Gere contratos a partir de modelos; exporte PDF; registre assinatura e anexos.

## 4. Automações e Webhooks

- Em **Config → Automações**, crie gatilhos:
  - *Ao criar entidade*, *ao comentar*, *tarefa concluída*, *fatura paga*…
  - Ação: notificar usuário, enviar e-mail, chamar **webhook**, mudar status, atribuir responsável.
- **Webhooks** recebem `POST` JSON com header `X-AgencyOS-Signature` (HMAC). Valide no destino com o `secret` exibido na tela.

## 5. Relatórios

- Acesse **Relatórios** para visão de CRM, Projetos, Faturamento, Tarefas e Carga.
- Exporte em **CSV** (padrão), **Excel (.xls)** ou **PDF/HTML imprimível**.
- Exportações pesadas são enfileiradas (`BuildReportExport`, fila `exports`) — em produção rode `php artisan queue:work --queue=exports`.

## 6. Portal do Cliente

- Gere um token de portal por cliente. O cliente acessa `/{token}` (link público assinado) e vê apenas seus projetos/tarefas/documentos.

## 7. Temas

- Botão **Dark/Light** no topo alterna o tema; a preferência é salva em `localStorage` e respeita `prefers-color-scheme`.

## 8. Recuperação de senha

- Em `/login`, clique em **Esqueci a senha** → informe o e-mail → link de reset → nova senha.

## 9. Backup e operações

- Backup do MySQL (`agency_os`) agendado.
- Filas: monitorar `php artisan queue:work --queue=exports --tries=3`.
- Auditoria: `AuditLog` registra ações sensíveis para rastreabilidade.
- Busca global: use a caixa de busca no topo para localizar leads, clientes, projetos, tarefas, contratos e páginas wiki.
