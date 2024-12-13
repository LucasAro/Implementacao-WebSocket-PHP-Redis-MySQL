FROM node:16-alpine

# Cria um diretório de trabalho
WORKDIR /app

# Copia o arquivo package.json e instala as dependências
COPY package.json .
RUN npm install

# Copia os arquivos do servidor WebSocket
COPY . .

# Expõe a porta do WebSocket
EXPOSE 3000

# Comando para rodar o servidor WebSocket
CMD ["node", "index.js"]
