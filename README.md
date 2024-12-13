# Implementação de WebSocket com PHP, Redis e MySQL

Este repositório apresenta uma prova de conceito (POC) para implementar WebSocket com Redis e PHP, oferecendo comunicação em tempo real eficiente e escalável. A solução inclui três componentes principais:

- Um servidor PHP para manipular requisições HTTP e publicar mensagens.
- Um servidor Node.js para gerenciar conexões WebSocket.
- Redis como um message broker para publicação e assinatura.

## Tecnologias Utilizadas

- **PHP (Apache)**: Para lidar com as requisições HTTP e interagir com o banco de dados.
- **Node.js**: Para gerenciar as conexões WebSocket e distribuir mensagens.
- **Redis**: Para intermediar mensagens entre o PHP e o servidor WebSocket.
- **MySQL**: Para armazenamento de mensagens persistentes.
- **Docker Compose**: Para facilitar o setup e execução do ambiente.

## Benefícios de WebSocket

1. **Baixa Latência**: Permite atualizações em tempo real, eliminando o atraso causado por requisições periódicas (polling).
2. **Redução de Carga no Servidor**: Mantém uma conexão persistente, evitando sobrecarga de requisições HTTP.
3. **Melhor Experiência do Usuário**: Notificações em tempo real aumentam a interatividade.
4. **Escalabilidade Melhorada**: O modelo baseado em eventos é mais eficiente para um grande número de conexões simultâneas.

## Passo a Passo para Rodar a POC

### 1. Clonar o Repositório

```bash
git clone https://github.com/LucasAro/Implementacao-WebSocket-PHP-Redis-MySQL.git
cd Implementacao-WebSocket-PHP-Redis-MySQL
```

### 2. Configurar o Ambiente
Certifique-se de ter o Docker e Docker Compose instalados no seu sistema.

### 3. Estrutura do Projeto

- `php-server/`: Contém os arquivos PHP, incluindo a lógica de publicação de mensagens no Redis.
- `websocket-server/`: Contém o código Node.js para o servidor WebSocket.
- `docker-compose.yml`: Arquivo de configuração para orquestração dos serviços.

### 4. Subir os Containers

Execute o comando abaixo para construir e iniciar todos os serviços:

```bash
docker compose up --build
```

Este comando iniciará:
- O servidor PHP no Apache (porta 8080).
- O servidor WebSocket (porta 3000).
- O Redis.
- O MySQL.

### 5. Configurar o Banco de Dados

Acesse o container MySQL e crie a tabela `messages`:

```sql
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message TEXT NOT NULL,
    target_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 6. Testar a API

Envie uma mensagem para o PHP, que publicará a mensagem no Redis e armazenará no MySQL:

```bash
curl -X POST http://localhost:8080 \
    -H "Content-Type: application/json" \
    -d '{"message": "Hello Client 1!", "targetId": 1}'
```

### 7. Verificar no Cliente HTML

Abra o arquivo `index.html` no navegador e conecte-se ao servidor WebSocket. A mensagem aparecerá em tempo real.

### 8. Testar Clientes Específicos

Para enviar mensagens para um cliente específico, inclua o `targetId` na requisição. O servidor WebSocket garantirá que apenas o cliente correspondente receba a mensagem.

## Comparativo: WebSocket vs Polling

| Característica           | Polling                          | WebSocket                         |
|--------------------------|----------------------------------|-----------------------------------|
| Frequência               | Requisições periódicas          | Tempo real                        |
| Uso de Recursos          | Alto (muitas requisições HTTP) | Baixo (conexão persistente)     |
| Escalabilidade           | Limitada                        | Alta                              |
| Experiência do Usuário   | Atraso                          | Instantânea                      |

## Considerações Finais

A implementação de WebSocket com Redis proporciona uma solução mais eficiente, escalável e responsiva para aplicações que exigem comunicação em tempo real. Esta POC pode ser expandida para incluir funcionalidades adicionais como autenticação de usuários, grupos de mensagens, e suporte a escala horizontal.

