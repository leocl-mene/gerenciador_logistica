# Gerenciador Logistica

Sistema de gestao de demandas e operacao de motoristas, com painel web (Laravel) e aplicativo Flutter integrado.

## Visao geral

O sistema e dividido em duas partes principais:
- Web (Laravel): cadastro de veiculos, motoristas e administradores, criacao de demandas, acompanhamento em tempo real e relatorios.
- App (Flutter): motoristas aceitam/iniciam/finalizam demandas, enviam GPS e registram abastecimentos com foto do cupom.

Principais funcionalidades:
- Demandas normais e urgentes
- Rastreamento GPS em tempo real
- Relatorios de uso e custos
- Registro de abastecimentos
- Configuracao de preco da gasolina e consumo km/L por veiculo
- Notificacoes (FCM)

## Perfis de acesso

- Administrador: acesso total ao painel web e pode usar o app. Recebe todos os veiculos no app.
- Motorista: acesso ao app e operacao das demandas.

## Estrutura do projeto

- app/: modelos, controllers e regras de negocio
- routes/: rotas web e API
- resources/views/: telas do painel
- database/migrations/: migrations do banco

O aplicativo Flutter fica em uma pasta separada no mesmo workspace: `motoboy_app/`.

## Requisitos

- PHP 8.1+
- Composer
- Node.js + NPM
- Banco de dados (MySQL recomendado)
- Extensoes PHP comuns do Laravel

## Instalacao (backend)

1) Instale dependencias:
```
composer install
npm install
```

2) Configure o ambiente:
- Copie `.env.example` para `.env`
- Configure DB, APP_URL e credenciais do Firebase/FCM

3) Gere a chave:
```
php artisan key:generate
```

4) Rode as migrations:
```
php artisan migrate
```

5) Link de storage para uploads:
```
php artisan storage:link
```

6) Suba o servidor local:
```
php artisan serve
```

## Instalacao (app Flutter)

No diretorio `motoboy_app/`:
```
flutter pub get
flutter run
```

Configure o `baseUrl` no arquivo:
- `motoboy_app/lib/services/api_service.dart`

## API principal (resumo)

- POST `/api/login`
- POST `/api/logout`
- GET `/api/demandas-disponiveis`
- GET `/api/minhas-demandas`
- POST `/api/demandas/{id}/aceitar`
- POST `/api/demandas/{id}/iniciar`
- POST `/api/demandas/{id}/finalizar`
- POST `/api/demandas/{id}/track`
- POST `/api/status`
- POST `/api/fcm-token`
- GET `/api/meus-veiculos`
- POST `/api/abastecimentos`

## Relatorios

No painel web:
- Relatorio de demandas (km, custo e motorista)
- Relatorio de abastecimentos (valor e cupom)

O custo das demandas usa:
- Preco da gasolina (configuracao)
- Consumo km/L definido no veiculo (editavel)

## Uploads

Fotos de KM e cupons ficam em `storage/app/public` e sao servidas via `storage:link`.

## Observacoes

- Rotas e controllers ainda usam o nome tecnico `motoboy` para manter compatibilidade.
- O texto exibido no sistema foi padronizado para "Motorista".

## Licenca

Projeto proprietario.
