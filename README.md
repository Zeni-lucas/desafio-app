# Desafio

Este projeto foi desenvolvido para atender a um desafio técnico de construção de uma API em Laravel, com foco no cadastro de usuários e gerenciamento de movimentações financeiras. Abaixo estão descritas as funcionalidades implementadas em cada etapa do desafio

## Etapa 1 - Cadastro de Usuários / Endpoint de Usuários

Na primeira etapa, foi solicitado a criação de uma estrutura para gerenciar usuários. As funcionalidades incluem:

- **Criar um endpoint para cadastrar usuários**, com os seguintes campos obrigatórios:
  - **name** | string (Nome)
  - **email** | string (E-mail)
  - **birthday** | date (Data de aniversário)
  - **created_at** | datetime (Criado em)
  - **updated_at** | datetime (Atualizado em)

- **Criar um endpoint para listar todos os usuários**, ordenados por ordem de cadastro decrescente (mais recente primeiro).

- **Criar um endpoint para visualizar um único usuário**, consultando pelo seu ID.

- **Criar um endpoint para excluir um usuário**, utilizando seu ID.

## Etapa 2 - Cadastro de Movimentações / Endpoint de Movimentações

Nesta etapa, a modelagem e a lógica foram desenvolvidas para gerenciar movimentações financeiras. As funcionalidades incluem:

- **Criar um endpoint para cadastrar uma movimentação**, com os seguintes dados:
  - **created_at** | datetime (Criado em)
  - **updated_at** | datetime (Atualizado em)
  - **produtos** | lista de produtos (nome, quantidade, valor)
  - **usuario** | referência ao usuário
  - **tipoDePagamento** | enum (CREDIT, DEBIT, MONEY)
  - **bloqueado** | boolean (Define se a movimentação está bloqueada)

- **Criar um endpoint para listar movimentações filtradas por tipo de pagamento** (ex.: somente débito ou crédito).

- **Criar um endpoint para visualizar todas as movimentações**, com paginação e as informações pessoais do usuário.

- **Criar um endpoint para excluir uma movimentação**, utilizando seu ID.

- **Criar um endpoint para exportar movimentações em formato CSV**, com os seguintes filtros:
  - Movimentações dos últimos 30 dias.
  - Movimentações filtradas por mês e ano (ex.: 06/2023).
  - Todas as movimentações.

- **Criar endpoints que retornam a soma das movimentações**:
  - Soma das movimentações por débito.
  - Soma das movimentações por crédito.
  - Soma total das movimentações (débito e crédito).

## Etapa 3 - Regras de Negócio

A última etapa do desafio envolveu a implementação de regras de negócio específicas:

- **No endpoint de exclusão de usuários**, foi adicionada uma regra para impedir a exclusão de um usuário que tenha qualquer tipo de movimentação ou saldo associado.

- **No endpoint de cadastro de usuários**, foi implementada uma validação para garantir que apenas maiores de 18 anos possam criar uma conta.



# Rodar o servidor
php artisan serve


 
 
