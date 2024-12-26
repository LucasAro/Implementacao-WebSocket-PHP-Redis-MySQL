const Redis = require( 'ioredis' );
const WebSocket = require( 'ws' );

const redis = new Redis( {
	host: 'redis',
	port: 6379,
} );

const wss = new WebSocket.Server( { port: 3000 } );

// Map para armazenar os clientes conectados e seus IDs
const clients = new Map();

// Subscribing ao canal Redis
redis.subscribe( 'updates', () =>
{
	console.log( 'Subscribed to Redis channel: updates' );
} );

redis.on( 'message', ( channel, message ) =>
{
	console.log( `Received message on channel ${channel}: ${message}` ); // Adicionado para debug
	if ( channel === 'updates' )
	{
		const data = JSON.parse( message );

		// Verifica o ID do cliente e envia apenas para ele
		const targetId = String( data.targetId ); // Converte para string para garantir a comparação
		const targetClient = [...clients.entries()].find( ( [ws, id] ) => id === targetId );

		if ( targetClient )
		{
			const [ws] = targetClient;
			if ( ws.readyState === WebSocket.OPEN )
			{
				ws.send( JSON.stringify( { type: 'new_message', data: data.message } ) );
				console.log( `Message sent to client ID ${targetId}: ${data.message}` );
			} else
			{
				console.log( `WebSocket not open for client ID ${targetId}` );
			}
		} else
		{
			console.log( `No WebSocket found for client ID ${targetId}` );
		}
	}
} );


wss.on( 'connection', ( ws ) =>
{
	console.log( 'Client connected' );

	// Associa um ID ao cliente quando ele envia uma mensagem de "identificação"
	ws.on( 'message', ( message ) =>
	{
		const data = JSON.parse( message );
		if ( data.type === 'identify' )
		{
			const clientId = String( data.clientId ); // Converte para string
			clients.set( ws, clientId ); // Associa o cliente ao seu ID
			console.log( `Client ID ${clientId} associated` );
		}
	} );

	ws.on( 'close', () =>
	{
		console.log( 'Client disconnected' );
		clients.delete( ws ); // Remove o cliente desconectado do Map
	} );
} );
